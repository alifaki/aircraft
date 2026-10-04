@extends('layouts.app')

@section('title', $page = 'Roles')

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
                    <form id="roleForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="name" name="name" class="input-field form-control controlled"
                                           placeholder=" ">
                                    <label for="name" class="input-label">
                                        <i class="ti ti-id me-1 fs-3 text-primary"></i>
                                        Role Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <textarea id="description" name="description" class="input-field form-control"
                                              placeholder=" "></textarea>
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
                                    <input class="form-check-input controlled" name="is_default" type="checkbox"
                                           id="is_default">
                                    <label for="is_default" id="isDefaultLabel">Default Role</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input controlled" value="active" name="status"
                                           type="checkbox" id="status" checked>
                                    <label for="status" id="statusLabel">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Permissions</h6>
                                        <button type="button" class="btn btn-sm btn-primary" id="selectAllPermissions">
                                            Select All
                                        </button>
                                    </div>
                                    <div class="card-body" id="permissionsContainer">
                                        <div class="row" id="permissionsList"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="roleForm"
                                            id="reset-btn">
                                        <i class="ti ti-x"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-primary ms-1" data-url=""
                                            id="create-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="roleForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                    <table class="display dataTable table table-responsive table-bordered">
                        <thead>
                        </thead>
                        <tbody>
                        <tr>
                            <td id="table-loader"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Assignment Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="permissionModalLabel">Manage Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs"  id="permissionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="assign-tab" data-bs-toggle="tab"
                                    data-bs-target="#assign-tab-pane" type="button" role="tab">Assign Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="remove-tab" data-bs-toggle="tab"
                                    data-bs-target="#remove-tab-pane" type="button" role="tab">Remove Permissions
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="permissionTabsContent">
                        <div class="tab-pane fade show active" id="assign-tab-pane" role="tabpanel">
                            <div class="row mt-3" id="availablePermissionsList"></div>
                        </div>
                        <div class="tab-pane fade" id="remove-tab-pane" role="tabpanel">
                            <div class="row mt-3" id="currentPermissionsList"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="savePermissionsBtn">Save Changes</button>
                    <button type="button" class="btn btn-danger btn-sm" id="removePermissionsBtn">Remove Selected</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/roles';
            const permissionsUrl = '/web/v1/permissions';

            const statusCheckbox = document.getElementById("status");
            const statusLabel = document.getElementById("statusLabel");
            const isDefaultCheckbox = document.getElementById("is_default");
            const isDefaultLabel = document.getElementById("isDefaultLabel");
            const selectAllBtn = document.getElementById("selectAllPermissions");

            // Store current role permissions for the modal
            let currentRolePermissions = [];

            // Listen for checkbox change events
            statusCheckbox.addEventListener("change", function () {
                statusLabel.textContent = statusCheckbox.checked ? "Active" : "Inactive";
            });

            isDefaultCheckbox.addEventListener("change", function () {
                isDefaultLabel.textContent = isDefaultCheckbox.checked ? "Default Role: Yes" : "Default Role: No";
            });

            // Select all permissions
            selectAllBtn.addEventListener("click", function () {
                const checkboxes = document.querySelectorAll('#permissionsList input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });

            // Fetch roles and populate the DataTable
            async function fetchRoles() {
                try {
                    showLoader();
                    const response = await fetch(apiUrl, {
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
                    console.error("Error fetching role records:", error);
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
                            title: "Role Name",
                            data: "name",
                            render: function (data) {
                                return `<strong>${data}</strong>`;
                            }
                        },
                        {
                            title: "Description",
                            data: "description",
                            render: function (data) {
                                return data || 'N/A';
                            }
                        },
                        {
                            title: "Permissions",
                            data: "permissions",
                            render: function (data) {
                                if (!data || data.length === 0) return 'No permissions';
                                const count = data.length;
                                return `<span class="badge bg-primary">${count} permission${count !== 1 ? 's' : ''}</span>`;
                            }
                        },
                        {
                            title: "Is Default",
                            data: "is_default",
                            render: function (data) {
                                const badgeClass = data === true ? 'badge bg-success' : 'badge bg-danger';
                                return `<span class="${badgeClass}">${data === true ? "Yes" : "No"}</span>`;
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="roleForm" data-url="${apiUrl}/${row.id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.name}" data-url="${apiUrl}/${row.id}"  data-bs-toggle="modal" data-bs-target="#confirm-delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                   <button class="btn btn-sm btn-primary assign-permissions" data-role-id="${row.id}" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Permissions">
                                        <i class="ti ti-key"></i> Permissions
                                    </button>`;
                            }
                        }
                    ],
                    responsive: true,
                    language: {
                        loadingRecords: "Loading...",
                        emptyTable: "No role records available"
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

            const roleForm = document.getElementById("roleForm");
            const submitButton = roleForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            roleForm.addEventListener('submit', async function (event) {
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

                const formData = new FormData(roleForm);
                formData.set("status", statusCheckbox.checked ? "active" : "inactive");
                formData.set("is_default", isDefaultCheckbox.checked ? 1 : 0);

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
                            await fetchRoles();
                        }

                        progressMessage.className = "text-success small";
                        progressMessage.textContent = res.message || (isUpdate ? "Company updated successfully!" : "Company created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        if(!isUpdate){
                            roleForm.reset();
                            statusCheckbox.checked = true;
                            statusLabel.textContent = "Active";
                            roleForm.classList.remove('was-validated');
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

            // Update the modal opening handler
            $(document).on('click', '.assign-permissions', async function() {
                const roleId = $(this).data('role-id');
                $('#permissionModal').data('role-id', roleId);
                await loadRolePermissions(roleId);
                $('#permissionModal').modal('show');
            });

            // Save permission assignments - FIXED: Merge with existing permissions
            $('#savePermissionsBtn').click(async function() {
                const roleId = $('#permissionModal').data('role-id');
                const checkboxes = $('#availablePermissionsList input[type="checkbox"]:checked');
                const newPermissions = Array.from(checkboxes).map(cb => parseInt(cb.value));

                if (newPermissions.length === 0) {
                    showToast('warning', 'Please select at least one permission to assign');
                    return;
                }

                try {
                    // Get current permissions and merge with new ones
                    const currentPermissionIds = currentRolePermissions.map(p => p.id);
                    const allPermissions = [...new Set([...currentPermissionIds, ...newPermissions])];

                    const response = await fetch(`${apiUrl}/${roleId}/assign-permissions`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ permissions: allPermissions })
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const data = await response.json();
                    showToast('success', data.message);
                    $('#permissionModal').modal('hide');
                    fetchRoles(); // Refresh the table
                } catch (error) {
                    showToast('error', error.message);
                }
            });

            // Handle permission removal - FIXED: Remove only selected permissions
            $('#removePermissionsBtn').click(async function() {
                const roleId = $('#permissionModal').data('role-id');
                const checkboxes = $('#currentPermissionsList input[type="checkbox"]:checked');
                const permissionsToRemove = Array.from(checkboxes).map(cb => parseInt(cb.value));

                if (permissionsToRemove.length === 0) {
                    showToast('warning', 'Please select at least one permission to remove');
                    return;
                }

                try {
                    // Get current permissions and filter out the ones to remove
                    const currentPermissionIds = currentRolePermissions.map(p => p.id);
                    const updatedPermissions = currentPermissionIds.filter(id => 
                        !permissionsToRemove.includes(id)
                    );

                    const response = await fetch(`${apiUrl}/${roleId}/assign-permissions`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ permissions: updatedPermissions })
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const data = await response.json();
                    showToast('success', data.message);

                    // Refresh both permission lists
                    await loadRolePermissions(roleId);
                    
                    // Stay on the current tab
                    const currentTab = $('#permissionTabs .nav-link.active').attr('id');
                    if (currentTab === 'remove-tab') {
                        $('#remove-tab').tab('show');
                    }
                } catch (error) {
                    showToast('error', error.message);
                }
            });

            /**
             * Creates a permission card for a module
             * @param {string} module - Module name
             * @param {Array} permissions - Array of permission objects
             * @param {string} checkboxPrefix - Prefix for checkbox IDs
             * @param {Array} checkedPermissions - Array of permission IDs that should be checked
             * @param {boolean} showCheckboxes - Whether to show checkboxes
             * @returns {HTMLElement} - The created card element
             */
            function createPermissionCard(module, permissions, checkboxPrefix = '', checkedPermissions = [], showCheckboxes = true) {
                const moduleDiv = document.createElement('div');
                moduleDiv.className = 'col-md-6 mb-3';

                const cardDiv = document.createElement('div');
                cardDiv.className = 'card';

                const cardHeader = document.createElement('div');
                cardHeader.className = 'card-header bg-light';
                cardHeader.innerHTML = `<h6 class="mb-0">${module}</h6>`;

                const cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                permissions.forEach(perm => {
                    const div = document.createElement('div');
                    div.className = 'form-check mb-2';

                    if (showCheckboxes) {
                        const input = document.createElement('input');
                        input.className = 'form-check-input permission-checkbox';
                        input.type = 'checkbox';
                        input.value = perm.id;
                        input.id = `${checkboxPrefix}perm_${perm.id}`;
                        input.name = 'permissions[]';

                        if (checkedPermissions.includes(perm.id)) {
                            input.checked = true;
                        }
                        div.appendChild(input);
                    }

                    const label = document.createElement('label');
                    label.className = 'form-check-label';
                    label.htmlFor = `${checkboxPrefix}perm_${perm.id}`;
                    label.textContent = perm.name;
                    div.appendChild(label);

                    cardBody.appendChild(div);
                });

                cardDiv.appendChild(cardHeader);
                cardDiv.appendChild(cardBody);
                moduleDiv.appendChild(cardDiv);

                return moduleDiv;
            }

            // Render permissions in the main form
            function renderPermissions(permissions, containerId, selectedPermissions = []) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                const grouped = groupPermissionsByModule(permissions);

                Object.entries(grouped).forEach(([module, perms]) => {
                    if (perms.length > 0) {
                        container.appendChild(
                            createPermissionCard(module, perms, '', selectedPermissions, true)
                        );
                    }
                });
            }

            // Render available permissions in modal
            function renderAvailablePermissions(permissions, currentPermissions = []) {
                const container = document.getElementById('availablePermissionsList');
                container.innerHTML = '';

                const grouped = groupPermissionsByModule(permissions);

                Object.entries(grouped).forEach(([module, perms]) => {
                    if (perms.length > 0) {
                        container.appendChild(
                            createPermissionCard(module, perms, 'avail_', currentPermissions, true)
                        );
                    }
                });
            }

            // Render current permissions for removal
            function renderCurrentPermissions(permissions) {
                const container = document.getElementById('currentPermissionsList');
                container.innerHTML = '';

                const grouped = groupPermissionsByModule(permissions);

                Object.entries(grouped).forEach(([module, perms]) => {
                    if (perms.length > 0) {
                        container.appendChild(
                            createPermissionCard(module, perms, 'current_', [], true)
                        );
                    }
                });
            }

            // Helper function to group permissions by module
            function groupPermissionsByModule(permissions) {
                const PERMISSION_MODULES = @json(config('permissions.modules'));
                return PERMISSION_MODULES.reduce((acc, module) => {
                    acc[module] = permissions.filter(p => p.module === module);
                    return acc;
                }, {});
            }

            // Load permissions for the role form
            async function loadPermissions() {
                try {
                    const response = await fetch(permissionsUrl, {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const res = await response.json();
                    renderPermissions(res.data, 'permissionsList');
                } catch (error) {
                    console.error("Error loading permissions:", error);
                }
            }

            // Enhanced function to load all permission data for a role
            async function loadRolePermissions(roleId) {
                try {
                    // Get role's current permissions
                    const roleResponse = await fetch(`${apiUrl}/${roleId}`, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const roleData = await roleResponse.json();
                    currentRolePermissions = roleData.data.permissions || [];
                    const currentPermissionIds = currentRolePermissions.map(p => p.id);

                    // Render current permissions (grouped by our constant modules)
                    renderCurrentPermissions(currentRolePermissions);

                    // Get all available permissions for the assign tab
                    const permResponse = await fetch(permissionsUrl, {
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const permData = await permResponse.json();

                    // Render available permissions (excluding already assigned ones)
                    const availablePermissions = permData.data.filter(p =>
                        !currentPermissionIds.includes(p.id)
                    );
                    renderAvailablePermissions(availablePermissions);
                } catch (error) {
                    console.error("Error loading permissions:", error);
                }
            }

            // Initialize the page
            fetchRoles();
            loadPermissions();
        });
    </script>
@endpush