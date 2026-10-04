@extends('layouts.app')

@section('title', $page = 'Folders')

@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">Manage {{$page}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row visually-hidden" id="more-details">
        <div class="col-xxl-12 col-md-12">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">More {{$page}} Details</h5>
                    <button class="btn btn-sm btn-danger close-detailed-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Close">
                        <span class="ti ti-x"></span>
                    </button>
                </div>
                <div class="card-body" id="detail-info-body">

                </div>
            </div>
        </div>
    </div>
    <div class="row" id="detailed-data-info">
        <div class="col-xxl-5 col-md-5 visually-hidden form-input">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white"><span class="btn-label">Create</span> {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-light expand-form resize-form" data-bs-toggle="tooltip" data-bs-placement="top" title="Resize">
                            <i class="ti ti-resize"></i>
                        </button>
                        <button class="btn btn-sm btn-danger close-form" data-bs-toggle="tooltip" data-bs-placement="top" title="Close">
                            <span class="ti ti-x"></span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="folderForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-folder me-1 fs-3 text-primary"></i>
                                        Folder Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="parent_id" name="parent_id" class="input-field form-select">
                                        <option value="">Select Parent Folder</option>
                                        <!-- Folders will be loaded dynamically -->
                                    </select>
                                    <label for="parent_id" class="input-label">
                                        <i class="ti ti-folder me-1 fs-3 text-primary"></i>
                                        Parent Folder
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="description" name="description" class="input-field form-control" placeholder=" "></textarea>
                                    <label for="description" class="input-label">
                                        <i class="ti ti-file-text me-1 fs-3 text-primary"></i>
                                        Description
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" type="checkbox" id="is_private" name="is_private">
                                    <label for="is_private" id="privacyLabel">Private: No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="folderForm" id="reset-btn">
                                        <i class="ti ti-x"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary ms-1" data-url="" id="create-btn">
                                        <i class="ti ti-device-floppy"></i>
                                        <span class="btn-label">Create</span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <div class="btn-group btn-group-sm me-2" role="group">
                            <button type="button" class="btn btn-light view-toggle active" data-view="list">
                                <i class="ti ti-list"></i>
                            </button>
                            <button type="button" class="btn btn-light view-toggle" data-view="grid">
                                <i class="ti ti-layout-grid"></i>
                            </button>
                        </div>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="folderForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                        <button class="btn btn-sm btn-light expand-details resize-details visually-hidden" data-bs-toggle="tooltip" data-bs-placement="top" title="Resize">
                            <i class="ti ti-resize"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search folders...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-privacy" class="form-select">
                                <option value="">All Privacy</option>
                                <option value="1">Private</option>
                                <option value="0">Public</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="reset-filters" class="btn btn-secondary btn-sm btn-h">Reset</button>
                        </div>
                    </div>

                    <!-- List View -->
                    <div class="table-responsive" id="list-view">
                        <table class="table table-bordered table-striped dataTable">
                            <tr><td id="table-loader"></td></tr>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div class="d-none" id="grid-view">
                        <div class="row g-3" id="folder-grid">
                            <!-- Folders will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Contents Modal -->
    <div class="modal fade" id="viewContentsModal" tabindex="-1" aria-labelledby="viewContentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="viewContentsModalLabel">Folder Contents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info p-2 mb-3">
                                <i class="ti ti-info-circle me-1"></i>
                                <span id="folder-path"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs" id="contentsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="subfolders-tab" data-bs-toggle="tab" data-bs-target="#subfolders" type="button" role="tab">Subfolders</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab">Files</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="letters-tab" data-bs-toggle="tab" data-bs-target="#letters" type="button" role="tab">Letters</button>
                                </li>
                            </ul>
                            <div class="tab-content p-3 border border-top-0" id="contentsTabsContent">
                                <div class="tab-pane fade show active" id="subfolders" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="btn-group view-toggle-group">
                                            <button type="button" class="btn btn-sm btn-light view-toggle active" data-view="list">
                                                <i class="ti ti-list"></i> List
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light view-toggle" data-view="grid">
                                                <i class="ti ti-layout-grid"></i> Grid
                                            </button>
                                        </div>
                                        <button class="btn btn-sm btn-primary create-subfolder" data-bs-toggle="tooltip" title="Create Subfolder">
                                            <i class="ti ti-folder-plus"></i> New Folder
                                        </button>
                                    </div>

                                    <div class="table-responsive" id="subfolders-list-view">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Privacy</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="subfolders-body">
                                            <!-- Subfolders will be loaded dynamically -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-none" id="subfolders-grid-view">
                                        <div class="row g-3" id="subfolders-grid">
                                            <!-- Subfolders grid will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="files" role="tabpanel">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="btn-group view-toggle-group">
                                            <button type="button" class="btn btn-sm btn-light view-toggle active" data-view="list">
                                                <i class="ti ti-list"></i> List
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light view-toggle" data-view="grid">
                                                <i class="ti ti-layout-grid"></i> Grid
                                            </button>
                                        </div>
                                        <button class="btn btn-sm btn-success upload-file-btn" data-bs-toggle="tooltip" title="Upload File">
                                            <i class="ti ti-upload"></i> Upload
                                        </button>
                                    </div>

                                    <div class="table-responsive" id="files-list-view">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Size</th>
                                                <th>Uploaded</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="files-body">
                                            <!-- Files will be loaded dynamically -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-none" id="files-grid-view">
                                        <div class="row g-3" id="files-grid">
                                            <!-- Files grid will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="letters" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Ref No.</th>
                                                <th>Subject</th>
                                                <th>Sender</th>
                                                <th>Date Received</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="letters-body">
                                            <!-- Letters will be loaded dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="uploadFileModalLabel">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadFileForm" enctype="multipart/form-data">
                        <input type="hidden" id="folder_id" name="folder_id">
                        <div class="mb-3">
                            <label for="file" class="form-label">File <span class="text-red">*</span></label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_private" name="is_private">
                                <label class="form-check-label" for="is_private">Private File</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="uploadFileBtn">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Folder Modal -->
    <div class="modal fade" id="newFolderModal" tabindex="-1" aria-labelledby="newFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="newFolderModalLabel">New Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newFolderForm">
                        <input type="hidden" id="parent_folder_id" name="parent_id">
                        <div class="mb-3">
                            <label for="folder_name" class="form-label">Folder Name <span class="text-red">*</span></label>
                            <input type="text" class="form-control" id="folder_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="folder_description" class="form-label">Description</label>
                            <textarea class="form-control" id="folder_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="folder_is_private" name="is_private">
                                <label class="form-check-label" for="folder_is_private">Private Folder</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="createFolderBtn">Create</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/folders';
            const filesUrl = '/web/v1/files';
            const lettersUrl = '/web/v1/letters';
            let currentFolderId = null;

            // Privacy toggle
            const privacyCheckbox = document.getElementById("is_private");
            const privacyLabel = document.getElementById("privacyLabel");
            privacyCheckbox.addEventListener("change", function (){
                privacyLabel.textContent = `Private: ${this.checked ? "Yes" : "No"}`;
            });

            const folderForm = document.getElementById("folderForm");
            const submitButton = folderForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // View toggle functionality
            $(document).on('click', '.view-toggle', function() {
                const viewType = $(this).data('view');
                const container = $(this).closest('.tab-pane').length ?
                    $(this).closest('.tab-pane') :
                    $('#detailed-data-info');

                // Toggle active class on buttons
                $(this).siblings('.view-toggle').removeClass('active');
                $(this).addClass('active');

                if (container.is('#subfolders')) {
                    // Subfolders view toggle
                    if (viewType === 'list') {
                        $('#subfolders-list-view').removeClass('d-none');
                        $('#subfolders-grid-view').addClass('d-none');
                    } else {
                        $('#subfolders-list-view').addClass('d-none');
                        $('#subfolders-grid-view').removeClass('d-none');
                    }
                } else if (container.is('#files')) {
                    // Files view toggle
                    if (viewType === 'list') {
                        $('#files-list-view').removeClass('d-none');
                        $('#files-grid-view').addClass('d-none');
                    } else {
                        $('#files-list-view').addClass('d-none');
                        $('#files-grid-view').removeClass('d-none');
                    }
                } else {
                    // Main view toggle
                    if (viewType === 'list') {
                        $('#list-view').removeClass('d-none');
                        $('#grid-view').addClass('d-none');
                    } else {
                        $('#list-view').addClass('d-none');
                        $('#grid-view').removeClass('d-none');
                    }
                }
            });

            // Form Submit Event Listener
            folderForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                // Run validation before submitting
                let isValid = true;
                document.querySelectorAll(".controlled").forEach(input => {
                    input.style.borderColor = "";
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    progressMessage.classList.remove("text-primary");
                    progressMessage.classList.add("error-message");
                    progressMessage.textContent = "Please fix the errors before submitting.";
                    return;
                }

                // Disable button and show loading spinner
                submitButton.disabled = true;
                btnIcon.classList.remove("ti-plus");
                btnIcon.classList.add("fa", "ti-loader", "fa-spin");
                progressMessage.textContent = "Processing section data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(folderForm);
                // Handle checkbox status field
                formData.set("is_private", privacyCheckbox.checked ? "1" : "0");

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

                const data = Object.fromEntries(formData);

                try {
                    let response = await fetch(dataUrl || apiUrl, {
                        method: isUpdate ? "PUT" : "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });

                    let res = await response.json();

                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                            addNewRecordToGrid(res.data);
                        } else if (isUpdate) {
                            await fetchFolders();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Department updated successfully!" : "Department created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            folderForm.reset();
                            privacyCheckbox.checked = true;
                            folderForm.classList.remove('was-validated');
                        }
                    } else {
                        if (res.errors) {
                            handleServerErrors(res.errors);
                        }
                        progressMessage.className = "text-danger small";
                        progressMessage.textContent = res.message || "Unexpected error occurred";
                        showToast('error', 'Error', res.message || 'Operation failed');
                    }
                } catch (error) {
                    progressMessage.className = "text-danger small";
                    progressMessage.textContent = error.message || "Network error occurred";
                    showToast('error', 'Error', error.message || 'Network error occurred');
                } finally {
                    submitButton.disabled = false;
                    btnIcon.classList.remove("ti-loader", "fa-spin");
                    btnIcon.classList.add("ti-device-floppy");
                }
            });

            function addNewRecordToTable(newRecord) {
                if (!$.fn.DataTable.isDataTable(tableElement)) {
                    initializeDataTable({ data: [newRecord] });
                    return;
                }

                const dataTable = tableElement.DataTable();
                dataTable.row.add(newRecord).draw(false);

                // Update serial numbers
                $(".dataTable tbody tr").each(function (index) {
                    $(this).find("td:first").text(index + 1);
                });
            }

            function addNewRecordToGrid(newRecord) {
                const gridElement = $('#folder-grid');
                const folderElement = createFolderGridElement(newRecord);
                gridElement.prepend(folderElement);
            }

            function createFolderGridElement(folder) {
                return `
                    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="card folder-card">
                            <div class="card-body text-center">
                                <div class="folder-icon">
                                    <i class="ti ti-folder text-warning" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="folder-name mt-2 mb-1 text-truncate" title="${folder.name}">${folder.name}</h6>
                                <small class="text-muted">${folder.description ? folder.description.substring(0, 20) + (folder.description.length > 20 ? '...' : '') : 'No description'}</small>
                                <div class="mt-2">
                                    <span class="badge ${folder.is_private ? 'bg-danger' : 'bg-success'}">${folder.is_private ? 'Private' : 'Public'}</span>
                                </div>
                                <div class="folder-actions mt-3">
                                    <button class="btn btn-sm btn-primary view-contents-btn" data-folder-id="${folder.id}" data-bs-toggle="tooltip" title="View Contents">
                                        <i class="ti ti-folder"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info show-detailed-info" data-label="${folder.name}" data-browse='${JSON.stringify(folder)}' data-bs-toggle="tooltip" title="Details">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning update-record" data-browse='${JSON.stringify(folder)}' data-for="folderForm" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger confirm-delete" data-label="${folder.name}" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Load initial data (parent folders)
            async function loadInitialData() {
                try {
                    showLoader();
                    // Load parent folders
                    const foldersResponse = await fetch(apiUrl);
                    const foldersData = await foldersResponse.json();
                    populateSelect('#parent_id', foldersData.data);
                } catch (error) {
                    toastr.error("Failed to load initial data");
                }
            }

            // Fetch folders and populate the DataTable
            async function fetchFolders(filters = {}) {
                try {
                    showLoader();

                    // Build query string from filters
                    const queryParams = new URLSearchParams();
                    for (const key in filters) {
                        if (filters[key]) {
                            queryParams.append(key, filters[key]);
                        }
                    }

                    const url = queryParams.toString() ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const res = await response.json();
                    initializeDataTable(res);
                    initializeGrid(res);
                } catch (error) {
                    showToast('error', 'Error', error.message);
                } finally {
                    hideLoader();
                }
            }

            function initializeGrid(res) {
                const data = res.data;
                const gridElement = $('#folder-grid');
                gridElement.empty();

                if (data.length === 0) {
                    gridElement.html('<div class="col-12 text-center py-5"><i class="ti ti-folder-off fs-5"></i><p class="mt-2">No folders found</p></div>');
                    return;
                }

                data.forEach(folder => {
                    const folderElement = createFolderGridElement(folder);
                    gridElement.append(folderElement);
                });

                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            }

            function initializeDataTable(res) {
                const data = res.data;
                tableElement.DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        {
                            title: "SN",
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { title: "Name", data: "name" },
                        {
                            title: "Path",
                            data: null,
                            render: function(data) {
                                return getFolderPath(data);
                            }
                        },
                        {
                            title: "Privacy",
                            data: "is_private",
                            render: function(data) {
                                return `<span class="badge ${data ? 'bg-danger' : 'bg-success'}">${data ? 'Private' : 'Public'}</span>`;
                            }
                        },
                        {
                            title: "Contents",
                            data: null,
                            render: function(data) {
                                return `
                                    <button class="btn btn-sm btn-primary view-contents-btn" data-folder-id="${data.id}">
                                        <i class="ti ti-folder"></i> View Contents
                                    </button>
                                `;
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="folderForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-success upload-file-btn" data-folder-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Upload File">
                                            <i class="ti ti-upload"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.name}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: "Loading...",
                        emptyTable: "No folder records available"
                    }
                });
            }

            function getFolderPath(folder) {
                let path = folder.name;
                let parent = folder.parent;

                while (parent) {
                    path = parent.name + ' / ' + path;
                    parent = parent.parent;
                }

                return path;
            }

            // View contents button handler
            $(document).on('click', '.view-contents-btn', async function() {
                currentFolderId = $(this).data('folder-id');

                // Show loading state
                $('#subfolders-body').html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#files-body').html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#letters-body').html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#subfolders-grid').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                $('#files-grid').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

                $('#viewContentsModal').modal('show');

                try {
                    // Fetch folder details to show path
                    const folderResponse = await fetch(`${apiUrl}/${currentFolderId}`);
                    const folderResult = await folderResponse.json();

                    if (folderResponse.ok) {
                        $('#folder-path').text(getFolderPath(folderResult.data));
                    }

                    // Fetch folder contents
                    const contentsResponse = await fetch(`${apiUrl}/${currentFolderId}/contents`);
                    const contentsResult = await contentsResponse.json();

                    if (contentsResponse.ok) {
                        // Populate subfolders
                        populateSubfolders(contentsResult.data.folders || []);

                        // Populate files
                        populateFiles(contentsResult.data.files || []);

                        // Populate letters
                        populateLetters(contentsResult.data.letters || []);
                    } else {
                        showErrorInContents();
                    }
                } catch (error) {
                    showErrorInContents();
                }
            });

            function populateSubfolders(folders) {
                let listHtml = '';
                let gridHtml = '';

                if (folders.length > 0) {
                    // List view content
                    folders.forEach(folder => {
                        listHtml += `
                            <tr>
                                <td>${folder.name}</td>
                                <td>${folder.description || 'N/A'}</td>
                                <td><span class="badge ${folder.is_private ? 'bg-danger' : 'bg-success'}">${folder.is_private ? 'Private' : 'Public'}</span></td>
                                <td>${new Date(folder.created_at).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-contents-btn" data-folder-id="${folder.id}" data-bs-toggle="tooltip" title="View Contents">
                                        <i class="ti ti-folder"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info show-detailed-info" data-label="${folder.name}" data-browse='${JSON.stringify(folder)}' data-bs-toggle="tooltip" title="Details">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning update-record" data-browse='${JSON.stringify(folder)}' data-for="folderForm" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger confirm-delete" data-label="${folder.name}" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Grid view content
                        gridHtml += `
                            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="card folder-card">
                                    <div class="card-body text-center">
                                        <div class="folder-icon">
                                            <i class="ti ti-folder text-warning" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="folder-name mt-2 mb-1 text-truncate" title="${folder.name}">${folder.name}</h6>
                                        <small class="text-muted">${folder.description ? folder.description.substring(0, 20) + (folder.description.length > 20 ? '...' : '') : 'No description'}</small>
                                        <div class="mt-2">
                                            <span class="badge ${folder.is_private ? 'bg-danger' : 'bg-success'}">${folder.is_private ? 'Private' : 'Public'}</span>
                                        </div>
                                        <div class="folder-actions mt-3">
                                            <button class="btn btn-sm btn-primary view-contents-btn" data-folder-id="${folder.id}" data-bs-toggle="tooltip" title="View Contents">
                                                <i class="ti ti-folder"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info show-detailed-info" data-label="${folder.name}" data-browse='${JSON.stringify(folder)}' data-bs-toggle="tooltip" title="Details">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning update-record" data-browse='${JSON.stringify(folder)}' data-for="folderForm" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger confirm-delete" data-label="${folder.name}" data-url="${apiUrl}/${folder.id}" data-bs-toggle="tooltip" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    listHtml = '<tr><td colspan="5" class="text-center">No subfolders found</td></tr>';
                    gridHtml = '<div class="col-12 text-center py-5"><i class="ti ti-folder-off fs-5"></i><p class="mt-2">No subfolders found</p></div>';
                }

                $('#subfolders-body').html(listHtml);
                $('#subfolders-grid').html(gridHtml);

                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            }

            function populateFiles(files) {
                let listHtml = '';
                let gridHtml = '';

                if (files.length > 0) {
                    // List view content
                    files.forEach(file => {
                        listHtml += `
                            <tr>
                                <td>${file.file_name}</td>
                                <td>${file.file_type}</td>
                                <td>${formatFileSize(file.file_size)}</td>
                                <td>${new Date(file.created_at).toLocaleDateString()}</td>
                                <td>
                                    <a href="/api/v1/files/${file.id}/download" class="btn btn-sm btn-primary" download data-bs-toggle="tooltip" title="Download">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    <button class="btn btn-sm btn-info show-detailed-info" data-label="${file.file_name}" data-browse='${JSON.stringify(file)}' data-bs-toggle="tooltip" title="Details">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-file-btn" data-file-id="${file.id}" data-bs-toggle="tooltip" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Grid view content
                        gridHtml += `
                            <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="card file-card">
                                    <div class="card-body text-center">
                                        <div class="file-icon">
                                            <i class="ti ${getFileIcon(file.file_type)} text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <h6 class="file-name mt-2 mb-1 text-truncate" title="${file.file_name}">${file.file_name}</h6>
                                        <small class="text-muted">${formatFileSize(file.file_size)}</small>
                                        <div class="file-actions mt-3">
                                            <a href="/api/v1/files/${file.id}/download" class="btn btn-sm btn-primary" download data-bs-toggle="tooltip" title="Download">
                                                <i class="ti ti-download"></i>
                                            </a>
                                            <button class="btn btn-sm btn-info show-detailed-info" data-label="${file.file_name}" data-browse='${JSON.stringify(file)}' data-bs-toggle="tooltip" title="Details">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-file-btn" data-file-id="${file.id}" data-bs-toggle="tooltip" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    listHtml = '<tr><td colspan="5" class="text-center">No files found</td></tr>';
                    gridHtml = '<div class="col-12 text-center py-5"><i class="ti ti-file-off fs-5"></i><p class="mt-2">No files found</p></div>';
                }

                $('#files-body').html(listHtml);
                $('#files-grid').html(gridHtml);

                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            }

            function populateLetters(letters) {
                let html = '';

                if (letters.length > 0) {
                    letters.forEach(letter => {
                        html += `
                            <tr>
                                <td>${letter.reference_number}</td>
                                <td>${letter.subject}</td>
                                <td>${letter.sender}</td>
                                <td>${new Date(letter.date_received).toLocaleDateString()}</td>
                                <td>
                                    <a href="/letters/${letter.id}" class="btn btn-sm btn-info">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center">No letters found</td></tr>';
                }

                $('#letters-body').html(html);
            }

            function showErrorInContents() {
                $('#subfolders-body').html('<tr><td colspan="5" class="text-center text-danger">Failed to load contents</td></tr>');
                $('#files-body').html('<tr><td colspan="5" class="text-center text-danger">Failed to load contents</td></tr>');
                $('#letters-body').html('<tr><td colspan="5" class="text-center text-danger">Failed to load contents</td></tr>');
                $('#subfolders-grid').html('<div class="col-12 text-center py-5 text-danger"><i class="ti ti-alert-circle fs-5"></i><p class="mt-2">Failed to load contents</p></div>');
                $('#files-grid').html('<div class="col-12 text-center py-5 text-danger"><i class="ti ti-alert-circle fs-5"></i><p class="mt-2">Failed to load contents</p></div>');
            }

            function getFileIcon(fileType) {
                if (!fileType) return 'ti-file';

                const type = fileType.toLowerCase();
                if (type.includes('pdf')) return 'ti-file-text';
                if (type.includes('word')) return 'ti-file-text';
                if (type.includes('excel')) return 'ti-file-spreadsheet';
                if (type.includes('powerpoint')) return 'ti-file-presentation';
                if (type.includes('image')) return 'ti-photo';
                if (type.includes('video')) return 'ti-video';
                if (type.includes('audio')) return 'ti-music';
                if (type.includes('zip') || type.includes('rar') || type.includes('7z')) return 'ti-file-zip';

                return 'ti-file';
            }

            // Create subfolder button handler
            $(document).on('click', '.create-subfolder', function() {
                $('#parent_folder_id').val(currentFolderId);
                $('#newFolderForm')[0].reset();
                $('#newFolderModal').modal('show');
            });

            // Create folder button in modal
            $('#createFolderBtn').click(async function() {
                const form = document.getElementById('newFolderForm');
                const formData = new FormData(form);

                if (!formData.get('name')) {
                    toastr.error('Folder name is required');
                    return;
                }

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Creating...');

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Folder created successfully');
                        $('#newFolderModal').modal('hide');

                        // Refresh the contents view
                        $(`.view-contents-btn[data-folder-id="${currentFolderId}"]`).trigger('click');
                    } else {
                        toastr.error(result.message || 'Failed to create folder');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Create');
                }
            });

            // Upload file button handler
            $(document).on('click', '.upload-file-btn', function() {
                const folderId = $(this).data('folder-id') || currentFolderId;
                $('#folder_id').val(folderId);
                $('#uploadFileForm')[0].reset();
                $('#uploadFileModal').modal('show');
            });

            // Upload file
            $('#uploadFileBtn').click(async function() {
                const form = document.getElementById('uploadFileForm');
                const formData = new FormData(form);

                if (!formData.get('file')) {
                    toastr.error('File is required');
                    return;
                }

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Uploading...');

                    const response = await fetch('/web/v1/files/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'File uploaded successfully');
                        $('#uploadFileModal').modal('hide');
                        // Refresh the contents view if open
                        if ($('#viewContentsModal').is(':visible')) {
                            $(`.view-contents-btn[data-folder-id="${currentFolderId}"]`).trigger('click');
                        }
                    } else {
                        toastr.error(result.message || 'Failed to upload file');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Upload');
                }
            });

            // Delete file
            $(document).on('click', '.delete-file-btn', async function() {
                const fileId = $(this).data('file-id');

                if (confirm('Are you sure you want to delete this file?')) {
                    try {
                        $(this).html('<span class="ti ti-loader fa fa-spin"></span>');

                        const response = await fetch(`${filesUrl}/${fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            toastr.success(result.message || 'File deleted successfully');
                            // Refresh the contents view
                            $(`.view-contents-btn[data-folder-id="${currentFolderId}"]`).trigger('click');
                        } else {
                            toastr.error(result.message || 'Failed to delete file');
                        }
                    } catch (error) {
                        toastr.error(error.message || 'An error occurred');
                    }
                }
            });

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i]);
            }

            // Search functionality
            $('#search-btn').click(async function () {
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchFolders({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by privacy
            $('#filter-privacy').change(function() {
                const isPrivate = $(this).val();
                fetchFolders({is_private: isPrivate});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-privacy').val('');
                fetchFolders();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchFolders();
            });
        });
    </script>
@endpush
