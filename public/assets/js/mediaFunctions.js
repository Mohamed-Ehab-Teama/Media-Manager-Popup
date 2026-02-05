// ============================ Loads All Media + Handling Pagination
let page = 1;

function loadMedia(search = '') {
    fetch(`/media?page=${page}&search=${search}`)
        .then(res => res.json())
        .then(data => {
            const grid = document.getElementById('media-grid');
            grid.innerHTML = '';

            data.data.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('media-item');

                if (item.mime_type.startsWith('image/')) {
                    div.innerHTML = `<img src="${item.url}" />`;
                } else {
                    div.innerHTML = `<span>${item.title}</span>`;
                }

                div.onclick = () => selectMedia(item);
                grid.appendChild(div);
            });
        });
}

loadMedia();


// ============================ Select Midea
let selectedMedia = null;

function selectMedia(item) {
    selectedMedia = item;

    document.querySelectorAll('.media-item')
        .forEach(el => el.classList.remove('active'));

    event.currentTarget.classList.add('active');

    showMediaDetails(item);
}



// ============================ Show Media Details && Save Media meta-data
function showMediaDetails(media) {
    selectedMedia = media;

    document.getElementById('media-details').classList.remove('hidden');
    document.getElementById('media-preview').src = media.url;
    document.getElementById('media-title').value = media.title ?? '';
    document.getElementById('media-alt').value = media.alt ?? '';
    document.getElementById('media-caption').value = media.caption ?? '';
}

function saveMediaMeta() {
    if (!selectedMedia) return;

    fetch(`/media/${selectedMedia.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            title: document.getElementById('media-title').value,
            alt: document.getElementById('media-alt').value,
            caption: document.getElementById('media-caption').value
        })
    })
        .then(res => res.json())
        .then(data => {
            selectedMedia = data.media;
            alert('Saved ✔');
        });
}




// ============================ Show Tab
function showTab(tab) {
    document.getElementById('tab-library').classList.add('hidden');
    document.getElementById('tab-upload').classList.add('hidden');

    document.getElementById(`tab-${tab}`).classList.remove('hidden');
}




// ============================ Drag & Drop
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('file-input');

dropzone.onclick = () => fileInput.click();

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.classList.add('dragging');
});

dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragging');
});

dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('dragging');

    uploadFiles(e.dataTransfer.files);
});

fileInput.addEventListener('change', () => {
    uploadFiles(fileInput.files);
});




// ============================ Upload with Progress
function uploadFiles(files) {
    [...files].forEach(file => {
        const formData = new FormData();
        formData.append('file', file);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/media/upload');

        xhr.setRequestHeader(
            'X-CSRF-TOKEN',
            document.querySelector('meta[name="csrf-token"]').content
        );

        const progress = document.createElement('div');
        progress.innerText = `Uploading ${file.name}`;
        document.getElementById('upload-progress').appendChild(progress);

        xhr.upload.onprogress = e => {
            const percent = Math.round((e.loaded / e.total) * 100);
            progress.innerText = `${file.name} - ${percent}%`;
        };

        xhr.onload = () => {
            const media = JSON.parse(xhr.responseText);

            progress.innerText = `${file.name} ✔`;

            // Switch to library
            showTab('library');

            // Reload media & auto-select
            loadMedia();
            setTimeout(() => selectMedia(media), 500);
        };

        xhr.send(formData);
    });
}





// ============================ T
// ============================ T
// ============================ T
// ============================ T