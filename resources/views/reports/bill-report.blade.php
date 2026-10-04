@extends('reports.base', [
    'title' => 'Bills',
    'apiRoute' => url('api/v1/reports/bills'),
    'groupByOptions' => [
        'status' => 'Status',
        'payment_option' => 'Payment Option',
        'day' => 'Daily'
    ]
])

@push('scripts')
    <script>
        function displayReport(data) {
            if (!data || !data.grouped_data) {
                $('#reportResults').html('<div class="alert alert-warning">No data found</div>');
                return;
            }

            let html = '<div class="row">';

            Object.entries(data.grouped_data).forEach(([groupName, group]) => {
                html += `
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>${groupName}</h5>
                                <p class="mb-0">
                                    Total: ${formatCurrency(group.total_amount)} |
                                    Paid: ${formatCurrency(group.total_paid)}
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Control #</th>
                                                <th>Customer</th>
                                                <th class="text-end">Amount</th>
                                                <th class="text-end">Paid</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                group.bills.forEach(bill => {
                    const statusClass = {
                        'paid': 'bg-success text-white',
                        'pending': 'bg-warning',
                        'cancelled': 'bg-danger text-white',
                        'expired': 'bg-secondary text-white'
                    }[bill.status] || '';

                    html += `
                        <tr>
                            <td>${bill.control_number}</td>
                            <td>${bill.customer_name}</td>
                            <td class="text-end">${formatCurrency(bill.amount)}</td>
                            <td class="text-end">${formatCurrency(bill.paid_amount)}</td>
                            <td><span class="badge ${statusClass}">${bill.status}</span></td>
                        </tr>`;
                });

                html += `</tbody></table></div></div></div>`;
            });

            html += '</div>';
            $('#reportResults').html(html);
        }
    </script>
@endpush
