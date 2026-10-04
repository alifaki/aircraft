@extends('layouts.app')

@section('title', $page = 'Audit Trails')

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
    <!-- Similar structure to your departments.blade.php -->
    <div class="row" id="detailed-data-info">
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
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
                        <div class="col-md-3">
                            <select id="filter-model" class="form-select">
                                <option value="">All Models</option>
                                <!-- Models will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Action</th>
                                <th>Model</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Time</th>
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

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white">Audit Trail Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Old Values</h6>
                            <pre id="old-values" class="bg-light p-3"></pre>
                        </div>
                        <div class="col-md-6">
                            <h6>New Values</h6>
                            <pre id="new-values" class="bg-light p-3"></pre>
                        </div>
                    </div>
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
            const apiUrl = '/web/v1/audit-trails';
            const tableElement = $('.dataTable');

            // Fetch and initialize data
            async function fetchAuditTrails(filters = {}) {
                try {
                    showLoader();

                    const queryParams = new URLSearchParams(filters).toString();
                    const url = queryParams ? `${apiUrl}?${queryParams}` : apiUrl;

                    const response = await fetch(url);
                    const data = await response.json();

                    initializeDataTable(data);

                    // Populate model filter
                    const modelFilter = document.getElementById('filter-model');
                    if (modelFilter && data.model_types) {
                        data.model_types.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model;
                            option.textContent = model;
                            modelFilter.appendChild(option);
                        });
                    }
                } catch (error) {
                    showToast('error', error.message);
                } finally {
                    hideLoader();
                }
            }

            function initializeDataTable(res) {
                const data = res.data.content;
                console.log(data)
                tableElement.DataTable({
                    destroy: true,
                    data: data.data,
                    columns: [
                        { data: null, render: (data, type, row, meta) => meta.row + 1 },
                        { data: 'action' },
                        { data: 'model_type' },
                        {
                            data: 'user',
                            render: (user) => user ? user.staff.first_name+" "+user.staff.last_name : 'System'
                        },
                        { data: 'ip_address' },
                        {
                            data: 'created_at',
                            render: (date) => new Date(date).toLocaleString()
                        },
                        {
                            data: null,
                            render: (data) => `
                        <button class="btn btn-sm btn-info view-details"
                                data-old='${JSON.stringify(data.old_values)}'
                                data-new='${JSON.stringify(data.new_values)}'>
                            <i class="ti ti-eye"></i> Details
                        </button>
                    `
                        }
                    ]
                });
            }

            // View details handler
            $(document).on('click', '.view-details', function() {
                const oldValues = $(this).data('old');
                const newValues = $(this).data('new');

                $('#old-values').text(JSON.stringify(oldValues, null, 2));
                $('#new-values').text(JSON.stringify(newValues, null, 2));
                $('#detailsModal').modal('show');
            });

            // Search functionality
            $('#search-btn').click(async function () {
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchAuditTrails({search: $('#search-input').val()});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            $('#filter-model').change(() => {
                fetchAuditTrails({ model_type: $('#filter-model').val() });
            });

            // Initial load
            fetchAuditTrails();
        });
    </script>
@endpush
