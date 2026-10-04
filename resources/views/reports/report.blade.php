@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <div class="mb-4 overflow-hidden position-relative">
        <div class="px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="#" class="fa fa-home"> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        @foreach([
            'cash-flow-report' => ['icon' => 'chart-line', 'color' => 'info', 'title' => 'Cash Flow'],
            'income-report' => ['icon' => 'arrow-down-circle', 'color' => 'success', 'title' => 'Income'],
            'expense-report' => ['icon' => 'arrow-up-circle', 'color' => 'danger', 'title' => 'Expense'],
            'sale-report' => ['icon' => 'shopping-cart', 'color' => 'warning', 'title' => 'Sales'],
            'product-report' => ['icon' => 'package', 'color' => 'primary', 'title' => 'Products'],
            'bill-report' => ['icon' => 'file-invoice', 'color' => 'secondary', 'title' => 'Bills']
        ] as $route => $data)
            <div class="col-xxl-4 col-md-6 mb-4">
                <a href="{{ url($route) }}" class="card text-decoration-none border-start border-{{ $data['color'] }} hover-shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                        <span class="text-{{ $data['color'] }} display-6 me-3">
                            <i class="ti ti-{{ $data['icon'] }}"></i>
                        </span>
                            <div>
                                <h5 class="mb-0">{{ $data['title'] }} Report</h5>
                                <p class="text-muted mb-0">View detailed {{ strtolower($data['title']) }} analysis</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection
