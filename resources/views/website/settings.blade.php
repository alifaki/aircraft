@extends('layouts.app')

@section('title', $page = 'Settings')

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
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">System {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                        <button class="btn btn-sm btn-light" id="add-setting" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Setting">
                            <i class="ti ti-plus"></i> Add Setting
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Slug</th>
                                <th>Metadata</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr><td id="table-loader" colspan="6"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Form Modal -->
    <div class="modal fade" id="settingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="modal-title">Add New Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="settingForm">
                    <div class="modal-body">
                        <input type="hidden" id="setting-id">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" required>
                                <div class="invalid-feedback" id="slug-error"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Metadata</label>
                                <div id="metadata-container">
                                    <!-- Initial metadata fields -->
                                    <div class="row mb-2 metadata-field" data-id="application-status">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_keys[]" value="application_status" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="form-select" name="metadata_values[]">
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            
                                        </div>
                                    </div>
                                    <div class="row mb-2 metadata-field" data-id="website-mode">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_keys[]" value="website_mode" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="form-select" name="metadata_values[]">
                                                <option value="active">Active</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            
                                        </div>
                                    </div>
                                    <div class="row mb-2 metadata-field" data-id="system-status">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_keys[]" value="system_status" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="form-select" name="metadata_values[]">
                                                <option value="active">Active</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            
                                        </div>
                                    </div>
                                    <div class="row mb-2 metadata-field" data-id="admin-phone">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_keys[]" value="admin_phone" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_values[]" placeholder="+1234567890">
                                        </div>
                                        <div class="col-md-2">
                                           
                                        </div>
                                    </div>
                                    <div class="row mb-2 metadata-field" data-id="admin-email">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="metadata_keys[]" value="admin_email" readonly>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="email" class="form-control" name="metadata_values[]" placeholder="admin@example.com">
                                        </div>
                                        <div class="col-md-2">
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 d-none" id="system-mode-row">
                                    <div class="col-md-12">
                                    
                                        <div class="row mb-2 metadata-field" data-id="system-mode">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="metadata_keys[]" value="system_mode" readonly>
                                            </div>
                                            <div class="col-md-5">
                                                <select class="form-select" name="metadata_values[]">
                                                    <option value="School">School</option>
                                                    <option value="College/University">College/University</option>
                                                    <option value="Center">Center</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-metadata-field">
                                    <i class="ti ti-plus"></i> Add Custom Field
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="save-setting">
                            <i class="ti ti-device-floppy"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const apiUrl = '/web/v1/settings';
            const tableElement = $('.dataTable');
            let metadataFields = [];

            // Store the initial HTML of the metadata container for reset
            const initialMetadataHtml = $('#metadata-container').html();
            const initialSystemModeHtml = $('#system-mode-row').html();

            // Helper to show/hide system_mode field based on slug value
            function toggleSystemModeField(slugValue, metadata = null) {
                if (slugValue === 'system-settings') {
                    // Show the system_mode row if not already visible
                    $('#system-mode-row').removeClass('d-none');
                    // Set value if editing
                    if (metadata && metadata.system_mode) {
                        $('#system-mode-row').find('select[name="metadata_values[]"]').val(metadata.system_mode);
                    } else {
                        // Reset to default
                        $('#system-mode-row').find('select[name="metadata_values[]"]').val('School');
                    }
                } else {
                    $('#system-mode-row').addClass('d-none');
                    // Optionally clear value
                    $('#system-mode-row').find('select[name="metadata_values[]"]').val('School');
                }
            }

            // Fetch and initialize data
            async function fetchSettings(filters = {}) {
                try {
                    showLoader();

                    const queryParams = new URLSearchParams(filters).toString();
                    const url = queryParams ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url);
                    const data = await response.json();

                    initializeDataTable(data);
                } catch (error) {
                    showToast('error', error.message);
                } finally {
                    hideLoader();
                }
            }

            function showLoader() {
                $('#table-loader').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
            }

            function hideLoader() {
                $('#table-loader').html('');
            }

            function showToast(type, message) {
                // Implement your toast notification here
                console.log(`${type}: ${message}`);
            }

            function initializeDataTable(res) {
                const data = res.data;
                tableElement.DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        { 
                            data: null, 
                            render: (data, type, row, meta) => meta.row + 1 
                        },
                        { data: 'slug' },
                        { 
                            data: 'metadata',
                            render: (metadata) => {
                                if (!metadata) return 'N/A';
                                return Object.entries(metadata).map(([key, value]) => 
                                    `<span class="badge bg-info me-1">${key}: ${value}</span>`
                                ).join('');
                            }
                        },
                        { 
                            data: 'created_at',
                            render: (date) => new Date(date).toLocaleString()
                        },
                        { 
                            data: 'updated_at',
                            render: (date) => new Date(date).toLocaleString()
                        },
                        {
                            data: null,
                            render: (data) => `
                                <button class="btn btn-sm btn-info edit-setting" 
                                        data-id="${data.id}"
                                        data-slug="${data.slug}"
                                        data-metadata='${JSON.stringify(data.metadata || {})}'>
                                    <i class="ti ti-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-setting" data-id="${data.id}">
                                    <i class="ti ti-trash"></i> Delete
                                </button>
                            `
                        }
                    ],
                    order: [[3, 'desc']] // Sort by created_at descending
                });
            }

            // Add metadata field
            $('#add-metadata-field').click(function() {
                const fieldId = `metadata-${Date.now()}`;
                const fieldHtml = `
                    <div class="row mb-2 metadata-field" data-id="${fieldId}">
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Key" name="metadata_keys[]">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Value" name="metadata_values[]">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger remove-metadata" data-id="${fieldId}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#metadata-container').append(fieldHtml);
            });

            // Remove metadata field - prevent removal of required fields
            $(document).on('click', '.remove-metadata', function() {
                const fieldId = $(this).data('id');
                // Don't allow removal of required fields
                if (!['application-status', 'website-mode', 'system-status', 'admin-phone', 'admin-email', 'system-mode'].includes(fieldId)) {
                    $(`.metadata-field[data-id="${fieldId}"]`).remove();
                } else {
                    showToast('warning', 'This is a required field and cannot be removed');
                }
            });

            // Show/hide system_mode field on slug change
            $(document).on('input', '#slug', function() {
                const slugValue = $(this).val();
                toggleSystemModeField(slugValue);
            });

            // Add new setting
            $('#add-setting').click(function() {
                $('#modal-title').text('Add New Setting');
                $('#setting-id').val('');
                $('#slug').val('');
                // Reset metadata fields to initial state
                $('#metadata-container').html(initialMetadataHtml);
                // Reset select fields to default values
                $('#metadata-container').find('select[name="metadata_values[]"]').each(function() {
                    $(this).val($(this).find('option:first').val());
                });
                // Reset input fields
                $('#metadata-container').find('input[name="metadata_values[]"]').not('[readonly]').val('');
                // Hide system_mode field by default
                $('#system-mode-row').addClass('d-none');
                $('#system-mode-row').find('select[name="metadata_values[]"]').val('School');
                $('#settingModal').modal('show');
            });

            // Edit setting
            $(document).on('click', '.edit-setting', function() {
                $('#modal-title').text('Edit Setting');
                $('#setting-id').val($(this).data('id'));
                $('#slug').val($(this).data('slug'));

                // Reset metadata fields to initial state before populating
                $('#metadata-container').html(initialMetadataHtml);

                // Hide system_mode field by default
                $('#system-mode-row').addClass('d-none');
                $('#system-mode-row').find('select[name="metadata_values[]"]').val('School');

                const metadata = $(this).data('metadata');
                const slugValue = $(this).data('slug');

                // Set values for predefined fields
                if (metadata) {
                    // Application Status
                    if (metadata.application_status) {
                        $('#metadata-container').find('[name="metadata_keys[]"][value="application_status"]').closest('.metadata-field').find('select[name="metadata_values[]"]').val(metadata.application_status);
                    }
                    // Website Mode
                    if (metadata.website_mode) {
                        $('#metadata-container').find('[name="metadata_keys[]"][value="website_mode"]').closest('.metadata-field').find('select[name="metadata_values[]"]').val(metadata.website_mode);
                    }
                    // System Status
                    if (metadata.system_status) {
                        $('#metadata-container').find('[name="metadata_keys[]"][value="system_status"]').closest('.metadata-field').find('select[name="metadata_values[]"]').val(metadata.system_status);
                    }
                    // Admin Phone
                    if (metadata.admin_phone) {
                        $('#metadata-container').find('[name="metadata_keys[]"][value="admin_phone"]').closest('.metadata-field').find('input[name="metadata_values[]"]').val(metadata.admin_phone);
                    }
                    // Admin Email
                    if (metadata.admin_email) {
                        $('#metadata-container').find('[name="metadata_keys[]"][value="admin_email"]').closest('.metadata-field').find('input[name="metadata_values[]"]').val(metadata.admin_email);
                    }
                }

                // If slug is system-settings, show system_mode field and set value if present
                if (slugValue === 'system-settings') {
                    $('#system-mode-row').removeClass('d-none');
                    if (metadata && metadata.system_mode) {
                        $('#system-mode-row').find('select[name="metadata_values[]"]').val(metadata.system_mode);
                    } else {
                        $('#system-mode-row').find('select[name="metadata_values[]"]').val('School');
                    }
                } else {
                    $('#system-mode-row').addClass('d-none');
                }

                // Add any additional custom fields (excluding system_mode if slug is system-settings)
                if (metadata) {
                    Object.entries(metadata).forEach(([key, value]) => {
                        if (!['application_status', 'website_mode', 'system_status', 'admin_phone', 'admin_email', 'system_mode'].includes(key)) {
                            const fieldId = `metadata-${Date.now()}-${Math.floor(Math.random()*10000)}`;
                            $('#metadata-container').append(`
                                <div class="row mb-2 metadata-field" data-id="${fieldId}">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" placeholder="Key" name="metadata_keys[]" value="${key}">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" placeholder="Value" name="metadata_values[]" value="${value}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-metadata" data-id="${fieldId}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `);
                        }
                    });
                }

                $('#settingModal').modal('show');
            });

            // Save setting
            $('#settingForm').submit(async function(e) {
                e.preventDefault();

                const id = $('#setting-id').val();
                const slug = $('#slug').val();

                // Collect metadata
                const metadata = {};
                // Collect from metadata-container
                $('#metadata-container .metadata-field').each(function() {
                    const key = $(this).find('input[name="metadata_keys[]"]').val();
                    const value = $(this).find('input[name="metadata_values[]"], select[name="metadata_values[]"]').val();
                    if (key && value) {
                        metadata[key] = value;
                    }
                });
                // Collect from system-mode-row if visible
                if (!$('#system-mode-row').hasClass('d-none')) {
                    const key = $('#system-mode-row').find('input[name="metadata_keys[]"]').val();
                    const value = $('#system-mode-row').find('select[name="metadata_values[]"]').val();
                    if (key && value) {
                        metadata[key] = value;
                    }
                }

                const data = {
                    slug: slug,
                    metadata: metadata
                };

                try {
                    let response;
                    const url = id ? `${apiUrl}/${id}` : apiUrl;
                    const method = id ? 'PUT' : 'POST';

                    response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        // Handle validation errors
                        if (result.errors) {
                            Object.entries(result.errors).forEach(([field, messages]) => {
                                $(`#${field}-error`).text(messages[0]).show();
                                $(`#${field}`).addClass('is-invalid');
                            });
                            throw new Error(result.message || 'Validation failed');
                        }
                        throw new Error(result.message || 'Failed to save setting');
                    }

                    showToast('success', result.message || 'Setting saved successfully');
                    $('#settingModal').modal('hide');
                    fetchSettings();
                } catch (error) {
                    showToast('error', error.message);
                }
            });

            // Delete setting
            $(document).on('click', '.delete-setting', async function() {
                if (confirm('Are you sure you want to delete this setting?')) {
                    const settingId = $(this).data('id');
                    try {
                        const response = await fetch(`${apiUrl}/${settingId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });

                        if (response.ok) {
                            showToast('success', 'Setting deleted successfully');
                            fetchSettings();
                        } else {
                            throw new Error('Failed to delete setting');
                        }
                    } catch (error) {
                        showToast('error', error.message);
                    }
                }
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchIcon = $('.filter-btn');
                searchIcon.addClass('ti-loader').removeClass("ti-search");
                await fetchSettings({ search: $('#search-input').val() });
                searchIcon.removeClass('ti-loader').addClass("ti-search");
            });

            // Initial load
            fetchSettings();
        });
    </script>
@endpush