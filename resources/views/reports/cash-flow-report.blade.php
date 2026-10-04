@extends('reports.base', [
    'title' => 'Cash Flow',
    'apiRoute' => url('api/v1/reports/cash-flow'),
    'groupByOptions' => [
        'day' => 'Daily',
        'week' => 'Weekly',
        'month' => 'Monthly',
        'year' => 'Yearly'
    ]
])

@push('scripts')
    <script>
        function displayReport(data) {
            if (!data || !data.summary || !data.transactions) {
                $('#reportResults').html('<div class="alert alert-warning">No data found</div>');
                return;
            }

            let html = `
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Income</h5>
                                        <p class="card-text h3">${formatCurrency(data.summary.total_income)}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Expenses</h5>
                                        <p class="card-text h3">${formatCurrency(data.summary.total_expenses)}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Net Cash Flow</h5>
                                        <p class="card-text h3">${formatCurrency(data.summary.net_cash_flow)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Transactions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Total Amount</th>
                                        <th>Transactions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            Object.entries(data.transactions).forEach(([period, group]) => {
                html += `
                    <tr>
                        <td>${period}</td>
                        <td>${formatCurrency(group.total_amount)}</td>
                        <td>${group.transactions.length}</td>
                    </tr>`;
            });

            html += `</tbody></table></div></div></div>`;
            $('#reportResults').html(html);
        }
    </script>
@endpush
