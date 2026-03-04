@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold">
        <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
    </h3>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small text-muted mb-1">Total Products</div>
                        <div class="fs-2 fw-bold text-primary">{{ $totalProducts }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-box fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small text-muted mb-1">Total Suppliers</div>
                        <div class="fs-2 fw-bold text-success">{{ $totalSuppliers }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-truck fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-info border-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small text-muted mb-1">Total Deliveries</div>
                        <div class="fs-2 fw-bold text-info">{{ $totalDeliveries }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-truck-flatbed fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small text-muted mb-1">Total Stock</div>
                        <div class="fs-2 fw-bold text-warning">{{ $totalCurrentStock }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-boxes fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Deliveries -->
<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-clock-history me-2"></i>
        Recent Deliveries
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th class="text-end">Quantity</th>
                        <th>Delivery Reference</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentDeliveries as $d)
                    <tr>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ $d->created_at->format('M d, Y') }}
                            <br>
                            <small class="text-muted">{{ $d->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $d->product->product_name ?? 'N/A' }}</span>
                            <br>
                            <small class="text-muted">{{ $d->product->product_code ?? '' }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $d->supplier->supplier_name ?? 'N/A' }}</span>
                            <br>
                            <small class="text-muted">{{ $d->supplier->supplier_code ?? '' }}</small>
                        </td>
                        <td class="text-end">
                            <span class="badge bg-success">{{ $d->quantity }} units</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $d->delivery_reference }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">No deliveries yet.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection