@extends('layouts.app')

@section('title', $page = 'Staff')

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
                    <form id="staffForm" enctype="multipart/form-data">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-2">
                                <div class="input-container">
                                    <select id="initial" name="initial" class="input-field form-control controlled">
                                        <option value="">Select Initial</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Mrs.">Mrs.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Prof.">Prof.</option>
                                        <option value="Eng.">Eng.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="Rev.">Rev.</option>
                                        <option value="Fr.">Fr.</option>
                                        <option value="Hon.">Hon.</option>
                                    </select>
                                    <label for="initial" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Initial
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-container">
                                    <input type="text" id="first_name" name="first_name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="first_name" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        First Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-container">
                                    <input type="text" id="last_name" name="last_name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="last_name" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Last Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="employee_number" name="employee_number" class="input-field form-control controlled" placeholder=" ">
                                    <label for="employee_number" class="input-label">
                                        <i class="ti ti-id me-1 fs-3 text-primary"></i>
                                        Employee Number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="phone" name="phone" class="input-field form-control controlled" placeholder=" ">
                                    <label for="phone" class="input-label">
                                        <i class="ti ti-phone me-1 fs-3 text-primary"></i>
                                        Phone Number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="email" id="email" name="email" class="input-field form-control" placeholder=" ">
                                    <label for="email" class="input-label">
                                        <i class="ti ti-mail me-1 fs-3 text-primary"></i>
                                        Email Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="position" name="position" class="input-field form-control controlled">
                                        <option value="">Select Position</option>
                                        @foreach(config('common.positions') as $position)
                                            <option value="{{$position}}">{{ucfirst(str_replace('_', ' ', $position))}}</option>
                                        @endforeach
                                    </select>
                                    <label for="position" class="input-label">
                                        <i class="ti ti-briefcase me-1 fs-3 text-primary"></i>
                                        Position <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- New row for photo_path and bio -->
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="file" id="photo_path" name="photo_path" class="input-field form-control" accept="image/*">
                                    <label for="photo_path" class="input-label">
                                        <i class="ti ti-camera me-1 fs-3 text-primary"></i>
                                        Photo
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <textarea id="bio" name="bio" class="input-field form-control" placeholder=" " rows="3"></textarea>
                                    <label for="bio" class="input-label">
                                        <i class="ti ti-info-circle me-1 fs-3 text-primary"></i>
                                        Bio
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- End new row -->
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="branch_id" name="branch_id" class="branch input-field form-select controlled">
                                        <option value="">Select Branch</option>
                                        <!-- Branches will be loaded dynamically -->
                                    </select>
                                    <label for="branch_id" class="input-label">
                                        <i class="ti ti-building me-1 fs-3 text-primary"></i>
                                        Branch <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="section_id" name="section_id" class="section input-field form-select controlled">
                                        <option value="">Select Section</option>
                                        <!-- Sections will be loaded dynamically based on branch -->
                                    </select>
                                    <label for="section_id" class="input-label">
                                        <i class="ti ti-layout-grid me-1 fs-3 text-primary"></i>
                                        Section <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" value="active" name="status" type="checkbox" id="status" checked>
                                    <label for="status" id="statusLabel">Active</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="create_user_account" name="create_user_account">
                                    <label for="create_user_account">Create User Account</label>
                                </div>
                            </div>
                        </div>

                        <!-- User Account Fields (Hidden by default) -->
                        <div id="userAccountFields" class="visually-hidden">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="input-container">
                                        <input type="text" id="username" name="username" class="input-field form-control" placeholder=" ">
                                        <label for="username" class="input-label">
                                            <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                            Username
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-container">
                                        <input type="password" id="password" name="password" class="input-field form-control" placeholder=" ">
                                        <label for="password" class="input-label">
                                            <i class="ti ti-lock me-1 fs-3 text-primary"></i>
                                            Password
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="input-container">
                                        <select id="role_id" name="role_id" class="input-field form-select">
                                            <option value="">Select Role</option>
                                            <!-- Roles will be loaded dynamically -->
                                        </select>
                                        <label for="role_id" class="input-label">
                                            <i class="ti ti-shield me-1 fs-3 text-primary"></i>
                                            Role
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="staffForm" id="reset-btn">
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
                        <!-- Download and Upload Buttons -->
                        <button class="btn btn-sm btn-success download-template me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Download Template">
                            <i class="ti ti-download"></i>
                        </button>
                        <button class="btn btn-sm btn-warning upload-staff me-1" data-bs-toggle="modal" data-bs-target="#uploadStaffModal" title="Upload Staff">
                            <i class="ti ti-upload"></i>
                        </button>
                        <button class="btn btn-sm btn-light send-notification" data-browes="" data-bs-toggle="modal" data-bs-target="#send-notification-modal" title="Send notification">
                            <i class="ti ti-send"></i>
                        </button>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="staffForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                        <div class="col-md-3">
                            <select id="filter-branch" class="form-select">
                                <option value="">All Branches</option>
                                <!-- Branches will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-section" class="form-select">
                                <option value="">All Sections</option>
                                <!-- Sections will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="apply-filters" class="btn btn-primary btn-sm btn-h">Filters</button>
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

    <!-- Upload Staff Modal -->
    <div class="modal fade" id="uploadStaffModal" tabindex="-1" aria-labelledby="uploadStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="uploadStaffModalLabel">Upload Staff Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadStaffForm" enctype="multipart/form-data">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> 
                                Download the template first, fill in the data, then upload the Excel file.
                            </small>
                        </div>
                        <div class="mb-3">
                            <div class="input-container">
                                <input type="file" id="staff_file" name="staff_file" class="input-field form-control" accept=".xlsx,.xls,.csv" required>
                                <label for="staff_file" class="input-label">
                                    <i class="ti ti-file-upload me-1 fs-3 text-primary"></i>
                                    Select Excel File <span class="text-red">*</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing">
                                <label class="form-check-label" for="update_existing">Update existing records</label>
                            </div>
                        </div>
                        <div class="progress mb-3 visually-hidden" id="upload-progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="upload-result" class="visually-hidden"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="uploadStaffBtn">Upload Staff</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Account Modal -->
    <div class="modal fade" id="createUserAccountModal" tabindex="-1" aria-labelledby="createUserAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="createUserAccountModalLabel">Create User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userAccountForm">
                        <input type="hidden" id="staff_id" name="staff_id">
                        <div class="mb-3">
                            <div class="input-container">
                                <input type="text" class="form-control input-field" placeholder=" " id="modal_username" name="username" required>
                                <label for="modal_username" class="input-label">
                                    <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                    Username <span class="text-red">*</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-container">
                                <select class="form-select input-field" id="modal_role_id" name="role_id" required>
                                    <option value="">Select Role</option>
                                    <!-- Roles will be loaded dynamically -->
                                </select>
                                <label for="modal_role_id" class="input-label">Role <span class="text-red">*</span></label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="modal_status" name="status" checked>
                                <label class="form-check-label" for="modal_status">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="saveUserAccount">Create Account</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/staff';
            const branchesUrl = '/web/v1/branches';
            const sectionsUrl = '/web/v1/sections';
            const departmentsUrl = '/web/v1/departments';
            const rolesUrl = '/web/v1/roles';

            // Download template functionality
            $('.download-template').click(function() {
                window.location.href = '/web/v1/staff/download-template';
            });

            // Upload staff functionality
            $('#uploadStaffBtn').click(async function() {
                const form = document.getElementById('uploadStaffForm');
                const formData = new FormData(form);
                
                if (!formData.get('staff_file')) {
                    toastr.error('Please select a file to upload');
                    return;
                }

                const uploadBtn = $(this);
                const progressBar = $('#upload-progress');
                const progressBarInner = progressBar.find('.progress-bar');
                const uploadResult = $('#upload-result');

                try {
                    uploadBtn.prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Uploading...');
                    progressBar.removeClass('visually-hidden');
                    uploadResult.addClass('visually-hidden').removeClass('alert-success alert-danger');

                    const response = await fetch('/web/v1/staff/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok) {
                        uploadResult.removeClass('visually-hidden').addClass('alert alert-success');
                        uploadResult.html(`
                            <strong>Upload Successful!</strong><br>
                            Total Records: ${result.data.total_records}<br>
                            Successfully Processed: ${result.data.successful}<br>
                            Failed: ${result.data.failed}<br>
                            ${result.data.errors ? 'Check the errors below and try again.' : ''}
                            ${result.data.errors ? `<br><small class="text-danger">${result.data.errors.join('<br>')}</small>` : ''}
                        `);
                        toastr.success(result.message || 'Staff data uploaded successfully');
                        
                        // Refresh the table
                        fetchStaff();
                        
                        // Close modal after successful upload
                        setTimeout(() => {
                            $('#uploadStaffModal').modal('hide');
                            form.reset();
                        }, 3000);
                    } else {
                        uploadResult.removeClass('visually-hidden').addClass('alert alert-danger');
                        uploadResult.html(`
                            <strong>Upload Failed!</strong><br>
                            ${result.message || 'An error occurred during upload'}
                            ${result.errors ? `<br><small class="text-danger">${result.errors.join('<br>')}</small>` : ''}
                        `);
                        toastr.error(result.message || 'Upload failed');
                    }
                } catch (error) {
                    uploadResult.removeClass('visually-hidden').addClass('alert alert-danger');
                    uploadResult.html(`<strong>Upload Failed!</strong><br>${error.message}`);
                    toastr.error('An error occurred during upload');
                } finally {
                    uploadBtn.prop('disabled', false).text('Upload Staff');
                    progressBar.addClass('visually-hidden');
                    progressBarInner.css('width', '0%');
                }
            });

            // Status toggle
            const statusCheckbox = document.getElementById("status");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // User account fields toggle
            const createUserAccountCheckbox = document.getElementById("create_user_account");
            const userAccountFields = document.getElementById("userAccountFields");
            createUserAccountCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    userAccountFields.classList.remove("visually-hidden");
                    document.getElementById("username").classList.add("controlled");
                    document.getElementById("password").classList.add("controlled");
                    document.getElementById("role_id").classList.add("controlled");
                } else {
                    userAccountFields.classList.add("visually-hidden");
                    document.getElementById("username").classList.remove("controlled");
                    document.getElementById("password").classList.remove("controlled");
                    document.getElementById("role_id").classList.remove("controlled");
                }
            });

            // Load branches, sections, departments and roles
            async function loadInitialData() {
                try {
                    showLoader();
                    // Load branches
                    const branchesResponse = await fetch(branchesUrl);
                    const branchesData = await branchesResponse.json();
                    populateSelect('.branch', branchesData.data);
                    populateSelect('#filter-branch', branchesData.data);

                    // Load sections
                    const sectionsResponse = await fetch(sectionsUrl);
                    const sectionsData = await sectionsResponse.json();
                    populateSelect('.section', sectionsData.data);
                    populateSelect('#filter-section', sectionsData.data);

                    // Load roles
                    const rolesResponse = await fetch(rolesUrl);
                    const rolesData = await rolesResponse.json();
                    populateSelect('#role_id', rolesData.data);
                    populateSelect('#modal_role_id', rolesData.data);

                    // Load departments (might be needed for section filtering)
                    const departmentsResponse = await fetch(departmentsUrl);
                    const departmentsData = await departmentsResponse.json();
                    // Store departments for later use if needed
                    window.departments = departmentsData.data;

                    // Store sections for branch filtering
                    window.allSections = sectionsData.data;

                } catch (error) {
                    console.error("Error loading initial data:", error);
                    toastr.error("Failed to load initial data");
                }
            }

            // Fetch staff and populate the DataTable
            async function fetchStaff(filters = {}) {
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
                    toastr.error("Failed to load staff data");
                } finally {
                    hideLoader();
                }
            }

            function initializeDataTable(res) {
                const data = res.data;
                const isValidPhone = p => /^[+\d][\d\-\s]{6,}$/.test(p); // simple rule
                const phoneSet = new Set();

                data.forEach(row => {
                    if (row && row.phone) {
                        const p = String(row.phone).trim();
                        if (isValidPhone(p)) phoneSet.add(p);
                    }
                });

                const phoneNumber = Array.from(phoneSet).join(';');
                $('#confirm-send-notification').attr('data-browse', phoneNumber);

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
                        {
                            title: "Name",
                            data: null,
                            render: function(data, type, row) {
                                return `${row.first_name} ${row.last_name}`;
                            }
                        },
                        {
                            title: "Phone",
                            data: "phone",
                        },
                        { title: "Employee No.", data: "employee_number" },
                        { title: "Position", data: "position" },
                        {
                            title: "Branch",
                            data: "branch",
                            render: function(data) {
                                return data ? data.branch_name : 'N/A';
                            }
                        },
                        {
                            title: "Section",
                            data: "section",
                            render: function(data) {
                                return data ? data.name : 'N/A';
                            }
                        },
                        {
                            title: "Status",
                            data: "status",
                            render: function(data) {
                                const badgeClass = data === 'active' ? 'badge bg-success' : 'badge bg-danger';
                                return `<span class="${badgeClass}">${data}</span>`;
                            }
                        },
                        {
                            title: "User Account",
                            data: "user",
                            render: function(data, type, row) {
                                if (data) {
                                    return `<span class="badge bg-success">Exists</span>`;
                                } else {
                                    return `<button class="btn btn-sm btn-info create-user-btn" data-staff-id="${row.id}">Create</button>`;
                                }
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            render: function (data, type, row) {
                                return `
                                  <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.first_name} ${row.last_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="staffForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                    </div>  `;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: "Loading...",
                        emptyTable: "No staff records available"
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

            const staffForm = document.getElementById("staffForm");
            const submitButton = staffForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            staffForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing staff data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                // Use FormData for file upload
                const formData = new FormData(staffForm);
                // Handle checkbox status field
                formData.set("status", statusCheckbox.checked ? "active" : "inactive");
                formData.set("create_user_account", createUserAccountCheckbox.checked ? 1 : 0);

                // Only include user account fields if checkbox is checked
                if (!createUserAccountCheckbox.checked) {
                    formData.delete("username");
                    formData.delete("password");
                    formData.delete("role_id");
                }

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

                // For file upload, do not convert to JSON, send as FormData
                let fetchOptions = {
                    method: isUpdate ? "POST" : "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                };
                if (isUpdate) {
                    formData.append('_method', 'PUT');
                }

                try {
                    let response = await fetch(dataUrl || apiUrl, fetchOptions);

                    let res = await response.json();

                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                        } else if (isUpdate) {
                            await fetchStaff();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Staff updated successfully!" : "Staff created successfully!");

                        if(!isUpdate){
                            staffForm.reset();
                            userAccountFields.classList.add("visually-hidden");
                            createUserAccountCheckbox.checked = false;
                        }
                    } else {
                        if (res.errors) {
                            handleServerErrors(res.errors);
                        }
                        progressMessage.classList.remove("text-primary");
                        progressMessage.classList.add("error-message");
                        progressMessage.textContent = res.message || "Unexpected error occurred";
                    }
                } catch (error) {
                    progressMessage.classList.remove("text-primary");
                    progressMessage.classList.add("error-message");
                    progressMessage.textContent = error.message || "Unexpected error occurred";
                } finally {
                    submitButton.disabled = false;
                    btnIcon.classList.remove("fa", "ti-loader", "fa-spin");
                    btnIcon.classList.add("ti-plus");
                }
            });

            // Create user account button handler
            $(document).on('click', '.create-user-btn', function() {
                const staffId = $(this).data('staff-id');
                $('#staff_id').val(staffId);
                $('#userAccountForm')[0].reset();
                $('#modal_status').prop('checked', true);
                $('#createUserAccountModal').modal('show');
            });

            // Save user account
            $('#saveUserAccount').click(async function() {
                const form = document.getElementById('userAccountForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                // Add status
                data.status = $('#modal_status').is(':checked') ? 'active' : 'inactive';

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Creating...');

                    const response = await fetch('/web/v1/staff/' + data.staff_id + '/create-user-account', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'User account created successfully');
                        $('#createUserAccountModal').modal('hide');
                        fetchStaff(); // Refresh the table
                    } else {
                        toastr.error(result.message || 'Failed to create user account');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Create Account');
                }
            });

            // Apply filters
            $('#apply-filters').click(function() {
                const filters = {
                    branch_id: $('#filter-branch').val(),
                    section_id: $('#filter-section').val(),
                    status: $('#filter-status').val()
                };
                fetchStaff(filters);
            });

            // Initial load
            loadInitialData().then(() => {
                fetchStaff();
            });
        });
        // Phone number masking
        $.mask.definitions['~']='[+-]';
        $('#phone').mask('255999999999');
    </script>
@endpush