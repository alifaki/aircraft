@extends('layouts.app')

@section('title', $page = 'Vehicle Types')

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

    <!-- Main Content Area -->
    <div class="row" id="detailed-data-info">
        <!-- Form Panel -->
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
                    <form id="vehicleTypeForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="type_name" name="type_name" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="type_name" class="input-label">
                                        <i class="ti ti-car me-1 fs-3 text-primary"></i>
                                        Vehicle Type Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="number" id="hourly_rate" name="hourly_rate" class="input-field form-control controlled" placeholder=" " step="0.01" min="0" required>
                                    <label for="hourly_rate" class="input-label">
                                        <i class="ti ti-currency-rupee me-1 fs-3 text-primary"></i>
                                        Hourly Rate (₹) <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="vehicleTypeForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="vehicleTypeForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search vehicle types...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="number" id="filter-min-rate" class="form-control" placeholder="Min Rate">
                        </div>
                        <div class="col-md-3">
                            <input type="number" id="filter-max-rate" class="form-control" placeholder="Max Rate">
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

    <!-- Parking Entries Modal -->
    <div class="modal fade" id="parkingEntriesModal" tabindex="-1" aria-labelledby="parkingEntriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="parkingEntriesModalLabel">Parking Entries for <span id="vehicleTypeTitle"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="parkingEntriesTable">
                            <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Location</th>
                                <th>Entry Time</th>
                                <th>Exit Time</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Parking entries will be loaded dynamically -->
                            </tbody>
                        </table>
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
            const tableElement = $('.dataTable');
            const apiUrl = '/web/v1/vehicle-types';

            // Fetch vehicle types and populate the DataTable
            async function fetchVehicleTypes(filters = {}) {
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
                            title: "Vehicle Type", 
                            data: "type_name",
                            render: function(data, type, row) {
                                return `<strong>${data}</strong>`;
                            }
                        },
                        {
                            title: "Hourly Rate",
                            data: "hourly_rate",
                            render: function(data) {
                                return `₹${parseFloat(data).toFixed(2)}`;
                            }
                        },
                        {
                            title: "Parking Entries",
                            data: "parking_entries",
                            render: function(data, type, row) {
                                const count = data ? data.length : 0;
                                const activeCount = data ? data.filter(entry => !entry.exit_time).length : 0;
                                return `
                                    <button class="btn btn-sm btn-primary view-parking-entries-btn" 
                                            data-vehicle-type-id="${row.vehicle_type_id}" 
                                            data-vehicle-type-name="${row.type_name}">
                                        View (${count})
                                    </button>
                                    ${activeCount > 0 ? `<span class="badge bg-success ms-1">${activeCount} Active</span>` : ''}
                                `;
                            }
                        },
                        {
                            title: "Created At",
                            data: "created_at",
                            render: function(data) {
                                return data ? new Date(data).toLocaleDateString() : 'N/A';
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
                                        <button class="btn btn-info show-detailed-info" data-label="${row.type_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="vehicleTypeForm" data-url="${apiUrl}/${row.vehicle_type_id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.type_name}" data-url="${apiUrl}/${row.vehicle_type_id}" data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No vehicle type records available"
                    }
                });
            }

            // View parking entries button handler
            $(document).on('click', '.view-parking-entries-btn', async function() {
                const vehicleTypeId = $(this).data('vehicle-type-id');
                const vehicleTypeName = $(this).data('vehicle-type-name');
                $('#vehicleTypeTitle').text(vehicleTypeName);
                
                await loadParkingEntries(vehicleTypeId);
                $('#parkingEntriesModal').modal('show');
            });

            async function loadParkingEntries(vehicleTypeId) {
                $('#parkingEntriesTable tbody').html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

                try {
                    const response = await fetch(`/api/vehicle-types/${vehicleTypeId}/parking-entries`);
                    const result = await response.json();

                    if (response.ok) {
                        let entriesHtml = '';
                        if (result.data && result.data.length > 0) {
                            result.data.forEach(entry => {
                                const isActive = !entry.exit_time;
                                const statusBadge = isActive ? 
                                    '<span class="badge bg-success">Active</span>' : 
                                    '<span class="badge bg-info">Completed</span>';
                                
                                const locationName = entry.location ? 
                                    `${entry.location.location_name} (${entry.location.municipal?.municipal_name || 'N/A'})` : 
                                    'N/A';
                                
                                entriesHtml += `
                                    <tr>
                                        <td><strong>${entry.plate_number}</strong></td>
                                        <td>${locationName}</td>
                                        <td>${new Date(entry.entry_time).toLocaleString()}</td>
                                        <td>${entry.exit_time ? new Date(entry.exit_time).toLocaleString() : '-'}</td>
                                        <td>${entry.total_amount ? '₹' + entry.total_amount : '-'}</td>
                                        <td>${statusBadge}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="window.location.href='/parking-entries/${entry.entry_id}'">
                                                <i class="ti ti-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            entriesHtml = '<tr><td colspan="7" class="text-center">No parking entries found</td></tr>';
                        }
                        $('#parkingEntriesTable tbody').html(entriesHtml);
                    } else {
                        toastr.error(result.message || 'Failed to load parking entries');
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                }
            }

            const vehicleTypeForm = document.getElementById("vehicleTypeForm");
            const submitButton = vehicleTypeForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            vehicleTypeForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                // Run validation before submitting
                let isValid = true;
                document.querySelectorAll(".controlled").forEach(input => {
                    input.style.borderColor = "";
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                // Additional validation for hourly rate
                const hourlyRate = document.getElementById('hourly_rate');
                if (hourlyRate.value <= 0) {
                    isValid = false;
                    hourlyRate.style.borderColor = "#dc3545";
                    showInputError(hourlyRate, "Hourly rate must be greater than 0");
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
                progressMessage.textContent = "Processing vehicle type data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(vehicleTypeForm);
                const data = Object.fromEntries(formData);
                data.hourly_rate = parseFloat(data.hourly_rate);

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
                        progressMessage.textContent = res.message || (isUpdate ? "Vehicle type updated successfully!" : "Vehicle type created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        // Refresh the table
                        await fetchVehicleTypes();

                        if(!isUpdate){
                            vehicleTypeForm.reset();
                            vehicleTypeForm.classList.remove('was-validated');
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
                await fetchVehicleTypes({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by rate range
            function applyRateFilters() {
                const minRate = $('#filter-min-rate').val();
                const maxRate = $('#filter-max-rate').val();
                
                let filters = {};
                if (minRate) filters.min_rate = minRate;
                if (maxRate) filters.max_rate = maxRate;
                
                fetchVehicleTypes(filters);
            }

            $('#filter-min-rate, #filter-max-rate').on('change', function() {
                applyRateFilters();
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-min-rate').val('');
                $('#filter-max-rate').val('');
                fetchVehicleTypes();
            });

            // Initial load
            fetchVehicleTypes();
        });
    </script>
@endpush