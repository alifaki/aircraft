@extends('reports.base', [
    'title' => 'Products',
    'apiRoute' => url('api/v1/reports/products'),
    'groupByOptions' => [
        'category' => 'Category',
        'brand' => 'Brand',
        'stock_level' => 'Stock Level'
    ]
])

@push('scripts')
    <script>
        function displayReport(data) {
            if (!data || !data.products) {
                $('#reportResults').html('<div class="alert alert-warning">No data found</div>');
                return;
            }

            let html = `
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Inventory Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>${data.total_products}</h3>
                                        <p class="mb-0">Total Products</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>${formatCurrency(data.total_stock_value)}</h3>
                                        <p class="mb-0">Inventory Value</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>${data.low_stock_items}</h3>
                                        <p class="mb-0">Low Stock Items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>${data.out_of_stock_items}</h3>
                                        <p class="mb-0">Out of Stock</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Product Details</h5>
                        <input type="text" id="productSearch" class="form-control form-control-sm w-25" placeholder="Search products...">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Stock</th>
                                        <th class="text-end">Cost Price</th>
                                        <th class="text-end">Selling Price</th>
                                        <th class="text-end">Value</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            data.products.forEach(product => {
                const stockClass = product.quantity <= 0 ? 'bg-danger text-white' :
                    product.quantity <= product.reorder_level ? 'bg-warning' : '';

                html += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${product.sku}</td>
                        <td class="${stockClass}">${product.quantity}</td>
                        <td class="text-end">${formatCurrency(product.cost_price)}</td>
                        <td class="text-end">${formatCurrency(product.selling_price)}</td>
                        <td class="text-end">${formatCurrency(product.stock_value)}</td>
                    </tr>`;
            });

            html += `</tbody></table></div></div></div>`;
            $('#reportResults').html(html);

            // Add search functionality
            $('#productSearch').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#productsTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        }
    </script>
@endpush
