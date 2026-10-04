@extends('layouts.app')

@section('title', $page = 'Payments')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="fa fa-home"> Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Manage {{$page}}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Search Panel -->
    <div class="row">
        <div class="col-xxl-12 col-md-12">
            <div class="card border-bottom border-info">
                <div class="card-header bg-primary text-white py-2 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Search Vehicle Dept.</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="plate-number" class="form-control" placeholder="Enter Plate Number">
                                <button class="btn btn-primary" type="button" id="search-btn" data-bs-toggle="tooltip" title="Search">
                                    <i class="ti ti-search filter-btn"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary btn-sm" id="reset-btn" data-bs-toggle="tooltip" title="Reset Table">Reset</button>
                        </div>
                        <div class="col-md-5 text-end">
                            <strong>Total Selected Amount: </strong>
                            <span id="total-amount">0</span> 
                            <button class="btn btn-success btn-sm ms-2" id="pay-btn" disabled data-bs-toggle="tooltip" title="Pay selected entries">Pay</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="payments-table">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="select-all" data-bs-toggle="tooltip" title="Select all entries"></th>
                                    <th>Receipt #</th>
                                    <th>Collection Officer</th>
                                    <th>Plate Number</th>
                                    <th>Vehicle Type</th>
                                    <th>Duration (hrs)</th>
                                    <th>Location</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="9" class="text-center">Enter a plate number to search...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="paymentConfirmModal" tabindex="-1" aria-labelledby="paymentConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentConfirmModalLabel">Confirm Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="confirm-table">
                            <thead>
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Plate Number</th>
                                    <th>Vehicle Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th id="confirm-total">0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success btn-sm" id="confirm-pay-btn">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = $('#payments-table tbody');
    const searchBtn = $('#search-btn');
    const plateInput = $('#plate-number');
    const totalAmountSpan = $('#total-amount');
    const payBtn = $('#pay-btn');
    const selectAllCheckbox = $('#select-all');

    let paymentsData = [];

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

    async function fetchPayments(plate) {
        try {
            tableBody.html('<tr><td colspan="9" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

            const response = await fetch(`/web/v1/payments/search-plate?plate_number=${plate}`, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const data = await response.json();
            paymentsData = data;
            renderTable(data);
        } catch (error) {
            tableBody.html('<tr><td colspan="9" class="text-center text-danger">Error fetching data</td></tr>');
            console.error(error);
        }
    }

    function renderTable(data) {
        if (data.length === 0) {
            tableBody.html('<tr><td colspan="9" class="text-center">No records found</td></tr>');
            totalAmountSpan.text('0');
            payBtn.prop('disabled', true);
            return;
        }

        let html = '';
        data.forEach(entry => {
            html += `
                <tr>
                    <td><input type="checkbox" class="select-entry" data-amount="${entry.amount}" data-id="${entry.id}" data-receipt="${entry.receiptNumber}" data-plate="${entry.plateNumber}" data-type="${entry.vehicleType}"></td>
                    <td>${entry.receiptNumber}</td>
                    <td>${entry.collectionOfficer}</td>
                    <td>${entry.plateNumber ?? 'N/A'}</td>
                    <td>${entry.vehicleType ?? 'N/A'}</td>
                    <td>${entry.duration}</td>
                    <td>${entry.location}</td>
                    <td>${entry.amount.toFixed(2)}</td>
                    <td>${entry.status}</td>
                </tr>
            `;
        });
        tableBody.html(html);
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        $('.select-entry:checked').each(function () { total += parseFloat($(this).data('amount')); });
        totalAmountSpan.text(total.toFixed(2));
        payBtn.prop('disabled', total === 0);
    }

    searchBtn.click(() => fetchPayments(plateInput.val()));
    plateInput.keypress(e => { if(e.key === 'Enter') fetchPayments(plateInput.val()); });
    $('#reset-btn').click(() => {
        plateInput.val(''); 
        tableBody.html('<tr><td colspan="9" class="text-center">Enter a plate number to search...</td></tr>'); 
        totalAmountSpan.text('0'); 
        payBtn.prop('disabled', true); 
        selectAllCheckbox.prop('checked', false);
    });
    $(document).on('change', '.select-entry', updateTotal);
    selectAllCheckbox.change(function () { $('.select-entry').prop('checked', $(this).prop('checked')); updateTotal(); });

    // Pay button click opens confirmation modal
    payBtn.click(() => {
        const selected = $('.select-entry:checked');
        if(selected.length === 0) return;

        const tbody = $('#confirm-table tbody');
        tbody.html('');
        let total = 0;
        selected.each(function() {
            const amount = parseFloat($(this).data('amount'));
            total += amount;
            tbody.append(`<tr>
                <td>${$(this).data('receipt')}</td>
                <td>${$(this).data('plate')}</td>
                <td>${$(this).data('type')}</td>
                <td>${amount.toFixed(2)}</td>
            </tr>`);
        });
        $('#confirm-total').text(total.toFixed(2));
        new bootstrap.Modal(document.getElementById('paymentConfirmModal')).show();
    });

    // Confirm modal pay button
    $('#confirm-pay-btn').click(async function() {
        const selectedIds = $('.select-entry:checked').map(function () { return $(this).data('id'); }).get();
        if(selectedIds.length === 0) return;

        try {
            const response = await fetch(`/web/v1/payments/pay-selected`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ entry_ids: selectedIds })
            });
            const res = await response.json();
            showToast('success', 'Payment Processed', res.message || 'Payment processed successfully');
            fetchPayments(plateInput.val());
            bootstrap.Modal.getInstance(document.getElementById('paymentConfirmModal')).hide();
        } catch (error) {
            showToast('error', 'Error', 'Error processing payment');
            console.error(error);
        }
    });
});
</script>
@endpush
