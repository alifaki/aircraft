@extends('layouts.app')

@section('title', $page = 'Branches')

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

    <!-- More Details Section -->
    <div class="row visually-hidden" id="more-details">
        <div class="col-xxl-12 col-md-12">
            <div class="card border-bottom border-info shadow-sm">
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

    <!-- Main Content Section -->
    <div class="row" id="detailed-data-info">
        <!-- Form Section -->
        <div class="col-xxl-5 col-md-5 visually-hidden form-input">
            <div class="card border-primary shadow-sm">
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
                    <form id="branchForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="company_id" name="company_id" class="input-field form-select controlled">
                                        <option value="">Select Company</option>
                                        <!-- Companies will be loaded dynamically -->
                                    </select>
                                    <label for="company_id" class="input-label">
                                        <i class="ti ti-building me-1 fs-3 text-primary"></i>
                                        Company <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="branch_name" name="branch_name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="branch_name" class="input-label">
                                        <i class="ti ti-home me-1 fs-3 text-primary"></i>
                                        Branch Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="branch_code" name="branch_code" class="input-field form-control controlled" placeholder=" ">
                                    <label for="branch_code" class="input-label">
                                        <i class="ti ti-code me-1 fs-3 text-primary"></i>
                                        Branch Code <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="location" name="location" class="input-field form-control controlled" placeholder=" ">
                                    <label for="location" class="input-label">
                                        <i class="ti ti-map-pin me-1 fs-3 text-primary"></i>
                                        Location <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="city" name="city" class="input-field form-control controlled" placeholder=" ">
                                    <label for="city" class="input-label">
                                        <i class="ti ti-map me-1 fs-3 text-primary"></i>
                                        City <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="country" name="country" class="input-field form-control controlled" placeholder=" ">
                                    <label for="country" class="input-label">
                                        <i class="ti ti-world me-1 fs-3 text-primary"></i>
                                        Country <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="manager_name" name="manager_name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="manager_name" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Manager Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="manager_phone" name="manager_phone" class="input-field form-control controlled" placeholder=" ">
                                    <label for="manager_phone" class="input-label">
                                        <i class="ti ti-phone me-1 fs-3 text-primary"></i>
                                        Manager Phone <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="email" id="manager_email" name="manager_email" class="input-field form-control controlled" placeholder=" ">
                                    <label for="manager_email" class="input-label">
                                        <i class="ti ti-mail me-1 fs-3 text-primary"></i>
                                        Manager Email <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="postal_address" name="postal_address" class="input-field form-control" placeholder=" ">
                                    <label for="postal_address" class="input-label">
                                        <i class="ti ti-map-2 me-1 fs-3 text-primary"></i>
                                        Postal Address
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="url" id="map_link" name="map_link" class="input-field form-control" placeholder=" ">
                                    <label for="map_link" class="input-label">
                                        <i class="ti ti-map-search me-1 fs-3 text-primary"></i>
                                        Map Link
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
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
                                    <input class="form-check-input controlled" type="checkbox" id="status" name="status" checked>
                                    <label for="status" id="statusLabel">Active</label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div id="form-progress" class="text-muted small"></div>
                            <div>
                                <button type="reset" class="btn btn-danger me-2 btn-sm" data-for="branchForm" id="reset-btn">
                                    <i class="ti ti-x me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm" data-url="" id="create-btn">
                                    <i class="ti ti-device-floppy me-1"></i> <span class="btn-label">Create</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center  py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="branchForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                    <!-- Filter Section -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-building"></i></span>
                                <select id="filter-company" class="form-select">
                                    <option value="">All Companies</option>
                                    <!-- Companies will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-toggle-left"></i></span>
                                <select id="filter-status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button id="apply-filters" class="btn btn-primary btn-sm btn-h">
                                <span id="filter-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Filters
                            </button>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable" id="branchesTable">
                            <tbody>
                            <tr><td id="table-loader"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('#branchesTable');
            const apiUrl = '/web/v1/branches';
            const companiesUrl = '/web/v1/companies';

            // Status toggle
            const statusCheckbox = document.getElementById("status");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // Load initial data (companies)
            async function loadInitialData() {
                try {
                    showLoader();
                    const companiesResponse = await fetch(companiesUrl);

                    if (!companiesResponse.ok) {
                        throw new Error(`HTTP error! Status: ${companiesResponse.status}`);
                    }

                    const companiesData = await companiesResponse.json();

                    if (!companiesData.success) {
                        throw new Error(companiesData.message || 'Failed to load companies');
                    }

                    populateSelect('#company_id', companiesData.data);
                    populateSelect('#filter-company', companiesData.data);

                } catch (error) {
                    console.error("Error loading initial data:", error);
                    showToast('error', 'Failed to load initial data', error.message);
                }
            }

            // Fetch branches and populate the DataTable
            async function fetchBranches(filters = {}) {
                try {
                    showLoader();
                    toggleFilterSpinner(true);

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

                    if (!res.success) {
                        throw new Error(res.message || 'Failed to load branch data');
                    }

                    initializeDataTable(res);
                } catch (error) {
                    console.error("Error fetching branch records:", error);
                    showToast('error', 'Failed to load branch data', error.message);
                } finally {
                    toggleFilterSpinner(false);
                }
            }

            function initializeDataTable(res) {
                const data = res.data;

                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable(tableElement)) {
                    tableElement.DataTable().destroy();
                }

                // Clear the table
                tableElement.find('thead').empty();
                tableElement.find('tbody').empty();

                // Check if we have data
                if (data.length === 0) {
                    tableElement.find('tbody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="ti ti-info-circle fs-4"></i>
                                <p class="mt-2 mb-0">No branch records found</p>
                            </td>
                        </tr>
                    `);
                    return;
                }

                // Initialize DataTable
                const dataTable = tableElement.DataTable({
                    data: data,
                    columns: [
                        {
                            title: "#",
                            data: null,
                            className: "text-center",
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            title: "Branch Info",
                            data: "branch_name"
                        },
                        {
                            title: "Company",
                            data: "company",
                            render: function(data) {
                                return data ? `
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-building me-2"></i>
                                        ${data.company_name}
                                    </div>
                                ` : 'N/A';
                            }
                        },
                        {
                            title: "Location",
                            data: "location",
                        },
                        {
                            title: "Manager",
                            data: "manager_name"
                        },
                        {
                            title: "Status",
                            data: "status",
                            className: "text-center",
                            render: function(data) {
                                const badgeClass = data === 'active' ? 'badge bg-success' : 'badge bg-danger';
                                return `<span class="${badgeClass}">${data.toUpperCase()}</span>`;
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
                                        <button class="btn btn-info show-detailed-info" data-label="${row.branch_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="branchForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.branch_name}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                        emptyTable: "No branch records available",
                        zeroRecords: "No matching branches found"
                    },
                    initComplete: function() {
                        // Add any initialization complete logic if needed
                    },
                    drawCallback: function() {
                        // Add any draw callback logic if needed
                    }
                });
            }

            function addNewRecordToTable(newRecord) {
                if (!$.fn.DataTable.isDataTable(tableElement)) {
                    initializeDataTable({ success: true, data: [newRecord] });
                    return;
                }

                const dataTable = tableElement.DataTable();
                dataTable.row.add(newRecord).draw(false);
            }

            const branchForm = document.getElementById("branchForm");
            const submitButton = branchForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            branchForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                event.stopPropagation();

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
                btnIcon.classList.remove("ti-device-floppy");
                btnIcon.classList.add("ti-loader", "fa-spin");
                progressMessage.textContent = "Processing branch data, please wait...";
                progressMessage.className = "text-primary small";

                // Clear previous error messages
                document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));

                const formData = new FormData(branchForm);
                // Handle checkbox status field
                formData.set("status", statusCheckbox.checked ? "active" : "inactive");

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

                const data = Object.fromEntries(formData);

                try {
                    let response = await fetch(dataUrl || apiUrl, {
                        method: isUpdate ? "PUT" : "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(data)
                    });

                    let res = await response.json();

                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                        } else if (isUpdate) {
                            await fetchBranches();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Branch updated successfully!" : "Branch created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            branchForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
                            branchForm.classList.remove('was-validated');
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
            // Apply filters
            $('#apply-filters').click(function() {
                const filters = {
                    company_id: $('#filter-company').val(),
                    status: $('#filter-status').val()
                };
                fetchBranches(filters);
            });

            // Helper functions
            function showLoader() {
                const loader = document.querySelector('#table-loader');
                if (loader) loader.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            }

            function toggleFilterSpinner(show) {
                const spinner = $('#filter-spinner');
                if (show) {
                    spinner.removeClass('d-none');
                    $('#apply-filters').prop('disabled', true);
                } else {
                    spinner.addClass('d-none');
                    $('#apply-filters').prop('disabled', false);
                }
            }

            // Phone number masking
            $.mask.definitions['~']='[+-]';
            $('#manager_phone').mask('255999999999');

            // Initial load
            loadInitialData().then(() => {
                fetchBranches();
            });
        });
    </script>
@endpush
