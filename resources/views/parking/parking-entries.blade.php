@extends('layouts.app')

@section('title', $page = 'Parking Entries')

@section('content')
    <!-- Breadcrumb -->
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

    <!-- Detailed View Panel -->
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
                    <!-- Detailed info will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Stats Panel -->
    <div class="col-xxl-12 col-md-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white">Active Parkings</h6>
                                <h3 class="text-white" id="active-count">0</h3>
                            </div>
                            <i class="ti ti-car fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white">Today's Revenue</h6>
                                <h3 class="text-white" id="today-revenue">₹0</h3>
                            </div>
                            <i class="ti ti-currency-rupee fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white">Monthly Revenue</h6>
                                <h3 class="text-white" id="monthly-revenue">₹0</h3>
                            </div>
                            <i class="ti ti-calendar-stats fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white">Total Entries</h6>
                                <h3 class="text-white" id="total-entries">0</h3>
                            </div>
                            <i class="ti ti-list fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area -->
    <div class="row" id="detailed-data-info">
        <!-- Form Panel -->
        <div class="col-xxl-5 col-md-5 visually-hidden form-input">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white"><span class="btn-label">Create</span> {{$page}} (Check-in)</h5>
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
                    <form id="parkingEntryForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="vehicle_type_id" name="vehicle_type_id" class="input-field form-select controlled" required>
                                        <option value="">Select Vehicle Type</option>
                                        <!-- Vehicle types will be loaded dynamically -->
                                    </select>
                                    <label for="vehicle_type_id" class="input-label">
                                        <i class="ti ti-car me-1 fs-3 text-primary"></i>
                                        Vehicle Type <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="plate_number" name="plate_number" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="plate_number" class="input-label">
                                        <i class="ti ti-number me-1 fs-3 text-primary"></i>
                                        Plate Number <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="tel" id="phone_number" name="phone_number" class="input-field form-control" placeholder=" ">
                                    <label for="phone_number" class="input-label">
                                        <i class="ti ti-phone me-1 fs-3 text-primary"></i>
                                        Phone Number
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="location_id" name="location_id" class="input-field form-select controlled" required>
                                        <option value="">Select Parking Location</option>
                                        <!-- Parking locations will be loaded dynamically -->
                                    </select>
                                    <label for="location_id" class="input-label">
                                        <i class="ti ti-map-pin me-1 fs-3 text-primary"></i>
                                        Parking Location <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <select id="officer_id" name="officer_id" class="input-field form-select controlled" required>
                                        <option value="">Select Officer</option>
                                        <!-- Officers will be loaded dynamically -->
                                    </select>
                                    <label for="officer_id" class="input-label">
                                        <i class="ti ti-user me-1 fs-3 text-primary"></i>
                                        Officer <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="parkingEntryForm" id="reset-btn">
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
        <!-- List Panel -->
        <div class="col-xxl-12 col-md-12 detailed-info">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">List of {{$page}}</h5>
                    <div>
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="parkingEntryForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
                            <i class="ti ti-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-light print-details me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                            <i class="ti ti-printer"></i>
                        </button>
                        <button class="btn btn-sm btn-success generate-report me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Report">
                            <i class="ti ti-report-analytics"></i>
                        </button>
                        <button class="btn btn-sm btn-light expand-details resize-details visually-hidden" data-bs-toggle="tooltip" data-bs-placement="top" title="Resize">
                            <i class="ti ti-resize"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" id="search-input" class="form-control" placeholder="Search plate number...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-vehicle-type" class="form-select">
                                <option value="">All Vehicle Types</option>
                                <!-- Vehicle types will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-location" class="form-select">
                                <option value="">All Locations</option>
                                <!-- Locations will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="filter-date" class="form-control">
                        </div>
                        <div class="col-md-1">
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

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title text-white" id="checkoutModalLabel">Checkout Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to checkout vehicle <strong id="checkout-plate-number"></strong>?</p>
                    <div class="alert alert-info">
                        <small><i class="ti ti-info-circle me-1"></i> Total amount will be calculated based on entry time and hourly rate.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning btn-sm" id="confirm-checkout">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="reportModalLabel">Generate Parking Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="report-start-date" class="form-label">Start Date *</label>
                                <input type="date" id="report-start-date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="report-end-date" class="form-label">End Date *</label>
                                <input type="date" id="report-end-date" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="report-group-by" class="form-label">Group By</label>
                                <select id="report-group-by" class="form-select">
                                    <option value="day">Daily</option>
                                    <option value="week">Weekly</option>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                    <option value="location">By Location</option>
                                    <option value="vehicle_type">By Vehicle Type</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-3 d-none" id="report-results">
                        <table class="table table-striped" id="reportTable">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Entries</th>
                                    <th>Revenue</th>
                                    <th>Avg/Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Report results will be loaded here -->
                            </tbody>
                        </table>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6>Summary</h6>
                                    <p id="report-summary"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="generate-report-btn">Generate</button>
                    <button type="button" class="btn btn-success btn-sm d-none" id="export-report-btn">Export to Excel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/parking-entries';
            const vehicleTypesUrl = '/web/v1/vehicle-types';
            const parkingLocationsUrl = '/web/v1/parking-locations';
            const usersUrl = '/web/v1/users'; // Assuming you have users endpoint

            let currentCheckoutEntryId = null;

            // Load dynamic data for dropdowns
            async function loadDropdownData() {
                try {
                    // Load vehicle types
                    const vehicleTypesResponse = await fetch(vehicleTypesUrl);
                    const vehicleTypesResult = await vehicleTypesResponse.json();
                    
                    if (vehicleTypesResponse.ok) {
                        populateSelect('#vehicle_type_id', vehicleTypesResult.data, 'vehicle_type_id', 'type_name');
                        populateSelect('#filter-vehicle-type', vehicleTypesResult.data, 'vehicle_type_id', 'type_name', 'All Vehicle Types');
                    }

                    // Load parking locations
                    const locationsResponse = await fetch(parkingLocationsUrl);
                    const locationsResult = await locationsResponse.json();
                    
                    if (locationsResponse.ok) {
                        populateSelect('#location_id', locationsResult.data, 'location_id', 'location_name');
                        populateSelect('#filter-location', locationsResult.data, 'location_id', 'location_name', 'All Locations');
                    }

                    // Load users (officers)
                    // Note: You might want to filter only users with officer role
                    const usersResponse = await fetch(usersUrl);
                    const usersResult = await usersResponse.json();
                    
                    if (usersResponse.ok) {
                        populateSelect('#officer_id', usersResult.data, 'id', 'name');
                    }

                } catch (error) {
                    toastr.error("Failed to load dropdown data");
                }
            }

            // Load dashboard statistics
            async function loadDashboardStats() {
                try {
                    const today = new Date().toISOString().split('T')[0];
                    const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
                    
                    // Get active parkings
                    const activeResponse = await fetch(`${apiUrl}/active`);
                    const activeResult = await activeResponse.json();
                    if (activeResponse.ok) {
                        $('#active-count').text(activeResult.data?.length || 0);
                    }

                    // Get total entries count
                    const allResponse = await fetch(apiUrl);
                    const allResult = await allResponse.json();
                    if (allResponse.ok) {
                        $('#total-entries').text(allResult.data?.length || 0);
                    }

                    // Get today's revenue
                    const todayResponse = await fetch(`${apiUrl}/reports?start_date=${today}&end_date=${today}&group_by=day`);
                    const todayResult = await todayResponse.json();
                    if (todayResponse.ok) {
                        const revenue = todayResult.data?.summary?.total_revenue || 0;
                        $('#today-revenue').text('₹' + parseFloat(revenue).toFixed(2));
                    }

                    // Get monthly revenue
                    const monthResponse = await fetch(`${apiUrl}/reports?start_date=${firstDayOfMonth}&end_date=${today}&group_by=month`);
                    const monthResult = await monthResponse.json();
                    if (monthResponse.ok) {
                        const revenue = monthResult.data?.summary?.total_revenue || 0;
                        $('#monthly-revenue').text('₹' + parseFloat(revenue).toFixed(2));
                    }

                } catch (error) {
                    console.error("Failed to load dashboard stats:", error);
                }
            }

            // Fetch parking entries and populate the DataTable
            async function fetchParkingEntries(filters = {}) {
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
                        { 
                            title: "Plate Number", 
                            data: "plate_number",
                            render: function(data, type, row) {
                                return `<strong>${data}</strong>`;
                            }
                        },
                        {
                            title: "Vehicle Type",
                            data: "vehicle_type",
                            render: function(data) {
                                return data ? `${data.type_name} (${formatCurrency(data.hourly_rate)}/hr)` : 'N/A';
                            }
                        },
                        {
                            title: "Location",
                            data: "location",
                            render: function(data) {
                                if (data && data.municipal) {
                                    return `${data.location_name}<br><small class="text-muted">${data.municipal.municipal_name}</small>`;
                                }
                                return data ? data.location_name : 'N/A';
                            }
                        },
                        {
                            title: "Entry Time",
                            data: "entry_time",
                            render: function(data) {
                                return data ? new Date(data).toLocaleString() : 'N/A';
                            }
                        },
                        {
                            title: "Exit Time",
                            data: "exit_time",
                            render: function(data) {
                                return data ? new Date(data).toLocaleString() : '-';
                            }
                        },
                        {
                            title: "Duration",
                            data: null,
                            render: function(data) {
                                if (!data.entry_time) return 'N/A';
                                const entryTime = new Date(data.entry_time);
                                const exitTime = data.exit_time ? new Date(data.exit_time) : new Date();
                                const diffMs = exitTime - entryTime;
                                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                                return `${diffHours}h ${diffMinutes}m`;
                            }
                        },
                        {
                            title: "Amount",
                            data: "total_amount",
                            render: function(data, type, row) {
                                if (data) {
                                    return `<strong class="text-success">${formatCurrency(data)}</strong>`;
                                } else if (!row.exit_time) {
                                    const entryTime = new Date(row.entry_time);
                                    const currentTime = new Date();
                                    const diffHours = Math.ceil((currentTime - entryTime) / (1000 * 60 * 60));
                                    const rate = row.rate_per_hour || row.vehicle_type?.hourly_rate || 0;
                                    const estimated = diffHours * rate;
                                    return `<span class="text-warning">${formatCurrency(estimated)} (est.)</span>`;
                                }
                                return '-';
                            }
                        },
                        {
                            title: "Status",
                            data: "exit_time",
                            render: function(data, type, row) {
                                const isActive = !data;
                                const badgeClass = isActive ? 'badge bg-success' : 'badge bg-info';
                                const statusText = isActive ? 'Active' : 'Completed';
                                return `<span class="${badgeClass}">${statusText}</span>`;
                            }
                        },
                        {
                            title: "Officer",
                            data: "officer",
                            render: function(officer) {
                                return officer ? officer.staffs.first_name : 'N/A';
                            }
                        },
                        {
                            title: "Actions",
                            data: null,
                            className: "text-center",
                            orderable: false,
                            render: function (data, type, row) {
                                const isActive = !row.exit_time;
                                const checkoutBtn = isActive ? `
                                    <button class="btn btn-sm btn-warning checkout-btn" data-entry-id="${row.entry_id}" data-plate-number="${row.plate_number}">
                                        <i class="ti ti-logout"></i>
                                    </button>
                                ` : '';
                                
                                return `
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info show-detailed-info" data-label="${row.plate_number}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="parkingEntryForm" data-url="${apiUrl}/${row.entry_id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        ${checkoutBtn}
                                        <button class="btn btn-danger confirm-delete" data-label="${row.plate_number}" data-url="${apiUrl}/${row.entry_id}" data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No parking entry records available"
                    },
                    order: [[4, 'desc']] // Sort by entry time descending by default
                });
            }

            // Checkout button handler
            $(document).on('click', '.checkout-btn', function() {
                const entryId = $(this).data('entry-id');
                const plateNumber = $(this).data('plate-number');
                currentCheckoutEntryId = entryId;
                $('#checkout-plate-number').text(plateNumber);
                $('#checkoutModal').modal('show');
            });

            // Confirm checkout
            $('#confirm-checkout').click(async function() {
                if (!currentCheckoutEntryId) return;

                try {
                    const response = await fetch(`${apiUrl}/${currentCheckoutEntryId}/checkout`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toastr.success('Vehicle checked out successfully');
                        $('#checkoutModal').modal('hide');
                        // Refresh data
                        await fetchParkingEntries();
                        await loadDashboardStats();
                    } else {
                        toastr.error(result.message || 'Failed to checkout');
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                } finally {
                    currentCheckoutEntryId = null;
                }
            });

            // Generate report
            $('.generate-report').click(function() {
                $('#reportModal').modal('show');
            });

            // Generate report button
            $('#generate-report-btn').click(async function() {
                const startDate = $('#report-start-date').val();
                const endDate = $('#report-end-date').val();
                const groupBy = $('#report-group-by').val();

                if (!startDate || !endDate) {
                    toastr.error('Please select start and end dates');
                    return;
                }

                try {
                    const response = await fetch(`${apiUrl}/reports?start_date=${startDate}&end_date=${endDate}&group_by=${groupBy}`);
                    const result = await response.json();

                    if (response.ok) {
                        displayReportResults(result.data);
                        $('#export-report-btn').removeClass('d-none');
                    } else {
                        toastr.error(result.message || 'Failed to generate report');
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                }
            });

            function displayReportResults(data) {
                const reports = data.reports || [];
                const summary = data.summary || {};
                
                let reportHtml = '';
                reports.forEach(report => {
                    let period = report.period;
                    if (groupBy === 'day' && report.period) {
                        period = new Date(report.period).toLocaleDateString();
                    }
                    
                    reportHtml += `
                        <tr>
                            <td>${period || 'N/A'}</td>
                            <td>${report.count || 0}</td>
                            <td>₹${parseFloat(report.revenue || 0).toFixed(2)}</td>
                            <td>₹${parseFloat(report.revenue / report.count || 0).toFixed(2)}</td>
                        </tr>
                    `;
                });

                $('#reportTable tbody').html(reportHtml);
                
                // Update summary
                const summaryHtml = `
                    Total Entries: <strong>${summary.total_entries || 0}</strong><br>
                    Total Revenue: <strong>₹${parseFloat(summary.total_revenue || 0).toFixed(2)}</strong><br>
                    Average per Entry: <strong>₹${parseFloat(summary.average_per_entry || 0).toFixed(2)}</strong>
                `;
                $('#report-summary').html(summaryHtml);
                
                $('#report-results').removeClass('d-none');
            }

            // Export report to Excel
            $('#export-report-btn').click(function() {
                toastr.info('Export feature coming soon!');
                // Implement Excel export functionality here
            });

            const parkingEntryForm = document.getElementById("parkingEntryForm");
            const submitButton = parkingEntryForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            parkingEntryForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                // Run validation before submitting
                let isValid = true;
                document.querySelectorAll(".controlled").forEach(input => {
                    input.style.borderColor = "";
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                // Validate phone number format if provided
                const phoneNumber = document.getElementById('phone_number');
                if (phoneNumber.value && !/^[0-9]{10}$/.test(phoneNumber.value)) {
                    isValid = false;
                    phoneNumber.style.borderColor = "#dc3545";
                    showInputError(phoneNumber, "Please enter a valid 10-digit phone number");
                }

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
                progressMessage.textContent = "Processing parking entry data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(parkingEntryForm);
                const data = Object.fromEntries(formData);

                const dataUrl = submitButton.getAttribute("data-url")?.trim();
                const isUpdate = !!dataUrl;

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
                        progressMessage.classList.remove("error-message");
                        progressMessage.classList.add("text-primary");
                        progressMessage.textContent = res.message || (isUpdate ? "Parking entry updated successfully!" : "Vehicle checked in successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        // Refresh the table and stats
                        await fetchParkingEntries();
                        await loadDashboardStats();

                        if(!isUpdate){
                            parkingEntryForm.reset();
                            parkingEntryForm.classList.remove('was-validated');
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
                const searchTerm = $('#search-input').val();
                const searchIcom = $('.filter-btn');
                searchIcom.addClass('ti-loader', 'fa-spinner').removeClass("ti-search");
                await fetchParkingEntries({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by status
            $('#filter-status').change(function() {
                const status = $(this).val();
                fetchParkingEntries({status: status});
            });

            // Filter by vehicle type
            $('#filter-vehicle-type').change(function() {
                const vehicleTypeId = $(this).val();
                fetchParkingEntries({vehicle_type_id: vehicleTypeId});
            });

            // Filter by location
            $('#filter-location').change(function() {
                const locationId = $(this).val();
                fetchParkingEntries({location_id: locationId});
            });

            // Filter by date
            $('#filter-date').change(function() {
                const date = $(this).val();
                if (date) {
                    fetchParkingEntries({date_from: date, date_to: date});
                }
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-status').val('');
                $('#filter-vehicle-type').val('');
                $('#filter-location').val('');
                $('#filter-date').val('');
                fetchParkingEntries();
            });

            // Set default dates for report
            const today = new Date().toISOString().split('T')[0];
            const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
            $('#report-start-date').val(firstDayOfMonth);
            $('#report-end-date').val(today);
            $('#filter-date').val(today);

            // Initial load
            loadDropdownData().then(() => {
                fetchParkingEntries();
                loadDashboardStats();
            });
        });
    </script>
@endpush