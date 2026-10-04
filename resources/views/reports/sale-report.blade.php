@extends('reports.base', [
    'title' => 'Sales',
    'apiRoute' => url('api/v1/reports/sales'),
    'groupByOptions' => [
        'day' => 'Daily',
        'week' => 'Weekly',
        'month' => 'Monthly',
        'payment_method' => 'Payment Method'
    ]
])

@push('scripts')
    <script>
        function displayReport(data) {
            if (!data || !data.grouped_data) {
                $('#reportResults').html('<div class="alert alert-warning">No data found</div>');
                return;
            }

            let html = `
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="salesChart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">`;

            Object.entries(data.grouped_data).forEach(([groupName, group]) => {
                html += `
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>${groupName}</h5>
                                <p class="mb-0">
                                    Total Sales: ${formatCurrency(group.total_amount)} |
                                    Average: ${formatCurrency(group.average_sale)}
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                group.sales.forEach(sale => {
                    html += `
                        <tr>
                            <td>${sale.invoice_number}</td>
                            <td>${sale.completed_at}</td>
                            <td class="text-end">${formatCurrency(sale.grand_total)}</td>
                        </tr>`;
                });

                html += `</tbody></table></div></div></div>`;
            });

            html += '</div>';
            $('#reportResults').html(html);

            // Initialize chart if Chart.js is available
            if (typeof Chart !== 'undefined') {
                initializeSalesChart(data);
            }
        }

        function initializeSalesChart(data) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const labels = Object.keys(data.grouped_data);
            const amounts = Object.values(data.grouped_data).map(g => g.total_amount);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales Amount',
                        data: amounts,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
