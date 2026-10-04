@extends('layouts.app')

@section('title', $page = 'IP Manager')

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
    <div class="row" id="detailed-data-info">
        <div class="col-xxl-5 col-md-5 visually-hidden form-input">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white"><span class="btn-label">Create</span> {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-danger close-form" data-bs-toggle="tooltip" data-bs-placement="top" title="Close">
                            <span class="ti ti-x"></span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="ipForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="ip_address" name="ip_address" class="input-field form-control controlled" placeholder=" ">
                                    <label for="ip_address" class="input-label">
                                        <i class="ti ti-network me-1 fs-3 text-primary"></i>
                                        IP Address <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="description" name="description" class="input-field form-control controlled" placeholder=" "></textarea>
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
                                    <select id="type" name="type" class="input-field form-select controlled">
                                        <option value="whitelist">Whitelist</option>
                                        <option value="blacklist">Blacklist</option>
                                    </select>
                                    <label for="type" class="input-label">
                                        <i class="ti ti-list me-1 fs-3 text-primary"></i>
                                        Type <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" type="checkbox" id="is_active" name="is_active" checked>
                                    <label for="is_active" id="statusLabel">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="ipForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="ipForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search by IP or description...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-type" class="form-select">
                                <option value="">All Types</option>
                                <option value="whitelist">Whitelist</option>
                                <option value="blacklist">Blacklist</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>IP Address</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><td id="table-loader" colspan="7"></td></tr>
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
            const apiUrl = '/web/v1/ip-restrictions';
            const tableElement = $('.dataTable');
            const statusCheckbox = document.getElementById("is_active");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // Fetch and initialize data
            async function fetchIpRestrictions(filters = {}) {
                try {
                    showLoader();

                    const queryParams = new URLSearchParams(filters).toString();
                    const url = queryParams ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url);
                    const data = await response.json();

                    initializeDataTable(data.data);
                } catch (error) {
                    showToast('error', error.message);
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
                        { data: null, render: (data, type, row, meta) => meta.row + 1 },
                        { data: 'ip_address' },
                        { data: 'description' },
                        {
                            data: 'type',
                            render: (type) => `<span class="badge ${type === 'whitelist' ? 'bg-success' : 'bg-danger'}">${type}</span>`
                        },
                        {
                            data: 'is_active',
                            render: (active) => `<span class="badge ${active ? 'bg-success' : 'bg-danger'}">${active ? 'Active' : 'Inactive'}</span>`
                        },
                        {
                            data: 'creator',
                            render: (creator) => creator ? creator.staff.first_name+' '+creator.staff.last_name : 'System'
                        },
                        {
                            data: null,
                            render: (data) => `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-warning update-record"
                                    data-browse='${JSON.stringify(data)}'
                                    data-for="ipForm"
                                    data-url="${apiUrl}/${data.id}">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button class="btn btn-danger confirm-delete"
                                    data-label="${data.ip_address}"
                                    data-url="${apiUrl}/${data.id}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    `
                        }
                    ]
                });
            }

            const ipForm = document.getElementById("ipForm");
            const submitButton = ipForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

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

            // Form Submit Event Listener
            ipForm.addEventListener('submit', async function (event) {
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

                const formData = new FormData(ipForm);
                formData.set("is_active", statusCheckbox.checked ? 1 : 0);
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
                            await fetchIpRestrictions();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Payment parameter updated successfully!" : "Payment parameter created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            ipForm.reset();
                            ipForm.classList.remove('was-validated');
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

            // Search functionality
            $('#search-btn').click(async function () {
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchIpRestrictions({ search: $('#search-input').val() });
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            $('#filter-type').change(() => {
                fetchIpRestrictions({ type: $('#filter-type').val() });
            });

            $('#filter-status').change(() => {
                fetchIpRestrictions({ status: $('#filter-status').val() });
            });

            // Initial load
            fetchIpRestrictions();
        });
    </script>
@endpush
