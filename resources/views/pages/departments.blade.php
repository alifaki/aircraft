@extends('layouts.app')

@section('title', $page = 'Departments')

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
                    <form id="departmentForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-building me-1 fs-3 text-primary"></i>
                                        Department Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="code" name="code" class="input-field form-control controlled" placeholder=" ">
                                    <label for="code" class="input-label">
                                        <i class="ti ti-code me-1 fs-3 text-primary"></i>
                                        Department Code <span class="text-red"> *</span>
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
                                        Description <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="head_ofd_id" name="head_ofd_id" class="input-field form-select">
                                        <option value="">Select Department Head</option>
                                        <!-- Staff will be loaded dynamically -->
                                    </select>
                                    <label for="head_ofd_id" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Department Head
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
                                    <button type="button" class="btn btn-sm btn-danger" data-for="departmentForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="departmentForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search by name or code...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-tag"></i></span>
                                <select id="filter-status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="reset-filters" class="btn btn-secondary btn-sm btn-h">Reset Filters</button>
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

    <!-- Assign Head Modal -->
    <div class="modal fade" id="assignHeadModal" tabindex="-1" aria-labelledby="assignHeadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="assignHeadModalLabel">Assign Department Head</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignHeadForm">
                        <input type="hidden" id="department_id" name="department_id">
                        <div class="mb-3">
                            <label for="assign_staff_id" class="form-label">Select Staff Member <span class="text-red">*</span></label>
                            <select class="form-select" id="assign_staff_id" name="staff_id" required>
                                <option value="">Select Staff</option>
                                <!-- Staff will be loaded dynamically -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveDepartmentHead">Assign Head</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections Modal -->
    <div class="modal fade" id="sectionsModal" tabindex="-1" aria-labelledby="sectionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="sectionsModalLabel">Department Sections</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Head</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="sections-list-body">
                        <!-- Sections will be loaded dynamically -->
                        </tbody>
                    </table>
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
            const apiUrl = '/web/v1/departments';
            const staffUrl = '/web/v1/staff';

            // Status toggle
            const statusCheckbox = document.getElementById("is_active");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // Load initial data (staff for department head selection)
            async function loadInitialData() {
                try {
                    showLoader();
                    // Load staff for department head selection
                    const staffResponse = await fetch(staffUrl);
                    const staffData = await staffResponse.json();
                    populateSelect('#head_ofd_id', staffData.data);
                    populateSelect('#assign_staff_id', staffData.data);

                } catch (error) {
                    toastr.error("Failed to load initial data");
                }
            }
            // Fetch departments and populate the DataTable
            async function fetchDepartments(filters = {}) {
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
                        { title: "Code", data: "code" },
                        {
                            title: "Head",
                            data: "head",
                            render: function(data, type, row) {
                                if (data) {
                                    return `${data.first_name} ${data.last_name}`;
                                }
                                return '<button class="btn btn-sm btn-info assign-head-btn" data-department-id="'+row.id+'">Assign</button>';
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
                            title: "Sections",
                            data: "sections",
                            render: function(data, type, row) {
                                const count = data ? data.length : 0;
                                return `<button href="#" class="btn btn-sm btn-info view-sections-btn text-white" data-department-id="${row.id}" style="color: inherit;">View (${count})</button>`;
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
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="departmentForm" data-url="${apiUrl}/${row.id}">
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
                        loadingRecords: "Loading...",
                        emptyTable: "No department records available"
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

            const departmentForm = document.getElementById("departmentForm");
            const submitButton = departmentForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            departmentForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing department data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(departmentForm);
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
                            await fetchDepartments();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Department updated successfully!" : "Department created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            departmentForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
                            departmentForm.classList.remove('was-validated');
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

            // Assign head button handler
            $(document).on('click', '.assign-head-btn', function() {
                const departmentId = $(this).data('department-id');
                $('#department_id').val(departmentId);
                $('#assignHeadForm')[0].reset();
                $('#assignHeadModal').modal('show');
            });

            // Save department head assignment
            $('#saveDepartmentHead').click(async function() {
                const form = document.getElementById('assignHeadForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Assigning...');

                    const response = await fetch('/web/v1/departments/' + data.department_id + '/assign-head', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({staff_id: data.staff_id})
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Department head assigned successfully');
                        $('#assignHeadModal').modal('hide');
                        fetchDepartments(); // Refresh the table
                    } else {
                        toastr.error(result.message || 'Failed to assign department head');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Assign Head');
                }
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchDepartments({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchDepartments({status: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-status').val('');
                fetchDepartments();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchDepartments();
            });
        });

        // View sections button handler
        $(document).on('click', '.view-sections-btn', async function(e) {
            e.preventDefault();
            const departmentId = $(this).attr('data-department-id');
            $('#sections-list-body').html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            $('#sectionsModal').modal('show');

            try {
                const response = await fetch(`/web/v1/sections/${departmentId}/departments`);
                const result = await response.json();

                if (response.ok) {
                    let sectionsHtml = '';
                    if (result.data && result.data.length > 0) {
                        result.data.forEach(section => {
                            sectionsHtml += `
                        <tr>
                            <td>${section.name}</td>
                            <td>${section.code}</td>
                            <td>${section.head ? `${section.head.first_name} ${section.head.last_name}` : 'N/A'}</td>
                            <td><span class="badge ${section.is_active ? 'bg-success' : 'bg-danger'}">${section.is_active ? 'Active' : 'Inactive'}</span></td>
                        </tr>
                    `;
                        });
                    } else {
                        sectionsHtml = '<tr><td colspan="4" class="text-center">No sections found for this department</td></tr>';
                    }
                    $('#sections-list-body').html(sectionsHtml);
                } else {
                    $('#sections-list-body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load sections</td></tr>');
                }
            } catch (error) {
                $('#sections-list-body').html('<tr><td colspan="4" class="text-center text-danger">Error loading sections</td></tr>');
            }
        });
    </script>
@endpush
