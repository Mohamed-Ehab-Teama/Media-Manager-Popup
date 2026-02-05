{{-- <h1 style="margin-bottom: 20px;">Media Manager Demo</h1>
<button class="demo-btn" onclick="openMediaManager()">📁 Open Media Manager</button> --}}

<!-- Media Manager Modal -->
<div class="media-modal-overlay" id="mediaModal">
    <div class="media-modal">
        <!-- Header -->
        <div class="modal-header">
            <h2>Media Manager</h2>
            <button class="modal-close" onclick="closeMediaManager()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <!-- Sidebar Tabs -->
            <div class="modal-sidebar">
                <ul class="modal-tabs">
                    <li class="modal-tab active" data-tab="library">Media Library</li>
                    <li class="modal-tab" data-tab="upload">Upload Files</li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="modal-content">
                <!-- Media Library Tab -->
                <div class="tab-panel active" id="library-panel">
                    <!-- Search Toolbar -->
                    <div class="search-toolbar">
                        <input type="text" class="search-input" placeholder="Search media..." id="searchInput">
                        <select class="filter-select" id="filterType">
                            <option value="all">All Types</option>
                            <option value="image">Images</option>
                            <option value="document">Documents</option>
                        </select>
                    </div>

                    <!-- Media Grid -->
                    <div class="media-grid-container">
                        <div class="media-grid" id="mediaGrid">
                            <!-- Media items will be dynamically inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Upload Tab -->
                <div class="tab-panel" id="upload-panel">
                    <div class="upload-container">
                        <div class="upload-zone" id="uploadZone">
                            <div class="upload-icon">📤</div>
                            <h3>Drop files to upload</h3>
                            <p>or</p>
                            <button class="upload-btn" onclick="document.getElementById('fileInput').click()">
                                Select Files
                            </button>
                            <input type="file" id="fileInput" multiple accept="image/*,.pdf,.doc,.docx">
                            <p style="margin-top: 16px; font-size: 12px;">
                                Maximum upload file size: 5 MB
                            </p>

                            <!-- Upload Progress -->
                            <div class="upload-progress" id="uploadProgress" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Sidebar -->
            <div class="details-sidebar" id="detailsSidebar">
                <div class="details-content">
                    <div class="details-preview" id="detailsPreview"></div>

                    <div class="details-field">
                        <label>File Name</label>
                        <input type="text" id="detailsFileName" readonly disabled>
                    </div>

                    <div class="details-field">
                        <label>Alt Text</label>
                        <input type="text" id="detailsAlt" placeholder="Describe this image...">
                    </div>

                    <div class="details-field">
                        <label>Title</label>
                        <input type="text" id="detailsTitle" placeholder="Enter title...">
                    </div>

                    <div class="details-field">
                        <label>Caption</label>
                        <textarea id="detailsCaption" placeholder="Enter caption..."></textarea>
                    </div>

                    <div class="details-field">
                        <label>File URL</label>
                        <input type="text" id="detailsUrl" readonly >
                        <button class="copy-url-btn" onclick="copyUrl(this)">📋 Copy URL</button>
                    </div>

                    <div class="details-info" id="detailsInfo"></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <div class="selection-info" id="selectionInfo">No items selected</div>
            <div class="modal-actions">
                <button class="btn btn-cancel" onclick="closeMediaManager()">Cancel</button>
                <button class="btn btn-insert" id="insertBtn" disabled onclick="insertMedia()">Insert</button>
            </div>
        </div>
    </div>
</div>