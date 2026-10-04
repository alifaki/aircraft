@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('reports.index') }}">Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-12 col-md-12">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2 px-4">
                    <h5 class="mb-0 text-white">{{ $title }} Report</h5>
                    <div>
                        <button class="btn btn-sm btn-light print-report me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Report">
                            <i class="ti ti-printer"></i>
                        </button>
                        <button class="btn btn-sm btn-light export-report" data-bs-toggle="tooltip" data-bs-placement="top" title="Export to Excel">
                            <i class="ti ti-download"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="reportForm">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="input-container">
                                    <input type="date" id="start_date" name="start_date" class="input-field form-control" value="{{ date('Y-m-01') }}" required>
                                    <label for="start_date" class="input-label">Start Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-container">
                                    <input type="date" id="end_date" name="end_date" class="input-field form-control" value="{{ date('Y-m-t') }}" required>
                                    <label for="end_date" class="input-label">End Date</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-container">
                                    <select id="branch_id" name="branch_id" class="input-field form-select">
                                        <option value="">All Branches</option>
                                    </select>
                                    <label for="branch_id" class="input-label">Branch</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-container">
                                    <select id="group_by" name="group_by" class="input-field form-select">
                                        @foreach($groupByOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <label for="group_by" class="input-label">Group By</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary" id="generateReportBtn">
                                    <i class="ti ti-report"></i> Generate Report
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="reportResults">
                                <div class="text-center text-muted py-5">
                                    <i class="ti ti-report fs-5"></i>
                                    <p>No report generated yet. Please select filters and click "Generate Report".</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const apiUrl = "{{ $apiRoute }}";
            const branchUrl = '/web/v1/branches';

            $('#reportForm').submit(function(e) {
                e.preventDefault();
                generateReport();
            });

            function generateReport() {
                $('#generateReportBtn').prop('disabled', true).html('<i class="ti ti-loader"></i> Generating...');

                $.ajax({
                    url: apiUrl,
                    type: "GET",
                    data: $('#reportForm').serialize(),
                    success: function(response) {
                        displayReport(response.data);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Failed to generate report');
                    },
                    complete: function() {
                        $('#generateReportBtn').prop('disabled', false).html('<i class="ti ti-report"></i> Generate Report');
                    }
                });
            }

            $('.print-report').click(function() {
                window.print();
            });

            $('.export-report').click(function() {
                toastr.info('Export to Excel feature will be implemented soon');
            });

            // Load initial data (users, branches, products)
            async function loadInitialData() {
                showLoader();
                try {
                    const [branchRes] = await Promise.all([
                        fetch(branchUrl)
                    ]);

                    const branches = await branchRes.json();
                    populateSelect('#branch_id', branches.data);

                } catch (error) {
                    toastr.error(`Failed to load initial data ${error}`);
                }
            }

            loadInitialData();
        });
    </script>
@endpush
