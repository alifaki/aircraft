@extends('layouts.app')

@section('title', $page = 'Permissions')

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
                    <form id="permissionForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled" placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-id me-1 fs-3 text-primary"></i>
                                        Permission Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="slug" name="slug" class="input-field form-control controlled" placeholder=" ">
                                    <label for="slug" class="input-label">
                                        <i class="ti ti-key me-1 fs-3 text-primary"></i>
                                        Permission Slug <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="module" name="module" class="input-field form-select controlled">
                                        <option value="">Select Module</option>
                                    </select>
                                    <label for="module" class="input-label">
                                        <i class="ti ti-layout-grid me-1 fs-3 text-primary"></i>
                                        Module <span class="text-red"> *</span>
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
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="permissionForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="permissionForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                            <select id="moduleFilter" class="form-select">
                                <option value="">All Modules</option>
                                <!-- Modules will be populated via JavaScript -->
                            </select>
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
@endsection
<!-- Add this modal at the bottom of the content section -->
<div class="modal fade" id="roleAssignmentModal" tabindex="-1" aria-labelledby="roleAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="roleAssignmentModalLabel">Assign Permission to Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Available Roles</h6>
                        <div id="availableRoles" class="list-group"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>Assigned Roles</h6>
                        <div id="assignedRoles" class="list-group"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveRoleAssignments">Save Assignments</button>
            </div>
        </div>
    </div>
</div>
@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/permissions';

            // Load modules for dropdown and filter
            function loadModules() {
                // Populate module dropdown in form
                const moduleSelect = document.getElementById('module');
                const moduleFilter = document.getElementById('moduleFilter');
                const PERMISSION_MODULES = @json(config('permissions.modules'));
                PERMISSION_MODULES.forEach(module => {
                    const option = document.createElement('option');
                    option.value = module;
                    option.textContent = module;

                    moduleSelect.appendChild(option);
                    moduleFilter.appendChild(option.cloneNode(true));
                });
            }

            // Fetch permissions and populate the DataTable
            async function fetchPermissions(module = null) {
                try {
                    showLoader();
                    let url = apiUrl;
                    if (module) {
                        url += `?module=${encodeURIComponent(module)}`;
                    }

                    const response = await fetch(url, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const res = await response.json();
                    initializeDataTable(res);
                } catch (error) {
                    console.error("Error fetching permission records:", error);
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
                        {
                            title: "Name",
                            data: "name",
                            render: function(data) {
                                return `<strong>${data}</strong>`;
                            }
                        },
                        {
                            title: "Slug",
                            data: "slug",
                            render: function(data) {
                                return `<code>${data}</code>`;
                            }
                        },
                        {
                            title: "Module",
                            data: "module",
                            render: function(data) {
                                return `<span class="badge bg-primary">${data}</span>`;
                            }
                        },
                        {
                            title: "Description",
                            data: "description",
                            render: function(data) {
                                return data || 'N/A';
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-sm btn-primary assign-roles" data-permission-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Assign to Roles">
                                            <i class="ti ti-users"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="permissionForm" data-url="${apiUrl}/${row.id}">
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
                        emptyTable: "No permission records available"
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
            const permissionForm = document.getElementById("permissionForm");
            const submitButton = permissionForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            permissionForm.addEventListener('submit', async function (event) {
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

                const formData = new FormData(permissionForm);
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
                            await fetchPermissions();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Company updated successfully!" : "Company created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            permissionForm.reset();
                            permissionForm.classList.remove('was-validated');
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

            // Handle module filter change
            document.getElementById('moduleFilter').addEventListener('change', function() {
                const module = this.value;
                fetchPermissions(module);
            });
// Handle role assignment button click
            $(document).on('click', '.assign-roles', async function() {
                const permissionId = $(this).data('permission-id');
                $('#roleAssignmentModal').data('permission-id', permissionId);

                try {
                    const response = await fetch(`/web/v1/permissions/${permissionId}/roles`, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    renderRoleAssignment(data.data);
                    $('#roleAssignmentModal').modal('show');
                } catch (error) {
                    showToast('error', 'Failed to load role assignment data');
                }
            });

// Render role assignment lists
            function renderRoleAssignment(data) {
                const availableRoles = document.getElementById('availableRoles');
                const assignedRoles = document.getElementById('assignedRoles');

                availableRoles.innerHTML = '';
                assignedRoles.innerHTML = '';

                // Render available roles
                data.available.forEach(role => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action';
                    item.textContent = role.name;
                    item.dataset.roleId = role.id;
                    item.addEventListener('click', () => assignRole(role.id));
                    availableRoles.appendChild(item);
                });

                // Render assigned roles
                data.assigned.forEach(role => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action list-group-item-primary';
                    item.textContent = role.name;
                    item.dataset.roleId = role.id;
                    item.addEventListener('click', () => unassignRole(role.id));
                    assignedRoles.appendChild(item);
                });
            }

// Handle role assignment
            function assignRole(roleId) {
                const button = document.querySelector(`#availableRoles button[data-role-id="${roleId}"]`);
                if (button) {
                    button.removeEventListener('click', () => assignRole(roleId));
                    button.addEventListener('click', () => unassignRole(roleId));
                    button.classList.remove('list-group-item-action');
                    button.classList.add('list-group-item-primary');
                    document.getElementById('assignedRoles').appendChild(button);
                }
            }

// Handle role unassignment
            function unassignRole(roleId) {
                const button = document.querySelector(`#assignedRoles button[data-role-id="${roleId}"]`);
                if (button) {
                    button.removeEventListener('click', () => unassignRole(roleId));
                    button.addEventListener('click', () => assignRole(roleId));
                    button.classList.remove('list-group-item-primary');
                    document.getElementById('availableRoles').appendChild(button);
                }
            }

// Save role assignments
            document.getElementById('saveRoleAssignments').addEventListener('click', async function() {
                const permissionId = $('#roleAssignmentModal').data('permission-id');
                const assignedRoles = Array.from(document.querySelectorAll('#assignedRoles button'))
                    .map(button => button.dataset.roleId);

                try {
                    const response = await fetch(`/web/v1/permissions/${permissionId}/assign-roles`, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ roles: assignedRoles })
                    });

                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Failed to save role assignments');

                    showToast('success', data.message);
                    $('#roleAssignmentModal').modal('hide');
                } catch (error) {
                    showToast('error', error.message);
                }
            });


            // Initialize the page
            loadModules();
            fetchPermissions();
        });
    </script>
@endpush
