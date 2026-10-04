@extends('layouts.app')

@section('title', $page = 'Municipals')

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
                    <form id="municipalForm">
                        <div class="alert alert-info mb-3 p-2">
                            <small><i class="ti ti-info-circle me-1"></i> Fields marked with <span class="text-danger">*</span> are mandatory</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="municipal_name" name="municipal_name" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="municipal_name" class="input-label">
                                        <i class="ti ti-building-community me-1 fs-3 text-primary"></i>
                                        Municipal Name <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="input-container">
                                    <input type="text" id="region" name="region" class="input-field form-control controlled" placeholder=" " required>
                                    <label for="region" class="input-label">
                                        <i class="ti ti-map-pin me-1 fs-3 text-primary"></i>
                                        Region <span class="text-red"> *</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <span class="text-primary" id="form-progress"></span>
                                <span class="fa-pull-right">
                                    <button type="button" class="btn btn-sm btn-danger" data-for="municipalForm" id="reset-btn">
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
                        <button class="btn btn-sm btn-dark create-record me-1" data-for="municipalForm" data-bs-toggle="tooltip" data-bs-placement="top" title="Create record">
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
                                <input type="text" id="search-input" class="form-control" placeholder="Search municipals...">
                                <button class="btn btn-primary" type="button" id="search-btn">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="filter-region" class="form-select">
                                <option value="">All Regions</option>
                                <option value="Region 1">Region 1</option>
                                <option value="Region 2">Region 2</option>
                                <option value="Region 3">Region 3</option>
                                <!-- Regions will be loaded dynamically -->
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

    <!-- Parking Locations Modal -->
    <div class="modal fade" id="parkingLocationsModal" tabindex="-1" aria-labelledby="parkingLocationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="parkingLocationsModalLabel">Parking Locations for <span id="municipalTitle"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="parkingLocationsTable">
                            <thead>
                            <tr>
                                <th>Location Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- Parking locations will be loaded dynamically -->
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
            const apiUrl = '/web/v1/municipals';

            // Fetch municipals and populate the DataTable
            async function fetchMunicipals(filters = {}) {
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
                            title: "Municipal Name", 
                            data: "municipal_name",
                            render: function(data, type, row) {
                                return `<strong>${data}</strong>`;
                            }
                        },
                        { title: "Region", data: "region" },
                        {
                            title: "Parking Locations",
                            data: "parking_locations",
                            render: function(data, type, row) {
                                const count = data ? data.length : 0;
                                return `<button class="btn btn-sm btn-primary view-parking-locations-btn" data-municipal-id="${row.municipal_id}" data-municipal-name="${row.municipal_name}">View (${count})</button>`;
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
                                        <button class="btn btn-info show-detailed-info" data-label="${row.municipal_name}" data-browse='${JSON.stringify(data)}'>
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-warning update-record" data-browse='${JSON.stringify(data)}' data-for="municipalForm" data-url="${apiUrl}/${row.municipal_id}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-danger confirm-delete" data-label="${row.municipal_name}" data-url="${apiUrl}/${row.municipal_id}" data-bs-toggle="modal" data-bs-target="#confirm-delete">
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
                        emptyTable: "No municipal records available"
                    }
                });
            }

            // View parking locations button handler
            $(document).on('click', '.view-parking-locations-btn', async function() {
                const municipalId = $(this).data('municipal-id');
                const municipalName = $(this).data('municipal-name');
                $('#municipalTitle').text(municipalName);
                $('#parkingLocationsTable tbody').html('<tr><td colspan="3" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

                try {
                    const response = await fetch(`${apiUrl}/${municipalId}/parking-locations`);
                    const result = await response.json();

                    if (response.ok) {
                        let locationsHtml = '';
                        if (result.data && result.data.length > 0) {
                            result.data.forEach(location => {
                                locationsHtml += `
                                    <tr>
                                        <td>${location.location_name}</td>
                                        <td>${new Date(location.created_at).toLocaleDateString()}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="window.location.href='/parking-locations/${location.location_id}'">
                                                <i class="ti ti-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            locationsHtml = '<tr><td colspan="3" class="text-center">No parking locations found</td></tr>';
                        }
                        $('#parkingLocationsTable tbody').html(locationsHtml);
                        $('#parkingLocationsModal').modal('show');
                    } else {
                        toastr.error(result.message || 'Failed to load parking locations');
                    }
                } catch (error) {
                    toastr.error(error.message || 'An error occurred');
                }
            });

            const municipalForm = document.getElementById("municipalForm");
            const submitButton = municipalForm.querySelector("button[type='submit']");
            const progressMessage = document.getElementById("form-progress");
            const btnIcon = submitButton.querySelector("i");

            // Form Submit Event Listener
            municipalForm.addEventListener('submit', async function (event) {
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
                progressMessage.textContent = "Processing municipal data, please wait...";
                progressMessage.classList.remove("error-message");
                progressMessage.classList.add("text-primary");

                // Clear previous error messages
                document.querySelectorAll(".error-message").forEach(el => el.remove());

                const formData = new FormData(municipalForm);
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
                        progressMessage.textContent = res.message || (isUpdate ? "Municipal updated successfully!" : "Municipal created successfully!");
                        showToast('success', 'Success', res.message || 'Operation completed successfully');

                        // Refresh the table
                        await fetchMunicipals();

                        if(!isUpdate){
                            municipalForm.reset();
                            municipalForm.classList.remove('was-validated');
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
                await fetchMunicipals({search: searchTerm});
                searchIcom.removeClass('ti-loader', 'fa-spinner').addClass("ti-search");
            });

            // Filter by region
            $('#filter-region').change(function() {
                const region = $(this).val();
                fetchMunicipals({region: region});
            });

            // Reset filters
            $('#reset-filters').click(function() {
                $('#search-input').val('');
                $('#filter-region').val('');
                fetchMunicipals();
            });

            // Load regions for filter dropdown
            async function loadRegions() {
                try {
                    const response = await fetch(apiUrl);
                    const result = await response.json();

                    if (response.ok) {
                        const regions = [...new Set(result.data.map(item => item.region))];
                        const filterSelect = $('#filter-region');
                        filterSelect.empty().append('<option value="">All Regions</option>');
                        regions.forEach(region => {
                            if (region) {
                                filterSelect.append(`<option value="${region}">${region}</option>`);
                            }
                        });
                    }
                } catch (error) {
                    console.error("Failed to load regions:", error);
                }
            }

            // Initial load
            fetchMunicipals();
            loadRegions();
        });
    </script>
@endpush