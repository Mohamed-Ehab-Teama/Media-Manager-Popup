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



// ============================ Show Media Details
function showMediaDetails(media) {
    document.getElementById('preview').src = media.url;
    document.getElementById('media-title').value = media.title ?? '';
    document.getElementById('media-alt').value = media.alt ?? '';
    document.getElementById('media-caption').value = media.caption ?? '';
}