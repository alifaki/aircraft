@extends('layouts.app')

@section('title', 'Parking Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
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
                    <small class="opacity-75">Currently parked vehicles</small>
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
                    <small class="opacity-75">Revenue generated today</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white">Monthly Revenue</h6>
                            <h3 class="text-white" id="monthly-revenue">TSh 0</h3>
                        </div>
                        <i class="ti ti-calendar-stats fs-1"></i>
                    </div>
                    <small class="opacity-75">This month's revenue</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white">Total Municipals</h6>
                            <h3 class="text-white" id="municipals-count">0</h3>
                        </div>
                        <i class="ti ti-building-community fs-1"></i>
                    </div>
                    <small class="opacity-75">Number of municipals</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <a href="{{ url('parking-entries') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="ti ti-car me-2"></i>Check-in Vehicle
                            </a>
                            <small>Register new parking</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="{{ url('parking-entries?status=active') }}" class="btn btn-warning btn-lg w-100 mb-2">
                                <i class="ti ti-logout me-2"></i>View Active Parkings
                            </a>
                            <small>Currently parked vehicles</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <button class="btn btn-success btn-lg w-100 mb-2 generate-report">
                                <i class="ti ti-report-analytics me-2"></i>Generate Report
                            </button>
                            <small>Generate parking reports</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <a href="{{ url('vehicle-types') }}" class="btn btn-info btn-lg w-100 mb-2">
                                <i class="ti ti-settings me-2"></i>Manage Rates
                            </a>
                            <small>Update hourly rates</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Parking Entries</h5>
                    <a href="{{ url('parking-entries') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="recent-entries">
                            <thead>
                                <tr>
                                    <th>Plate No.</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Recent entries will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top Parking Locations</h5>
                    <a href="{{ url('parking-locations') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div id="top-locations-chart">
                        <!-- Chart will be loaded here -->
                    </div>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="generate-report-btn">Generate</button>
                    <button type="button" class="btn btn-success btn-sm d-none" id="export-report-btn">Export to
                        Excel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function formatCurrency(amount) {
                return new Intl.NumberFormat('sw-TZ', {
                    style: 'currency',
                    currency: 'TZS'
                }).format(amount);
            }
            // Load dashboard statistics
            async function loadDashboardStats() {
                try {
                    const today = new Date().toISOString().split('T')[0];
                    const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                        .toISOString().split('T')[0];

                    // Get active parkings
                    const activeResponse = await fetch('/web/v1/parking-entries/active');
                    const activeResult = await activeResponse.json();
                    if (activeResponse.ok) {
                        $('#active-count').text(activeResult.data?.length || 0);
                    }

                    // Get municipals count
                    const municipalsResponse = await fetch('/web/v1/municipals');
                    const municipalsResult = await municipalsResponse.json();
                    if (municipalsResponse.ok) {
                        $('#municipals-count').text(municipalsResult.data?.length || 0);
                    }

                    // Get today's revenue
                    const todayResponse = await fetch(
                        `/api/parking-entries/reports?start_date=${today}&end_date=${today}&group_by=day`);
                    const todayResult = await todayResponse.json();
                    if (todayResponse.ok) {
                        const revenue = todayResult.data?.summary?.total_revenue || 0;
                        $('#today-revenue').text('₹' + parseFloat(revenue).toFixed(2));
                    }

                    // Get monthly revenue
                    const monthResponse = await fetch(
                        `/api/parking-entries/reports?start_date=${firstDayOfMonth}&end_date=${today}&group_by=month`
                        );
                    const monthResult = await monthResponse.json();
                    if (monthResponse.ok) {
                        const revenue = monthResult.data?.summary?.total_revenue || 0;
                        $('#monthly-revenue').text(formatCurrency(revenue));
                    }

                    // Load recent entries
                    await loadRecentEntries();

                    // Load top locations
                    await loadTopLocations();

                } catch (error) {
                    console.error("Failed to load dashboard stats:", error);
                }
            }

            // Load recent parking entries
            async function loadRecentEntries() {
                try {
                    const response = await fetch('/web/v1/parking-entries?limit=5');
                    const result = await response.json();

                    if (response.ok) {
                        let entriesHtml = '';
                        if (result.data && result.data.length > 0) {
                            result.data.forEach(entry => {
                                const isActive = !entry.exit_time;
                                const statusBadge = isActive ?
                                    '<span class="badge bg-success">Active</span>' :
                                    '<span class="badge bg-info">Completed</span>';

                                entriesHtml += `
                                    <tr>
                                        <td><strong>${entry.plate_number}</strong></td>
                                        <td>${entry.vehicle_type?.type_name || 'N/A'}</td>
                                        <td>${entry.location?.location_name || 'N/A'}</td>
                                        <td>${statusBadge}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            entriesHtml = '<tr><td colspan="4" class="text-center">No recent entries</td></tr>';
                        }
                        $('#recent-entries tbody').html(entriesHtml);
                    }
                } catch (error) {
                    console.error("Failed to load recent entries:", error);
                }
            }

            // Load top parking locations
            async function loadTopLocations() {
                try {
                    const today = new Date().toISOString().split('T')[0];
                    const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                        .toISOString().split('T')[0];

                    const response = await fetch(
                        `/api/parking-entries/reports?start_date=${firstDayOfMonth}&end_date=${today}&group_by=location`
                        );
                    const result = await response.json();

                    if (response.ok && result.data && result.data.reports && result.data.reports.length > 0) {
                        // Prepare data for chart
                        const locations = result.data.reports.slice(0, 5); // Top 5 locations

                        // Create simple bar chart using HTML/CSS
                        let chartHtml = '<div class="location-chart">';
                        locations.forEach(location => {
                            const maxEntries = Math.max(...locations.map(l => l.count || 0));
                            const percentage = maxEntries > 0 ? (location.count / maxEntries) * 100 : 0;

                            chartHtml += `
                                <div class="location-item mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>${location.location?.location_name || 'Unknown'}</span>
                                        <span class="text-muted">${location.count} entries</span>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: ${percentage}%" 
                                             aria-valuenow="${location.count}" aria-valuemin="0" aria-valuemax="${maxEntries}">
                                            ₹${parseFloat(location.revenue || 0).toFixed(2)}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        chartHtml += '</div>';

                        $('#top-locations-chart').html(chartHtml);
                    } else {
                        $('#top-locations-chart').html(
                            '<p class="text-center text-muted">No data available</p>');
                    }
                } catch (error) {
                    console.error("Failed to load top locations:", error);
                    $('#top-locations-chart').html('<p class="text-center text-muted">Failed to load data</p>');
                }
            }

            // Generate report functionality
            $('.generate-report').click(function() {
                // Set default dates
                const today = new Date().toISOString().split('T')[0];
                const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
                    .toISOString().split('T')[0];
                $('#report-start-date').val(firstDayOfMonth);
                $('#report-end-date').val(today);

                $('#reportModal').modal('show');
            });

            $('#generate-report-btn').click(async function() {
                const startDate = $('#report-start-date').val();
                const endDate = $('#report-end-date').val();
                const groupBy = $('#report-group-by').val();

                if (!startDate || !endDate) {
                    toastr.error('Please select start and end dates');
                    return;
                }

                try {
                    const response = await fetch(
                        `/api/parking-entries/reports?start_date=${startDate}&end_date=${endDate}&group_by=${groupBy}`
                        );
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

                let reportHtml = '';
                reports.forEach(report => {
                    let period = report.period;
                    if (groupBy === 'day' && report.period) {
                        period = new Date(report.period).toLocaleDateString();
                    } else if (groupBy === 'location') {
                        period = report.location?.location_name || 'Unknown';
                    } else if (groupBy === 'vehicle_type') {
                        period = report.vehicle_type?.type_name || 'Unknown';
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
                $('#report-results').removeClass('d-none');
            }

            // Auto-refresh dashboard every 60 seconds
            setInterval(() => {
                loadDashboardStats();
            }, 60000);

            // Initial load
            loadDashboardStats();
        });
    </script>
@endpush
