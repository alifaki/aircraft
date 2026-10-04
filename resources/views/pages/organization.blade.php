@extends('layouts.app')

@section('title', $page = 'Organization')

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
                    <form id="companyForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="app_id" name="app_id" class="input-field form-control controlled" placeholder=" ">
                                    <label for="app_id" class="input-label">
                                        <i class="ti ti-number me-1 fs-3 text-primary"></i>
                                        App Id  <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="company_name" name="company_name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="company_name" class="input-label">
                                        <i class="ti ti-building me-1 fs-3 text-primary"></i>
                                        Company Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="email" name="email" class="input-field form-control controlled" placeholder=" ">
                                    <label for="email" class="input-label">
                                        <i class="ti ti-mail me-1 fs-3 text-primary"></i>
                                        Email Address <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="phone" name="phone" class="input-field form-control controlled" placeholder=" ">
                                    <label for="phone" class="input-label">
                                        <i class="ti ti-phone me-1 fs-3 text-primary"></i>
                                        Phone number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="country" name="country" class="input-field form-control controlled" placeholder=" ">
                                    <label for="country" class="input-label">
                                        <i class="ti ti-map-pin me-1 fs-3 text-primary"></i>
                                        Country <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="city" name="city" class="input-field form-control controlled" placeholder=" ">
                                    <label for="city" class="input-label">
                                        <i class="ti ti-map me-1 fs-3 text-primary"></i>
                                        City <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="location" name="location" class="input-field form-control controlled" placeholder=" ">
                                    <label for="location" class="input-label">
                                        <i class="ti ti-map-2 me-1 fs-3 text-primary"></i>
                                        Location <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="map_link" name="map_link" class="input-field form-control" placeholder=" ">
                                    <label for="map_link" class="input-label">
                                        <i class="ti ti-map-search me-1 fs-3 text-primary"></i>
                                        Map link
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="postal_address" name="postal_address" class="input-field form-control" placeholder=" ">
                                    <label for="postal_address" class="input-label">
                                        <i class="ti ti-map me-1 fs-3 text-primary"></i>
                                        Postal Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="website" name="website" class="input-field form-control" placeholder=" ">
                                    <label for="website" class="input-label">
                                        <i class="ti ti-globe me-1 fs-3 text-primary"></i>
                                        Website
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="facebook" name="facebook" class="input-field form-control" placeholder=" ">
                                    <label for="facebook" class="input-label">
                                        <i class="ti ti-brand-facebook me-1 fs-3 text-primary"></i>
                                        Facebook Page
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="twitter" name="twitter" class="input-field form-control" placeholder=" ">
                                    <label for="twitter" class="input-label">
                                        <i class="ti ti-brand-twitter me-1 fs-3 text-primary"></i>
                                        Twitter
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="instagram" name="instagram" class="input-field form-control" placeholder=" ">
                                    <label for="instagram" class="input-label">
                                        <i class="ti ti-brand-instagram me-1 fs-3 text-primary"></i>
                                        Instagram
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="text" id="youtube" name="youtube" class="input-field form-control" placeholder=" ">
                                    <label for="youtube" class="input-label">
                                        <i class="ti ti-brand-youtube me-1 fs-3 text-primary"></i>
                                        Youtube Page
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-container">
                                    <input type="number" min="1000" step="0.01" id="donation" name="donation" class="input-field form-control controlled" placeholder=" ">
                                    <label for="donation" class="input-label">
                                        <i class="ti ti-zoom-money me-1 fs-3 text-primary"></i>
                                        Donation <span class="text-red">*</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" value="active" name="status" type="checkbox" id="status" checked="">
                                    <label for="status" id="statusLabel">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="description" name="description" class="input-field form-control controlled" placeholder=" "></textarea>
                                    <label for="description" class="input-label">
                                        <i class="ti ti-file-text me-1 fs-3 text-primary"></i>
                                        Description <span class="text-red">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="companyForm" id="reset-btn">
                                        <i class="ti ti-x"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary ms-1"  data-url="" id="create-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="companyForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                    <div class="row g-3 mb-4">
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
                        <table class="table table-bordered table-striped dataTable">
                        <tr><td id="table-loader"></td></tr>
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
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/companies';  // Your API endpoint

            const statusCheckbox = document.getElementById("status");
            const statusLabel = document.getElementById("statusLabel");

            // Listen for checkbox change event
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

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

            // Update fetchCompanies to support filters
            async function fetchCompanies(filters = {}) {
                try {
                    showLoader();
                    toggleFilterSpinner(true);

                    const queryParams = new URLSearchParams();
                    for (const key in filters) {
                        if (filters[key]) {
                            queryParams.append(key, filters[key]);
                        }
                    }

                    const url = queryParams.toString() ? `${apiUrl}?${queryParams}` : apiUrl;
                    const response = await fetch(url, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const res = await response.json();
                    initializeDataTable(res);
                } catch (error) {
                    console.error("Error:", error);
                    showToast('error', 'Error', error.message);
                } finally {
                    toggleFilterSpinner(false);
                }
            }

// Add filter event listener
            $('#apply-filters').click(function() {
                const filters = {
                    status: $('#filter-status').val()
                };
                fetchCompanies(filters);
            });

            function initializeDataTable(res) {
                const data = res.data;

                tableElement.DataTable({
                    destroy: true, // Destroy existing table instance before reinitializing
                    data: data,
                    columns: [
                        {
                            title: "SN",
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { title: "Company Name", data: "company_name" },
                        { title: "Email", data: "email" },
                        { title: "Phone", data: "phone" },
                        { title: "Location", data: "location" },
                        { title: "City", data: "city" },
                        { title: "Country", data: "country" },
                        { title: "Status", data: "status" },
                        {
                            title: "Actions",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.company_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="comanyForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.company_name}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No company records available"
                    }
                });
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

            const companyForm = document.getElementById("companyForm");
            const submitButton = companyForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            companyForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing company data, please wait...";
                progressMessage.className = "text-primary small";

                // Clear previous error messages
                document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));

                const formData = new FormData(companyForm);
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
                            await fetchCompanies();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Company updated successfully!" : "Company created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            companyForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
                            companyForm.classList.remove('was-validated');
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
            fetchCompanies();
        });
        $.mask.definitions['~']='[+-]';
        $('#app_id').mask('SAMIS-9-9-9-9');
        $('#phone').mask('255999999999');
    </script>
@endpush
