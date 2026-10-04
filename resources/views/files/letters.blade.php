// resources/views/letters/index.blade.php
@extends('layouts.app')

@section('title', $page = 'Letters')

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
                    <form id="letterForm" enctype="multipart/form-data">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="reference_number" name="reference_number" class="input-field form-control controlled" placeholder=" ">
                                    <label for="reference_number" class="input-label">
                                        <i class="ti ti-id me-1 fs-3 text-primary"></i>
                                        Reference Number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="subject" name="subject" class="input-field form-control controlled" placeholder=" ">
                                    <label for="subject" class="input-label">
                                        <i class="ti ti-file-text me-1 fs-3 text-primary"></i>
                                        Subject <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="sender" name="sender" class="input-field form-control controlled" placeholder=" ">
                                    <label for="sender" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Sender <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="recipient" name="recipient" class="input-field form-control controlled" placeholder=" ">
                                    <label for="recipient" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Recipient <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="date" id="date_received" name="date_received" class="input-field form-control controlled" placeholder=" ">
                                    <label for="date_received" class="input-label">
                                        <i class="ti ti-calendar me-1 fs-3 text-primary"></i>
                                        Date Received <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="date" id="letter_date" name="letter_date" class="input-field form-control controlled" placeholder=" ">
                                    <label for="letter_date" class="input-label">
                                        <i class="ti ti-calendar me-1 fs-3 text-primary"></i>
                                        Letter Date <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="priority" name="priority" class="input-field form-select controlled">
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="low">Low</option>
                                    </select>
                                    <label for="priority" class="input-label">
                                        <i class="ti ti-flag me-1 fs-3 text-primary"></i>
                                        Priority <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="folder_id" name="folder_id" class="input-field form-select">
                                        <option value="">Select Folder</option>
                                        <!-- Folders will be loaded dynamically -->
                                    </select>
                                    <label for="folder_id" class="input-label">
                                        <i class="ti ti-folder me-1 fs-3 text-primary"></i>
                                        Folder
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
                                    <input class="form-check-input controlled" type="checkbox" id="is_confidential" name="is_confidential">
                                    <label for="is_confidential" id="confidentialLabel">Confidential: No</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="file" id="files" name="files[]" class="input-field form-control" multiple>
                                    <label for="files" class="input-label">
                                        <i class="ti ti-upload me-1 fs-3 text-primary"></i>
                                        Attachments
                                    </label>
                                </div>
                                <div id="file-preview" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="letterForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="letterForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search letters...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-priority" class="form-select">
                                <option value="">All Priorities</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processed">Processed</option>
                                <option value="archived">Archived</option>
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

    <!-- Transaction Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="transactionModalLabel">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="transactionForm">
                        <input type="hidden" id="letter_id" name="letter_id">
                        <div class="mb-3">
                            <label for="recipient_type" class="form-label">Recipient Type <span class="text-red">*</span></label>
                            <select class="form-select" id="recipient_type" name="recipient_type" required>
                                <option value="user">Select User</option>
                                <option value="manual">Manual Entry</option>
                            </select>
                        </div>

                        <div class="mb-3" id="user-recipient-field">
                            <label for="to_user_id" class="form-label">Select User <span class="text-red">*</span></label>
                            <select class="form-select" id="to_user_id" name="to_user_id">
                                <option value="">Select User</option>
                                <!-- Users will be loaded dynamically -->
                            </select>
                        </div>

                        <div class="mb-3 visually-hidden" id="manual-recipient-field">
                            <label for="recipient_name" class="form-label">Recipient Name <span class="text-red">*</span></label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name">
                        </div>
                        <div class="mb-3">
                            <label for="action" class="form-label">Action <span class="text-red">*</span></label>
                            <input type="text" class="form-control" id="action" name="action" required>
                        </div>
                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="letter_status" class="form-label">Update Letter Status</label>
                            <select class="form-select" id="letter_status" name="letter_status">
                                <option value="">No Change</option>
                                <option value="pending">Pending</option>
                                <option value="processed">Processed</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveTransaction">Add Transaction</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Files Modal -->
    <div class="modal fade" id="viewFilesModal" tabindex="-1" aria-labelledby="viewFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="viewFilesModalLabel">Letter Attachments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="letter-files-body">
                            <!-- Files will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Transactions Modal -->
    <div class="modal fade" id="viewTransactionsModal" tabindex="-1" aria-labelledby="viewTransactionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="viewTransactionsModalLabel">Letter Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Comments</th>
                            </tr>
                            </thead>
                            <tbody id="letter-transactions-body">
                            <!-- Transactions will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/letters';
            const foldersUrl = '/web/v1/folders';
            const usersUrl = '/web/v1/users';

            // Confidential toggle
            const confidentialCheckbox = document.getElementById("is_confidential");
            const confidentialLabel = document.getElementById("confidentialLabel");
            confidentialCheckbox.addEventListener("change", function (){
                confidentialLabel.textContent = `Confidential: ${this.checked ? "Yes" : "No"}`;
            });

            const letterForm = document.getElementById("letterForm");
            const submitButton = letterForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            letterForm.addEventListener('submit', async function (event) {
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

                const formData = new FormData(letterForm);
                // Handle checkbox status field
                formData.set("is_confidential", confidentialCheckbox.checked ? "1" : "0");

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;
                if (isUpdate) {
                    formData.append('_method', 'PUT');
                }
                try {
                    let response = await fetch(dataUrl || apiUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    let res = await response.json();
                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                        } else if (isUpdate) {
                            await fetchLetters();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Department updated successfully!" : "Department created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            letterForm.reset();
                            confidentialCheckbox.checked = true;
                            letterForm.classList.remove('was-validated');
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
                console.log(newRecord)
                if (!$.fn.DataTable.isDataTable(tableElement)) {
                    initializeDataTable({ data: [newRecord] });
                    return;
                }

                // const dataTable = tableElement.DataTable();
                // dataTable.row.add(newRecord).draw(false);
                //
                // // Update serial numbers
                // $(".dataTable tbody tr").each(function (index) {
                //     $(this).find("td:first").text(index + 1);
                // });
                return;
            }
            // File preview
            document.getElementById('files').addEventListener('change', function(e) {
                const filePreview = document.getElementById('file-preview');
                filePreview.innerHTML = '';

                if (this.files.length > 0) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'alert alert-info p-2';

                    const fileList = document.createElement('ul');
                    fileList.className = 'mb-0';

                    Array.from(this.files).forEach(file => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${file.name} (${formatFileSize(file.size)})`;
                        fileList.appendChild(listItem);
                    });

                    previewDiv.appendChild(fileList);
                    filePreview.appendChild(previewDiv);
                }
            });

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Load initial data (folders and users)
            async function loadInitialData() {
                try {
                    showLoader();
                    // Load folders
                    const foldersResponse = await fetch(foldersUrl);
                    const foldersData = await foldersResponse.json();
                    populateSelect('#folder_id', foldersData.data);

                    // Load users for transaction recipient selection
                    const usersResponse = await fetch(usersUrl);
                    const usersData = await usersResponse.json();
                    populateSelect('#to_user_id', usersData.data);
                } catch (error) {
                    toastr.error("Failed to load initial data");
                }
            }

            // Fetch letters and populate the DataTable
            async function fetchLetters(filters = {}) {
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
                        { title: "Ref No.", data: "reference_number" },
                        { title: "Subject", data: "subject" },
                        { title: "Sender", data: "sender" },
                        { title: "Recipient", data: "recipient" },
                        {
                            title: "Date Received",
                            data: "date_received",
                            render: function(data) {
                                return new Date(data).toLocaleDateString();
                            }
                        },
                        {
                            title: "Priority",
                            data: "priority",
                            render: function(data) {
                                const badgeClass = {
                                    'high': 'danger',
                                    'medium': 'warning',
                                    'low': 'success'
                                }[data] || 'secondary';
                                return `<span class="badge bg-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            title: "Status",
                            data: "status",
                            render: function(data) {
                                const badgeClass = {
                                    'pending': 'warning',
                                    'processed': 'success',
                                    'archived': 'secondary'
                                }[data] || 'info';
                                return `<span class="badge bg-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
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
                                        <button class="btn btn-info show-detailed-info" data-label="${row.subject}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="letterForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-primary view-files-btn" data-letter-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Attachments">
                                            <i class="ti ti-paperclip"></i>
                                        </button>
                                        <button class="btn btn-secondary view-transactions-btn" data-letter-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="View Transactions">
                                            <i class="ti ti-exchange"></i>
                                        </button>
                                        <button class="btn btn-success add-transaction-btn" data-letter-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Transaction">
                                            <i class="ti ti-send"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.subject}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No letter records available"
                    }
                });
            }

            // Add transaction button handler
            $(document).on('click', '.add-transaction-btn', function() {
                const letterId = $(this).data('letter-id');
                $('#letter_id').val(letterId);
                $('#transactionForm')[0].reset();
                $('#transactionModal').modal('show');
            });

            // View files button handler
            $(document).on('click', '.view-files-btn', async function() {
                const letterId = $(this).data('letter-id');
                $('#letter-files-body').html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#viewFilesModal').modal('show');

                try {
                    const response = await fetch(`${apiUrl}/${letterId}`);
                    const result = await response.json();

                    if (response.ok) {
                        let filesHtml = '';
                        if (result.data.files && result.data.files.length > 0) {
                            result.data.files.forEach(file => {
                                filesHtml += `
                                    <tr>
                                        <td>${file.file_name}</td>
                                        <td>${file.file_type}</td>
                                        <td>${formatFileSize(file.file_size)}</td>
                                        <td>
                                            <a href="/api/v1/letters/${letterId}/files/${file.id}/download" class="btn btn-sm btn-primary" download>
                                                <i class="ti ti-download"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger delete-file-btn" data-letter-id="${letterId}" data-file-id="${file.id}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            filesHtml = '<tr><td colspan="4" class="text-center">No attachments found</td></tr>';
                        }
                        $('#letter-files-body').html(filesHtml);
                    } else {
                        $('#letter-files-body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load attachments</td></tr>');
                    }
                } catch (error) {
                    $('#letter-files-body').html('<tr><td colspan="4" class="text-center text-danger">Error loading attachments</td></tr>');
                }
            });

            // View transactions button handler
            $(document).on('click', '.view-transactions-btn', async function() {
                const letterId = $(this).data('letter-id');
                $('#letter-transactions-body').html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#viewTransactionsModal').modal('show');

                try {
                    const response = await fetch(`${apiUrl}/${letterId}`);
                    const result = await response.json();
console.log(result)
                    if (response.ok) {
                        let transactionsHtml = '';
                        if (result.data.transactions && result.data.transactions.length > 0) {
                            result.data.transactions.forEach(transaction => {
                                transactionsHtml += `
                                    <tr>
                                        <td>${new Date(transaction.created_at).toLocaleString()}</td>
                                        <td>${transaction.from_user.staffs.first_name} ${transaction.from_user.staffs.last_name}</td>
                                        <td>${transaction.to_user ? transaction.to_user.staffs.first_name + ' ' + transaction.to_user.staffs.last_name : transaction.recipient_name}</td>
                                        <td>${transaction.action}</td>
                                        <td><span class="badge ${transaction.status === 'completed' ? 'bg-success' : 'bg-warning'}">${transaction.status}</span></td>
                                        <td>${transaction.comments || 'N/A'}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            transactionsHtml = '<tr><td colspan="6" class="text-center">No transactions found</td></tr>';
                        }
                        $('#letter-transactions-body').html(transactionsHtml);
                    } else {
                        $('#letter-transactions-body').html('<tr><td colspan="6" class="text-center text-danger">Failed to load transactions</td></tr>');
                    }
                } catch (error) {
                    $('#letter-transactions-body').html('<tr><td colspan="6" class="text-center text-danger">Error loading transactions</td></tr>');
                }
            });

            // Save transaction
            $('#saveTransaction').click(async function() {
                const form = document.getElementById('transactionForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Saving...');

                    const response = await fetch(`${apiUrl}/${data.letter_id}/transactions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Transaction added successfully');
                        $('#transactionModal').modal('hide');
                        fetchLetters(); // Refresh the table
                    } else {
                        toastr.error(result.message || 'Failed to add transaction');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Add Transaction');
                }
            });

            // Delete file
            $(document).on('click', '.delete-file-btn', async function() {
                const letterId = $(this).data('letter-id');
                const fileId = $(this).data('file-id');

                if (confirm('Are you sure you want to delete this file?')) {
                    try {
                        $(this).html('<span class="ti ti-loader fa fa-spin"></span>');

                        const response = await fetch(`${apiUrl}/${letterId}/files/${fileId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            toastr.success(result.message || 'File deleted successfully');
                            // Refresh the files list
                            $(this).trigger('click');
                        } else {
                            toastr.error(result.message || 'Failed to delete file');
                        }
                    } catch (error) {
                        toastr.error(error.message || 'An error occurred');
                    }
                }
            });
// Add this to your scripts section
            document.getElementById('recipient_type').addEventListener('change', function() {
                const recipientType = this.value;
                const userField = document.getElementById('user-recipient-field');
                const manualField = document.getElementById('manual-recipient-field');

                if (recipientType === 'user') {
                    userField.classList.remove('visually-hidden');
                    manualField.classList.add('visually-hidden');
                    document.getElementById('to_user_id').required = true;
                    document.getElementById('recipient_name').required = false;
                } else {
                    userField.classList.add('visually-hidden');
                    manualField.classList.remove('visually-hidden');
                    document.getElementById('to_user_id').required = false;
                    document.getElementById('recipient_name').required = true;
                }
            });
            // Search functionality
            $('#search-btn').click(async function () {
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchLetters({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by priority
            $('#filter-priority').change(function() {
                const priority = $(this).val();
                fetchLetters({priority: priority});
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchLetters({status: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-priority').val('');
                $('#filter-status').val('');
                fetchLetters();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchLetters();
            });
        });
    </script>
@endpush
