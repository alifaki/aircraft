@extends('reports.base', [
    'title' => 'Income',
    'apiRoute' => url('api/v1/reports/income'),
    'groupByOptions' => [
        'category' => 'Category',
        'payment_method' => 'Payment Method',
        'day' => 'Daily',
        'week' => 'Weekly'
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
                                <h5>${groupName} (Total: ${formatCurrency(group.total_amount)})</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                group.incomes.forEach(income => {
                    html += `
                        <tr>
                            <td>${income.date}</td>
                            <td>${income.description}</td>
                            <td class="text-end">${formatCurrency(income.amount)}</td>
                        </tr>`;
                });

                html += `</tbody></table></div></div></div>`;
            });

            html += '</div>';
            $('#reportResults').html(html);
        }
    </script>
@endpush
