@extends('layouts.app')

@section('title', $page = 'Menus')

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
                <div class="card-body" id="detail-info-body"></div>
            </div>
        </div>
    </div>
    
    <div class="row" id="detailed-data-info">
        <!-- Create/Edit Menu Form -->
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
                    <form id="menuForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-menu-2 me-1 fs-3 text-primary"></i>
                                        Menu Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="slug" name="slug" class="input-field form-control controlled" placeholder=" ">
                                    <label for="slug" class="input-label">
                                        <i class="ti ti-link me-1 fs-3 text-primary"></i>
                                        Menu Slug <span class="text-red"> *</span>
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
                                <div class="input-container">
                                    <input type="text" id="location" name="location" class="input-field form-control controlled" placeholder=" ">
                                    <label for="location" class="input-label">
                                        <i class="ti ti-location me-1 fs-3 text-primary"></i>
                                        Location <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" type="checkbox" id="is_active" name="is_active" checked>
                                    <label for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="menuForm" id="reset-btn">
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
        
        <!-- Main Menu List -->
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="menuForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search menus...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-location" class="form-select">
                                <option value="">All Locations</option>
                                <!-- Locations will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="reset-filters" class="btn btn-secondary btn-sm btn-h">Reset</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <tr><td id="table-loader"></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Items Modal -->
    <div class="modal fade" id="menuItemsModal" tabindex="-1" aria-labelledby="menuItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="menuItemsModalLabel">Manage Menu Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Add New Menu Item</h6>
                                </div>
                                <div class="card-body">
                                    <form id="menuItemForm">
                                        <input type="hidden" id="menu_id" name="menu_id">
                                        <input type="hidden" id="item_id" name="id">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="title" name="title" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="item-slug" name="slug" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                                <select class="form-select" id="type" name="type" required>
                                                    <option value="link">Link</option>
                                                    <option value="dropdown">Dropdown</option>
                                                    <option value="content">Content</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="url" class="form-label">URL</label>
                                                <input type="text" class="form-control" id="url" name="url">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="route" class="form-label">Route</label>
                                                <input type="text" class="form-control" id="route" name="route">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="target" class="form-label">Target</label>
                                                <select class="form-select" id="target" name="target">
                                                    <option value="_self">Same Tab</option>
                                                    <option value="_blank">New Tab</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="icon_class" class="form-label">Icon Class</label>
                                                <input type="text" class="form-control" id="icon_class" name="icon_class">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="color" class="form-label">Color</label>
                                                <input type="color" class="form-control form-control-color" id="color" name="color">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="parent_id" class="form-label">Parent Item</label>
                                                <select class="form-select" id="parent_id" name="parent_id">
                                                    <option value="">No Parent</option>
                                                    <!-- Parent items will be loaded dynamically -->
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <div class="form-check form-switch ms-md-3">
                                                    <input class="form-check-input" type="checkbox" id="is_active_item" name="is_active" checked>
                                                    <label class="form-check-label" for="is_active_item">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 content-fields d-none">
                                            <label for="content" class="form-label">Content</label>
                                            <textarea class="form-control" id="content" name="content" rows="6"></textarea>
                                            <div id="content-editor" style="height: 200px; display:none;"></div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-sm btn-secondary" id="cancel-edit" style="display: none;">
                                                Cancel Edit
                                            </button>
                                            <button type="submit" class="btn btn-sm btn-primary ms-auto">
                                                <span id="item-action-text">Add Item</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Menu Items</h6>
                                    <div>
                                        <button class="btn btn-sm btn-light" id="save-menu-order">
                                            <i class="ti ti-device-floppy"></i> Save Order
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dd" id="menu-items-nestable">
                                        <ol class="dd-list" id="menu-items-list">
                                            <!-- Menu items will be loaded dynamically -->
                                        </ol>
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
@endsection

@push("styles")
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" rel="stylesheet">
    <!-- CKEditor 5 Classic Theme -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/themes/lark.css">
    <style>
        .dd-handle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            cursor: move;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .dd-title {
            flex-grow: 1;
            font-weight: 500;
        }
        .dd-actions {
            margin-left: 10px;
            display: flex;
        }
        .dd-actions button {
            padding: 2px 5px;
            margin-left: 3px;
            font-size: 12px;
        }
        .dd-empty, .dd-placeholder {
            background-color: #f1f1f1;
            border: 1px dashed #bbb;
        }
        .dd-item > button {
            height: 36px;
        }
        .dd-item > button:before {
            color: #6c757d;
        }
        .dd-item.dd-expanded > button:before {
            color: #0d6efd;
        }
        .menu-item-active {
            background-color: #e8f4ff;
        }
        .menu-item-inactive {
            opacity: 0.7;
        }
        .dd-item.dd-collapsed > ol {
            display: none;
        }
        .dd-item.dd-expanded > ol {
            display: block;
        }

        .dd-actions {
            z-index: 10; /* Ensure buttons stay above other elements */
            pointer-events: auto; /* Make sure buttons can be clicked */
        }

        .dd-handle {
            position: relative; /* Create stacking context */
        }

        .dd-handle > *:not(.dd-actions) {
            pointer-events: none; /* Prevent other elements from blocking clicks */
        }

        .dd-actions button {
            pointer-events: auto; /* Ensure buttons are clickable */
            position: relative; /* Maintain proper stacking */
        }
    </style>
@endpush

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <!-- CKEditor 5 Classic build -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let ckeditorInstance = null;

        function initCKEditor() {
            if (ckeditorInstance) {
                ckeditorInstance.destroy().then(() => {
                    ckeditorInstance = null;
                    createCKEditor();
                });
            } else {
                createCKEditor();
            }
        }

        function createCKEditor() {
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'superscript', 'subscript', '|',
                        'fontFamily', 'fontSize', '|',
                        'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', '|',
                        'blockQuote', 'codeBlock', '|',
                        'link', 'imageUpload', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo', 'alignment', 'highlight', 'removeFormat'
                    ],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                            { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                            { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                            { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                        ]
                    },
                    fontFamily: {
                        options: [
                            'default',
                            'Arial, Helvetica, sans-serif',
                            'Courier New, Courier, monospace',
                            'Georgia, serif',
                            'Lucida Sans Unicode, Lucida Grande, sans-serif',
                            'Tahoma, Geneva, sans-serif',
                            'Times New Roman, Times, serif',
                            'Trebuchet MS, Helvetica, sans-serif',
                            'Verdana, Geneva, sans-serif'
                        ]
                    },
                    fontSize: {
                        options: [
                            9,
                            11,
                            13,
                            'default',
                            17,
                            19,
                            21
                        ]
                    }
                })
                .then(editor => {
                    ckeditorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/menus';
            const menuItemsUrl = '/web/v1/menu-items';

            // Initialize CKEditor if content field is visible
            function maybeInitCKEditor() {
                if ($('.content-fields').is(':visible')) {
                    setTimeout(() => {
                        initCKEditor();
                    }, 100);
                }
            }

            // Show/hide content fields and initialize CKEditor
            $('#type').change(function() {
                const type = $(this).val();
                $('.link-fields').toggle(type !== 'content');
                $('.content-fields').toggle(type === 'content');
                if (type === 'content') {
                    $('.content-fields').removeClass('d-none');
                    maybeInitCKEditor();
                } else {
                    $('.content-fields').addClass('d-none');
                    if (ckeditorInstance) {
                        ckeditorInstance.destroy().then(() => {
                            ckeditorInstance = null;
                        });
                    }
                }
            });

            // Initial CKEditor setup if content field is visible on load
            if ($('#type').val() === 'content') {
                $('.content-fields').removeClass('d-none');
                maybeInitCKEditor();
            }

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

            const menuForm = document.getElementById("menuForm");
            const submitButton = menuForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            menuForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing menu data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());
                const statusCheckbox = menuForm.querySelector("input[name='is_active']");
                const formData = new FormData(menuForm);
                // Handle checkbox status field
                formData.set("is_active", statusCheckbox.checked ? "1" : "0");

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
                        } else if (isUpdate) {
                            await fetchMenus(); 
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Department updated successfully!" : "Department created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            menuForm.reset();
                            statusCheckbox.checked = true;
                            menuForm.classList.remove('was-validated');
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

            // Load initial data (locations)
            async function loadInitialData() {
                try {
                    showLoader();
                    const response = await fetch(apiUrl);
                    const data = await response.json();
                    
                    if (data.success && data.data) {
                        const locations = [...new Set(data.data.map(menu => menu.location))];
                        const locationSelect = document.getElementById('filter-location');
                        
                        locations.forEach(location => {
                            const option = new Option(location, location);
                            locationSelect.add(option);
                        });
                    }
                } catch (error) {
                    toastr.error("Failed to load initial data");
                }
            }

            // Fetch menus and populate the DataTable
            async function fetchMenus(filters = {}) {
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
                } catch (error) {
                    showToast('error', 'Error', error.message);
                } finally {
                    hideLoader();
                }
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
                        { title: "Slug", data: "slug" },
                        { title: "Location", data: "location" },
                        {
                            title: "Items",
                            data: "items",
                            render: function(data) {
                                return data ? data.length : 0;
                            }
                        },
                        {
                            title: "Status",
                            data: "is_active",
                            render: function(data) {
                                const badgeClass = data ? 'badge bg-success' : 'badge bg-danger';
                                const statusText = data ? 'Active' : 'Inactive';
                                return `<span class="${badgeClass}">${statusText}</span>`;
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                // Escape single quotes and ampersands in JSON string to avoid HTML/JS issues
                                function escapeForHtmlAttr(str) {
                                    return str
                                        .replace(/&/g, '&amp;')
                                        .replace(/'/g, '&#39;')
                                        .replace(/"/g, '&quot;')
                                        .replace(/</g, '&lt;')
                                        .replace(/>/g, '&gt;');
                                }
                                const jsonData = escapeForHtmlAttr(JSON.stringify(data));
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info manage-items" data-menu-id="${row.id}" data-menu-name="${escapeForHtmlAttr(row.name)}">
                                            <i class="ti ti-list"></i> Items
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${jsonData}' data-for="menuForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${escapeForHtmlAttr(row.name)}" data-url="${apiUrl}/${row.id}" data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No menu records available"
                    }
                });
            }

            // Menu items management
            $(document).on('click', '.manage-items', function() {
                const menuId = $(this).data('menu-id');
                const menuName = $(this).data('menu-name');
                
                $('#menuItemsModalLabel').text(`Manage Items: ${menuName}`);
                $('#menu_id').val(menuId);
                $('#menuItemForm')[0].reset();
                $('#item_id').val('');
                $('#cancel-edit').hide();
                $('#item-action-text').text('Add Item');
                if (ckeditorInstance) {
                    ckeditorInstance.setData('');
                }
                
                // Load menu items
                loadMenuItems(menuId);
                
                $('#menuItemsModal').modal('show');
            });

            // Load menu items for a specific menu
            async function loadMenuItems(menuId) {
                try {
                    const response = await fetch(`${apiUrl}/${menuId}/items`);
                    const result = await response.json();
                    
                    if (response.ok && result.data) {
                        // Load parent items dropdown
                        const parentSelect = document.getElementById('parent_id');
                        while (parentSelect.options.length > 1) {
                            parentSelect.remove(1);
                        }
                        
                        // Add "No Parent" option first
                        result.data.forEach(item => {
                            if (!item.parent_id) {
                                const option = new Option(item.title, item.id);
                                parentSelect.add(option);
                            }
                        });
                        
                        // Initialize nestable with proper hierarchy
                        $('#menu-items-list').empty();
                        buildNestableList(result.data, $('#menu-items-list'));
                        
                        // Initialize nestable plugin
                        $('#menu-items-nestable').nestable({
                            group: 1,
                            maxDepth: 5,
                            serialize: function(element) {
                                return $(element).children('li').map(function() {
                                    const $li = $(this);
                                    const item = {
                                        id: $li.data('id'),
                                        children: $li.children('ol').length ? this.serialize($li.children('ol')) : []
                                    };
                                    return item;
                                }).get();
                            }
                        });
                    }
                } catch (error) {
                    toastr.error('Failed to load menu items');
                }
            }

            // Build nestable list from menu items with proper hierarchy
            function buildNestableList(items, parentElement, parentId = null) {
                items
                    .filter(item => item.parent_id == parentId)
                    .sort((a, b) => a.order - b.order)
                    .forEach(item => {
                        // Avoid using single quotes for data-item and escape problematic characters
                        function htmlEscape(str) {
                            return String(str)
                                .replace(/&/g, '&amp;')
                                .replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;')
                                .replace(/"/g, '&quot;')
                                .replace(/'/g, '&#39;');
                        }
                        const itemJson = htmlEscape(JSON.stringify(item));
                        const li = $(`
                            <li class="dd-item" data-id="${item.id}">
                                <div class="dd-handle ${item.is_active ? '' : 'menu-item-inactive'}">
                                    <span class="dd-title">${htmlEscape(item.slug)}</span>
                                    <span class="dd-actions">
                                        <button class="btn btn-sm btn-info edit-item" data-item="${itemJson}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${htmlEscape(item.title)}" data-url="${apiUrl}/${item.menu_id}/items/${item.id}" data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </span>
                                </div>
                            </li>
                        `);
                        
                        // Make sure buttons are not nested inside clickable elements
                        li.find('.dd-handle').on('click', function(e) {
                            // Only handle clicks that aren't on buttons
                            if (!$(e.target).closest('.dd-actions').length) {
                                $(this).parent().toggleClass('dd-collapsed dd-expanded')
                                    .find('> ol').toggle();
                            }
                        });
                        
                        const children = items.filter(i => i.parent_id == item.id);
                        if (children.length > 0) {
                            const ol = $('<ol class="dd-list"></ol>');
                            li.append(ol);
                            buildNestableList(items, ol, item.id);
                            li.addClass('dd-expanded');
                        }
                        
                        parentElement.append(li);
                    });
            }

            // Menu item form submit
            $('#menuItemForm').submit(async function(e) {
                e.preventDefault();

                // If CKEditor is present, update textarea value
                if (ckeditorInstance) {
                    $('#content').val(ckeditorInstance.getData());
                }
                
                const formData = new FormData(this);
                const menuId = $('#menu_id').val();
                const itemId = $('#item_id').val();
                const isActive = $('#is_active_item').prop('checked') ? 1 : 0;
                formData.set('is_active', isActive);
                
                try {
                    const response = await fetch(`${apiUrl}/${menuId}/items${itemId ? `/${itemId}` : ''}`, {
                        method: itemId ? 'PUT' : 'POST', 
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        toastr.success(result.message || 'Menu item saved successfully');
                        loadMenuItems(menuId);
                        this.reset();
                        $('#item_id').val('');
                        $('#cancel-edit').hide();
                        $('#item-action-text').text('Add Item');
                        if (ckeditorInstance) {
                            ckeditorInstance.setData('');
                        }
                    } else {
                        toastr.error(result.message || 'Failed to save menu item');
                    }
                } catch (error) {
                    toastr.error('An error occurred while saving menu item');
                }
            });

            // Save menu order
            $('#save-menu-order').click(async function() {
                const menuId = $('#menu_id').val();
                
                // Get the serialized order with proper order values
                const order = $('#menu-items-nestable').nestable('serialize');
                
                // Transform the data to include explicit order values
                const items = [];
                let orderCounter = 1;
                
                function processItems(itemList, parentId = null) {
                    itemList.forEach((item, index) => {
                        items.push({
                            id: item.id,
                            order: orderCounter++,
                            parent_id: parentId
                        });
                        
                        if (item.children && item.children.length > 0) {
                            processItems(item.children, item.id);
                        }
                    });
                }
                
                processItems(order);
                
                try {
                    const response = await fetch(`${apiUrl}/${menuId}/reorder-items`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ items: items })
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        toastr.success(result.message || 'Menu order saved successfully');
                    } else {
                        toastr.error(result.message || 'Failed to save menu order');
                    }
                } catch (error) {
                    toastr.error('An error occurred while saving menu order');
                }
            });

            // Edit button handler
            $(document).on('click', '.edit-item', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                const item = $(this).data('item');
                $('#menuItemForm')[0].reset();
                $('#item_id').remove();
                $('#menuItemForm').append(`<input type="hidden" id="item_id" name="id" value="${item.id}">`);
                
                $('#title').val(item.title);
                $('#item-slug').val(item.slug);
                $('#type').val(item.type).trigger('change');
                $('#url').val(item.url);
                $('#route').val(item.route);
                $('#target').val(item.target);
                $('#icon_class').val(item.icon_class);
                $('#color').val(item.color || '#000000');
                $('#parent_id').val(item.parent_id);
                $('#is_active_item').prop('checked', item.is_active);
                
                if (item.type === 'content') {
                    $('.content-fields').removeClass('d-none').show();
                    maybeInitCKEditor();
                    setTimeout(() => {
                        if (ckeditorInstance) {
                            ckeditorInstance.setData(item.content || '');
                        } else {
                            $('#content').val(item.content || '');
                        }
                    }, 200);
                } else {
                    $('.content-fields').addClass('d-none').hide();
                    if (ckeditorInstance) {
                        ckeditorInstance.setData('');
                    }
                }
                
                $('#cancel-edit').show();
                $('#item-action-text').text('Update Item');
                
                // Scroll to form
                $('.card-body').animate({
                    scrollTop: $('#menuItemForm').offset().top - 20
                }, 500);
            });

            // Delete button handler
            $(document).on('click', '.delete-item', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                const itemId = $(this).data('item-id');
                const menuId = $('#menu_id').val();
                
                if (confirm('Are you sure you want to delete this menu item?')) {
                    fetch(`${menuItemsUrl}/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            toastr.success(result.message || 'Menu item deleted successfully');
                            loadMenuItems(menuId);
                        } else {
                            toastr.error(result.message || 'Failed to delete menu item');
                        }
                    })
                    .catch(error => {
                        toastr.error('An error occurred while deleting menu item');
                    });
                }
            });

            // Cancel edit
            $('#cancel-edit').click(function() {
                $('#menuItemForm')[0].reset();
                $('#item_id').val('');
                $(this).hide();
                $('#item-action-text').text('Add Item');
                if (ckeditorInstance) {
                    ckeditorInstance.setData('');
                }
                $('#type').trigger('change');
            });

            // Expand all items
            $('#expand-all-items').click(function() {
                $('.dd-item').each(function() {
                    const $item = $(this);
                    const $list = $item.find('> ol.dd-list');
                    if ($list.length && !$item.hasClass('dd-expanded')) {
                        $item.addClass('dd-expanded');
                        $list.show();
                    }
                });
            });

            // Collapse all items
            $('#collapse-all-items').click(function() {
                $('.dd-item').each(function() {
                    const $item = $(this);
                    const $list = $item.find('> ol.dd-list');
                    if ($list.length && $item.hasClass('dd-expanded')) {
                        $item.removeClass('dd-expanded');
                        $list.hide();
                    }
                });
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchMenus({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by location
            $('#filter-location').change(function() {
                const location = $(this).val();
                fetchMenus({location: location});
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchMenus({is_active: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-location').val('');
                $('#filter-status').val('');
                fetchMenus();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchMenus();
            });
        });
    </script>
@endpush