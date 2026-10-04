@extends('layouts.app')

@section('title', $page = 'Sections')

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
                    <form id="sectionForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-layout-grid me-1 fs-3 text-primary"></i>
                                        Section Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="code" name="code" class="input-field form-control controlled" placeholder=" ">
                                    <label for="code" class="input-label">
                                        <i class="ti ti-code me-1 fs-3 text-primary"></i>
                                        Section Code <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="department_id" name="department_id" class="input-field form-select controlled">
                                        <option value="">Select Department</option>
                                        <!-- Departments will be loaded dynamically -->
                                    </select>
                                    <label for="department_id" class="input-label">
                                        <i class="ti ti-building me-1 fs-3 text-primary"></i>
                                        Department <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="head_id" name="head_id" class="input-field form-select">
                                        <option value="">Select Section Head</option>
                                        <!-- Staff will be loaded dynamically based on department -->
                                    </select>
                                    <label for="head_id" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Section Head
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
                                    <input class="form-check-input controlled" type="checkbox" id="is_active" name="is_active" checked>
                                    <label for="is_active" id="statusLabel">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="sectionForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="sectionForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search sections...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-department" class="form-select">
                                <option value="">All Departments</option>
                                <!-- Departments will be loaded dynamically -->
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

    <!-- Assign Head Modal -->
    <div class="modal fade" id="assignHeadModal" tabindex="-1" aria-labelledby="assignHeadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="assignHeadModalLabel">Assign Section Head</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="assignHeadForm">
                        <input type="hidden" id="section_id" name="section_id">
                        <input type="hidden" id="departmentId" name="department_id">
                        <div class="mb-3">
                            <label for="assign_staff_id" class="form-label">Select Staff Member <span class="text-red">*</span></label>
                            <select class="form-select" id="assign_staff_id" name="staff_id" required>
                                <option value="">Select Staff</option>
                                <!-- Staff will be loaded dynamically -->
                            </select>
                            <small class="text-muted">Only staff from the same department are listed</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveSectionHead">Assign Head</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Staff Modal -->
    <div class="modal fade" id="sectionStaffModal" tabindex="-1" aria-labelledby="sectionStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="sectionStaffModalLabel">Section Staff Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Employee No.</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="section-staff-body">
                        <!-- Staff will be loaded dynamically -->
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
            const apiUrl = '/web/v1/sections';
            const departmentsUrl = '/web/v1/departments';
            const staffUrl = '/web/v1/staff';

            // Status toggle
            const statusCheckbox = document.getElementById("is_active");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // Load initial data (departments and staff)
            async function loadInitialData() {
                try {
                    showLoader();
                    // Load departments
                    const departmentsResponse = await fetch(departmentsUrl);
                    const departmentsData = await departmentsResponse.json();
                    populateSelect('#department_id', departmentsData.data);
                    populateSelect('#filter-department', departmentsData.data);

                    // Load staff for section head selection
                    const staffResponse = await fetch(staffUrl);
                    const staffData = await staffResponse.json();
                    window.allStaff = staffData.data; // Store for department filtering
                    populateSelect('#head_id', window.allStaff);
                } catch (error) {
                    toastr.error("Failed to load initial data");
                }
            }

            // Department change handler for staff filtering
            document.getElementById('department_id').addEventListener('change', function() {
                const departmentId = this.value;
                const headSelect = document.getElementById('head_id');

                // Clear existing options except the first one
                while (headSelect.options.length > 1) {
                    headSelect.remove(1);
                }

                if (departmentId) {
                    // Filter staff based on selected department
                    window.allStaff.forEach(staff => {
                        if (staff.section.department_id == departmentId) {
                            const option = new Option(`${staff.first_name} ${staff.last_name}`, staff.id);
                            headSelect.add(option);
                        }
                    });
                }
            });

            // Fetch sections and populate the DataTable
            async function fetchSections(filters = {}) {
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
                            title: "Department",
                            data: "department",
                            render: function(data) {
                                return data ? data.name : 'N/A';
                            }
                        },
                        {
                            title: "Head",
                            data: "head",
                            render: function(data, type, row) {
                                if (data) {
                                    return `${data.first_name} ${data.last_name}`;
                                }
                                return '<button class="btn btn-sm btn-info assign-head-btn" data-section-id="'+row.id+'" data-department-id="'+row.department_id+'">Assign</button>';
                            }
                        },
                        {
                            title: "Staff",
                            data: 'staff',
                            render: function(data, type, row) {
                                const count = data ? data.length : 0;
                                return `<button class="btn btn-sm btn-primary view-staff-btn" data-section-id="${row.id}">View (${count || 0})</button>`;
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
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="sectionForm" data-url="${apiUrl}/${row.id}">
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
                        emptyTable: "No section records available"
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

            const sectionForm = document.getElementById("sectionForm");
            const submitButton = sectionForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            sectionForm.addEventListener('submit', async function (event) {
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

                const formData = new FormData(sectionForm);
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
                            await fetchSections();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Department updated successfully!" : "Department created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            sectionForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
                            sectionForm.classList.remove('was-validated');
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
                const sectionId = $(this).data('section-id');
                const departmentId = $(this).data('department-id');
                $('#section_id').val(sectionId);
                $('#departmentId').val(departmentId);
                $('#assignHeadForm')[0].reset();

                // Filter staff by department
                const staffSelect = document.getElementById('assign_staff_id');
                while (staffSelect.options.length > 1) {
                    staffSelect.remove(1);
                }

                if (departmentId) {
                    window.allStaff.forEach(staff => {
                        if (staff.section.department_id == departmentId) {
                            const option = new Option(`${staff.first_name} ${staff.last_name}`, staff.id);
                            staffSelect.add(option);
                        }
                    });
                }

                $('#assignHeadModal').modal('show');
            });

            // View staff button handler
            $(document).on('click', '.view-staff-btn', async function() {
                const sectionId = $(this).data('section-id');
                $('#section-staff-body').html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                $('#sectionStaffModal').modal('show');

                try {
                    const response = await fetch(`/web/v1/sections/${sectionId}/staff`);
                    const result = await response.json();

                    if (response.ok) {
                        let staffHtml = '';
                        if (result.data && result.data.length > 0) {
                            result.data.forEach(staff => {
                                staffHtml += `
                                    <tr>
                                        <td>${staff.first_name} ${staff.last_name}</td>
                                        <td>${staff.position}</td>
                                        <td>${staff.employee_number}</td>
                                        <td><span class="badge ${staff.status === 'active' ? 'bg-success' : 'bg-danger'}">${staff.status}</span></td>
                                    </tr>
                                `;
                            });
                        } else {
                            staffHtml = '<tr><td colspan="4" class="text-center">No staff assigned to this section</td></tr>';
                        }
                        $('#section-staff-body').html(staffHtml);
                    } else {
                        $('#section-staff-body').html('<tr><td colspan="4" class="text-center text-danger">Failed to load staff data</td></tr>');
                    }
                } catch (error) {
                    $('#section-staff-body').html('<tr><td colspan="4" class="text-center text-danger">Error loading staff data</td></tr>');
                }
            });

            // Save section head assignment
            $('#saveSectionHead').click(async function() {
                const form = document.getElementById('assignHeadForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Assigning...');

                    const response = await fetch('/web/v1/sections/' + data.section_id + '/assign-head', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({staff_id: data.staff_id})
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Section head assigned successfully');
                        $('#assignHeadModal').modal('hide');
                        fetchSections(); // Refresh the table
                    } else {
                        toastr.error(result.message || 'Failed to assign section head');
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
                await fetchSections({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by department
            $('#filter-department').change(function() {
                const departmentId = $(this).val();
                fetchSections({department_id: departmentId});
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchSections({is_active: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-department').val('');
                $('#filter-status').val('');
                fetchSections();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchSections();
            });
        });
    </script>
@endpush
