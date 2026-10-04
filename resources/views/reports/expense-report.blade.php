@extends('reports.base', [
    'title' => 'Expense',
    'apiRoute' => url('api/v1/reports/expense'),
    'groupByOptions' => [
        'category' => 'Category',
        'payment_method' => 'Payment Method',
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

            let html = '<div class="accordion" id="expenseAccordion">';
            let index = 0;

            Object.entries(data.grouped_data).forEach(([groupName, group]) => {
                const accordionId = `expense-${index}`;
                html += `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-${accordionId}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-${accordionId}">
                                ${groupName} (Total: ${formatCurrency(group.total_amount)})
                            </button>
                        </h2>
                        <div id="collapse-${accordionId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}"
                            data-bs-parent="#expenseAccordion">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th class="text-end">Amount</th>
                                                <th>Recipient</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                group.expenditures.forEach(expense => {
                    html += `
                        <tr>
                            <td>${expense.date}</td>
                            <td>${expense.description}</td>
                            <td class="text-end">${formatCurrency(expense.amount)}</td>
                            <td>${expense.recipient_name}</td>
                        </tr>`;
                });

                html += `</tbody></table></div></div></div></div>`;
                index++;
            });

            html += '</div>';
            $('#reportResults').html(html);
        }
    </script>
@endpush
