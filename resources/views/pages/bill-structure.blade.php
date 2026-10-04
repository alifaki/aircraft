@extends('layouts.app')

@section('title', $page = 'Chart of accounts')

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
                    <h5 class="mb-0 text-white">More {{$page}} Detail</h5>
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
                    <form id="paymentParameterForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="short_name" name="short_name" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="short_name" class="input-label">
                                        <i class="ti ti-abacus me-1 fs-3 text-primary"></i>
                                        Short Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="name" class="input-label">
                                        <i class="ti ti-text-wrap me-1 fs-3 text-primary"></i>
                                        Full Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="gfs_code" name="gfs_code" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="gfs_code" class="input-label">
                                        <i class="ti ti-code me-1 fs-3 text-primary"></i>
                                        GFS Code <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="group" name="group" class="input-field form-select controlled" required>
                                        <option value="">Select Group</option>
                                    </select>
                                    <label for="group" class="input-label">
                                        <i class="ti ti-category me-1 fs-3 text-primary"></i>
                                        Group <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="category" name="category" class="input-field form-select controlled" required>
                                        <option value="">Select Category</option>
                                    </select>
                                    <label for="category" class="input-label">
                                        <i class="ti ti-tag me-1 fs-3 text-primary"></i>
                                        Category <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div id="form-progress" class="text-muted small"></div>
                            <div>
                                <button type="reset" class="btn btn-danger me-2 btn-sm" data-for="paymentParameterForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="paymentParameterForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <span class="input-group-text"><i class="ti ti-category"></i></span>
                                <select id="filter-group" class="form-select">
                                    <option value="">All Groups</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-tag"></i></span>
                                <select id="filter-category" class="form-select">
                                    <option value="">All Categories</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button id="apply-filters" class="btn btn-primary btn-sm btn-h">
                                <span id="filter-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="paymentParametersTable">
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
            const tableElement = $('#paymentParametersTable');
            const apiUrl = '/web/v1/bill-structure';
            // Load initial data
            async function loadInitialData() {
                showLoader();
                try {
                    // Populate group dropdowns
                    populateSelect('#group', groups.map(group => ({name: group})));
                    populateSelect('#filter-group', groups.map(group => ({name: group})));

                    // Populate category dropdowns
                    populateSelect('#category', categories.map(category => ({name: category})));
                    populateSelect('#filter-category', categories.map(category => ({name: category})));
                } catch (error) {
                    console.error("Error loading initial data:", error);
                    showToast('error', 'Failed to load initial data', error.message);
                }
            }

            // Fetch payment parameters and populate the DataTable
            async function fetchPaymentParameters(filters = {}) {
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
                        throw new Error(res.message || 'Failed to load payment parameters');
                    }

                    initializeDataTable(res);
                } catch (error) {
                    console.error("Error fetching payment parameters:", error);
                    showToast('error', 'Failed to load payment parameters', error.message);
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
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="ti ti-info-circle fs-4"></i>
                                <p class="mt-2 mb-0">No payment parameters found</p>
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
                            title: "Short Name",
                            data: "short_name"
                        },
                        {
                            title: "Name",
                            data: "name"
                        },
                        {
                            title: "GFS Code",
                            data: "gfs_code"
                        },
                        {
                            title: "Group",
                            data: "group"
                        },
                        {
                            title: "Category",
                            data: "category"
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
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="paymentParameterForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
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
                        loadingRecords: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                        emptyTable: "No payment parameters available",
                        zeroRecords: "No matching payment parameters found"
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

            const paymentParameterForm = document.getElementById("paymentParameterForm");
            const submitButton = paymentParameterForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            paymentParameterForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing payment parameter data, please wait...";
                progressMessage.className = "text-primary small";

                // Clear previous error messages
                document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));

                const formData = new FormData(paymentParameterForm);
                const data = Object.fromEntries(formData);

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

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
                            await fetchPaymentParameters();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Payment parameter updated successfully!" : "Payment parameter created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            paymentParameterForm.reset();
                            paymentParameterForm.classList.remove('was-validated');
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
                    group: $('#filter-group').val(),
                    category: $('#filter-category').val()
                };
                fetchPaymentParameters(filters);
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

            // Initial load
            loadInitialData().then(() => {
                fetchPaymentParameters();
            })
        });
    </script>
@endpush
