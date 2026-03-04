@extends('layouts.app')

@section('content')
<h3 class="mb-3">Dashboard</h3>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Products</div>
                <div class="fs-3 fw-bold">{{ $totalProducts }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Suppliers</div>
                <div class="fs-3 fw-bold">{{ $totalSuppliers }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Deliveries (Stock Entries)</div>
                <div class="fs-3 fw-bold">{{ $totalDeliveries }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted">Total Current Stock</div>
                <div class="fs-3 fw-bold">{{ $totalCurrentStock }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Recent Deliveries</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Supplier</th>
                    <th class="text-end">Qty</th>
                    <th>Delivery Ref</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentDeliveries as $d)
                <tr>
                    <td>{{ $d->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $d->product->product_name ?? 'N/A' }}</td>
                    <td>{{ $d->supplier->supplier_name ?? 'N/A' }}</td>
                    <td class="text-end">{{ $d->quantity }}</td>
                    <td>{{ $d->delivery_reference }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center p-4">No deliveries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection