@extends('layouts.app')

@section('title', $page = 'Users')

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
                <form id="userForm">
                    <div class="alert alert-info mb-3 p-2">
                        <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                    </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <input type="text" id="username" name="username" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="username" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Username <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="parking_location_id" name="parking_location_id" class="input-field form-select controlled" required>
                                        <option value="">Select Parking Location</option>
                                        <!-- Parking locations will be loaded dynamically -->
                                    </select>
                                    <label for="parking_location_id" class="input-label">
                                        <i class="ti ti-shield me-1 fs-3 text-primary"></i>
                                        Parking Location <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="role_id" name="role_id" class="input-field form-select controlled" required>
                                        <option value="">Select Role</option>
                                        <!-- Roles will be loaded dynamically -->
                                    </select>
                                    <label for="role_id" class="input-label">
                                        <i class="ti ti-shield me-1 fs-3 text-primary"></i>
                                        Role <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-container">
                                    <select id="staff_id" name="staff_id" class="input-field form-select controlled">
                                        <option value="">Select Staff (Optional)</option>
                                        <!-- Staff will be loaded dynamically -->
                                    </select>
                                    <label for="staff_id" class="input-label">
                                        <i class="ti ti-id me-1 fs-3 text-primary"></i>
                                        Staff Member
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
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="userForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="userForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search by username or staff...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-role" class="form-select">
                                <option value="">All Roles</option>
                                <!-- Roles will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <input type="hidden" id="user_id" name="user_id">
                        <div class="mb-3 input-container">
                            <input type="password" class="form-control input-field" id="current_password" name="current_password" required>
                            <label for="current_password" class="input-label">Current Password <span class="text-red">*</span></label>
                        </div>
                        <div class="mb-3 input-container">
                            <input type="password" class="form-control input-field" id="new_password" name="new_password" required>
                            <label for="new_password" class="input-label">New Password <span class="text-red">*</span></label>
                        </div>
                        <div class="mb-3 input-container">
                            <input type="password" class="form-control input-field" id="confirm_password" name="confirm_password" required>
                            <label for="confirm_password" class="input-label">Confirm Password <span class="text-red">*</span></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="savePasswordBtn">Change Password</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/users';
            const rolesUrl = '/web/v1/roles';
            const staffUrl = '/web/v1/staff';
            const parkingLocationsUrl = '/web/v1/parking-locations';

            // Status toggle
            const statusCheckbox = document.getElementById("status");
            const statusLabel = document.getElementById("statusLabel");
            statusCheckbox.addEventListener("change", function (){
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            // Load initial data (roles, staff)
            async function loadInitialData() {
                try {
                    showLoader();
                    const [rolesRes, staffRes, parkingLocationsRes] = await Promise.all([
                        fetch(rolesUrl),
                        fetch(staffUrl),
                        fetch(parkingLocationsUrl)
                    ]);

                    const roles = await rolesRes.json();
                    const staff = await staffRes.json();
                    const parkingLocations = await parkingLocationsRes.json();

                    populateSelect('#parking_location_id', parkingLocations.data);
                    populateSelect('#role_id', roles.data);
                    populateSelect('#staff_id', staff.data);
                    populateSelect('#filter-role', roles.data);

                } catch (error) {
                    console.error("Error loading initial data:", error);
                    toastr.error("Failed to load initial data");
                }
            }

            // Fetch users and populate the DataTable
            async function fetchUsers(filters = {}) {
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
                    console.error("Error fetching user records:", error);
                    toastr.error("Failed to load user data");
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
                        { title: "Username", data: "username" },
                        {
                            title: "Role",
                            data: "role",
                            render: function(data) {
                                return data ? data.name : '-';
                            }
                        },
                        {
                            title: "Staff",
                            data: "staff",
                            render: function(data) {
                                return data ? `${data.first_name} ${data.last_name}` : '-';
                            }
                        },
                        {
                            title: "Parking Location",
                            data: "parking_location",
                            render: function(data) {
                                return data ? data.location_name : '-';
                            }
                        },
                        {
                            title: "Status",
                            data: "status",
                            render: function(data) {
                                const badgeClass = data === 'active' ? 'badge bg-success' : 'badge bg-danger';
                                const statusText = data === 'active' ? 'Active' : 'Inactive';
                                return `<span class="${badgeClass}">${statusText}</span>`;
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            render: function (data, type, row) {
                                return `
                                     <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.staff.first_name} ${row.staff.last_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-primary update-record" data-browse='${JSON.stringify(data)}' data-for="userForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm ${row.status === 'active' ? 'btn-warning' : 'btn-success'} toggle-status-btn" data-user-id="${row.id}">
                                            ${row.status === 'active' ? 'Deactivate' : 'Activate'}
                                        </button>
                                    </div>
                                        <!--<button class="btn btn-sm btn-secondary change-password-btn" data-user-id="${row.id}" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            <i class="ti ti-key"></i>
                                        </button>-->
                                        
                                `;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: "Loading...",
                        emptyTable: "No user records available"
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

            const userForm = document.getElementById("userForm");
            const submitButton = userForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            userForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing user data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(userForm);
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
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });

                    let res = await response.json();

                    if (response.ok) {
                        if (!isUpdate && res.data) {
                            addNewRecordToTable(res.data);
                        } else if (isUpdate) {
                            await fetchUsers();
                        }

                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "User updated successfully!" : "User created successfully!");

                        if(!isUpdate){
                            userForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
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

            // Change password modal
            $(document).on('click', '.change-password-btn', function() {
                const userId = $(this).data('user-id');
                $('#user_id').val(userId);
                $('#changePasswordForm')[0].reset();
            });

            // Save password change
            $('#savePasswordBtn').click(async function() {
                const form = document.getElementById('changePasswordForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    $(this).prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span> Changing...');

                    const response = await fetch(`/web/v1/users/${data.user_id}/change-password`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            current_password: data.current_password,
                            new_password: data.new_password,
                            confirm_password: data.confirm_password
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'Password changed successfully');
                        $('#changePasswordModal').modal('hide');
                    } else {
                        toastr.error(result.message || 'Failed to change password');
                        if (result.errors) {
                            handleServerErrors(result.errors);
                        }
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $(this).prop('disabled', false).text('Change Password');
                }
            });

            // Toggle user status
            $(document).on('click', '.toggle-status-btn', async function() {
                const $this = $(this);
                const userId = $this.data('user-id');

                try {
                    $this.prop('disabled', true).html('<span class="ti ti-loader fa fa-spin"></span>');

                    const response = await fetch(`/web/v1/users/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success(result.message || 'User status updated successfully');
                        fetchUsers(); // Refresh the table
                    } else {
                        toastr.error(result.message || 'Failed to update user status');
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    $this.prop('disabled', false);
                }
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchIcom = $('.filter-btn')
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                const searchTerm = $('#search-input').val();
                await fetchUsers({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by role
            $('#filter-role').change(function() {
                const roleId = $(this).val();
                fetchUsers({role_id: roleId});
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchUsers({status: status});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-role').val('');
                $('#filter-status').val('');
                fetchUsers();
            });

            // Initial load
            loadInitialData().then(() => {
                fetchUsers();
            });
        });
    </script>
@endpush
