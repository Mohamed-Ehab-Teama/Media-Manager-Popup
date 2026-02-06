// Media Manager State
const mediaManager = {
    selectedItem: null,
    mediaItems: [],
    uploadedFiles: []
};


// ==========================================   Get Specific Cookie using its name => To get 'XSRF-TOKEN'
function getCookie(name) {
    return document.cookie
        .split('; ')
        .find(row => row.startsWith(name + '='))
        ?.split('=')[1];
}



// ==========================================   Initialize
function initMediaManager() {
    fetchMedia().then(items => {  // ① fetch media from server
        mediaManager.mediaItems = items.data; // ② store in global object
        renderMediaGrid(mediaManager.mediaItems); // ③ render grid
        setupEventListeners(); // ④ attach click/select behavior
    });
}



// ==========================================   Fetch Media
async function fetchMedia() {
    try {
        // const searchTerm = document.getElementById('searchInput')?.value || '';
        // const filterType = document.getElementById('filterType')?.value || 'all';
        // const response = await fetch(`/media?search=${searchTerm}&type=${filterType}`);

        // const response = await fetch(`http://media-manager-popup.test/media`);

        const response = await fetch(`/media`);
        const data = await response.json();
        // console.log('Fetched media:', data);
        return data;
    } catch (error) {
        console.error('Failed to fetch media:', error);
        alert('Failed to fetch media: ' + error);
        return [];
    }
}



// ==========================================   Render media grid 
function renderMediaGrid(items) {
    const grid = document.getElementById('mediaGrid');

    if (items.length == 0) {
        grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📁</div>
                        <h3>No media found</h3>
                        <p>Upload some files to get started</p>
                    </div>
                `;
        return;
    }

    grid.innerHTML = items.map(item => `
                <div class="media-item" data-id="${item.id}" onclick="selectMedia(${item.id})">
                    <div class="media-thumbnail ${item.type === 'document' ? 'file-icon' : ''}">
                        ${item.type === 'image'
            ? `<img src="${item.url}" alt="${item.alt || item.filename}">`
            : '📄'
        }
                    </div>
                    <div class="media-item-name">${item.filename}</div>
                </div>
            `).join('');
}




// ==========================================   Setup event listeners
function setupEventListeners() {
    // Tab switching
    document.querySelectorAll('.modal-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const tabName = tab.dataset.tab;
            switchTab(tabName);
        });
    });

    // Search
    document.getElementById('searchInput').addEventListener('input', (e) => {
        filterMedia();
    });

    // Filter
    document.getElementById('filterType').addEventListener('change', () => {
        filterMedia();
    });

    // Drag and drop
    const uploadZone = document.getElementById('uploadZone');

    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        handleFileUpload(e.dataTransfer.files);
    });

    // File input
    document.getElementById('fileInput').addEventListener('change', (e) => {
        handleFileUpload(e.target.files);
    });

    // Update metadata when editing
    ['detailsAlt', 'detailsTitle', 'detailsCaption'].forEach(id => {
        document.getElementById(id).addEventListener('change', updateMetadata);
    });
}



// ==========================================   Filter media
function filterMedia() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterType = document.getElementById('filterType').value;

    const filtered = mediaManager.mediaItems.filter(item => {
        const matchesSearch = item.filename.toLowerCase().includes(searchTerm) ||
            (item.alt && item.alt.toLowerCase().includes(searchTerm)) ||
            (item.title && item.title.toLowerCase().includes(searchTerm));

        const matchesType = filterType === 'all' || item.type === filterType;

        return matchesSearch && matchesType;
    });

    renderMediaGrid(filtered);
}






// ==========================================   Handle file upload
function handleFileUpload(files) {
    const progressContainer = document.getElementById('uploadProgress');
    progressContainer.style.display = 'block';
    progressContainer.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        // Validate file
        if (file.size > 5 * 1024 * 1024) {
            alert(`${file.name} is too large. Maximum size is 5 MB.`);
            return;
        }

        // Create progress UI
        const progressId = `upload-${Date.now()}-${index}`;
        const progressHtml = `
                    <div class="upload-item" id="${progressId}">
                        <div class="upload-item-name">${file.name}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                `;
        progressContainer.insertAdjacentHTML('beforeend', progressHtml);

        // Upload File
        uploadMedia(file, progressId);
    });
}



// ==========================================   Real : Upload file
function uploadMedia(file, progressId) {
    const progressBar = document.querySelector(`#${progressId} .progress-fill`);

    const formData = new FormData();
    formData.append('file', file);

    fetch('/media', {
        method: 'POST',
        credentials: 'same-origin', // VERY IMPORTANT
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                const item = data.data;

                // Add new item to mediaManager
                mediaManager.mediaItems.unshift(item);

                // Switch to library tab and render
                switchTab('library');
                renderMediaGrid(mediaManager.mediaItems);
                selectMedia(item.id);

                // Clear progress UI
                document.getElementById('uploadProgress').innerHTML = '';
                document.getElementById('uploadProgress').style.display = 'none';
                document.getElementById('fileInput').value = '';
            } else {
                alert('Upload failed: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Upload error:', err);
            alert('Upload failed. Check console.');
        });
}






// ==========================================   Select media item
function selectMedia(id) {
    const item = mediaManager.mediaItems.find(m => m.id === id);
    if (!item) return;

    // Update selection state
    mediaManager.selectedItem = item;

    // Update UI
    document.querySelectorAll('.media-item').forEach(el => {
        el.classList.toggle('selected', el.dataset.id == id);
    });

    // Show details sidebar
    showDetails(item);

    // Enable insert button
    document.getElementById('insertBtn').disabled = false;
    document.getElementById('selectionInfo').textContent = `1 item selected`;
}



// ==========================================   Show details sidebar
function showDetails(item) {
    const sidebar = document.getElementById('detailsSidebar');
    const preview = document.getElementById('detailsPreview');

    sidebar.classList.add('active');

    // Update preview
    if (item.type === 'image') {
        preview.innerHTML = `<img src="${item.url}" alt="${item.alt || item.filename}">`;
    } else {
        preview.innerHTML = `<div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 64px;">📄</div>`;
    }

    // Update fields
    document.getElementById('detailsFileName').value = item.filename;
    document.getElementById('detailsAlt').value = item.alt || '';
    document.getElementById('detailsTitle').value = item.title || '';
    document.getElementById('detailsCaption').value = item.caption || '';
    document.getElementById('detailsUrl').value = item.url;

    // Update info
    const info = document.getElementById('detailsInfo');
    info.innerHTML = `
                <div><strong>File Size:</strong> ${item.size}</div>
                ${item.dimensions ? `<div><strong>Dimensions:</strong> ${item.dimensions}</div>` : ''}
                <div><strong>Upload Date:</strong> ${item.uploadDate}</div>
                <div><strong>File Type:</strong> ${item.type}</div>
            `;
}



// ==========================================   Update metadata
function updateMetadata() {
    if (!mediaManager.selectedItem) return;

    mediaManager.selectedItem.alt = document.getElementById('detailsAlt').value;
    mediaManager.selectedItem.title = document.getElementById('detailsTitle').value;
    mediaManager.selectedItem.caption = document.getElementById('detailsCaption').value;

    // Here you would send an AJAX request to update the backend
    console.log('Metadata updated:', mediaManager.selectedItem);
}






// ==========================================   Switch tabs
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.modal-tab').forEach(tab => {
        tab.classList.toggle('active', tab.dataset.tab === tabName);
    });

    // Update panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.toggle('active', panel.id === `${tabName}-panel`);
    });
}


// ==========================================   Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}





// ==========================================   Copy URL
function copyUrl(button) {
    const input = document.getElementById('detailsUrl');
    const url = input.value;

    if (!url) {
        alert('No URL to copy');
        return;
    }

    // ✅ Modern way
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url)
            .then(() => successFeedback(button))
            .catch(() => fallbackCopy(input, button));
    }
    // 🧓 Old browsers / TinyMCE / iframe
    else {
        fallbackCopy(input, button);
    }
}

function fallbackCopy(input, button) {
    input.select();
    input.setSelectionRange(0, 99999); // mobile support

    try {
        document.execCommand('copy');
        successFeedback(button);
    } catch (err) {
        alert('Copy failed');
        console.error(err);
    }
}

function successFeedback(button) {
    if (!button) return;

    const originalText = button.textContent;
    button.textContent = '✅ Copied!';
    button.disabled = true;

    setTimeout(() => {
        button.textContent = originalText;
        button.disabled = false;
    }, 1500);
}




// ==========================================   Insert media into editor
function insertMedia() {
    if (!mediaManager.selectedItem) return;

    const item = mediaManager.selectedItem;

    // Generate HTML based on type
    let html = '';
    if (item.type === 'image') {
        html = `<img src="${item.url}" alt="${item.alt || ''}" width="200px" height="auto" title="${item.title || ''}" />`;
    } else {
        html = `<a href="${item.url}" target="_blank">${item.title || item.filename}</a>`;
    }

    console.log('Insert into editor:', html);
    // alert(`This will be inserted into TinyMCE:\n\n${html}`);

    // Here you would actually insert into TinyMCE
    tinymce.activeEditor.insertContent(html);

    closeMediaManager();
}




// ==========================================   Open modal
function openMediaManager() {
    document.getElementById('mediaModal').classList.add('active');
}


// ==========================================   Close modal
function closeMediaManager() {
    document.getElementById('mediaModal').classList.remove('active');
    mediaManager.selectedItem = null;
    document.getElementById('detailsSidebar').classList.remove('active');
    document.getElementById('insertBtn').disabled = true;
    document.getElementById('selectionInfo').textContent = 'No items selected';
}


// ==========================================   Close on overlay click
document.getElementById('mediaModal').addEventListener('click', (e) => {
    if (e.target.id === 'mediaModal') {
        closeMediaManager();
    }
});




// ==========================================   Initialize on page load
window.addEventListener('DOMContentLoaded', initMediaManager);
