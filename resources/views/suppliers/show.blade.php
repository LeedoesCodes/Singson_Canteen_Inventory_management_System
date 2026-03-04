@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="text-dark fw-bold mb-1">{{ $supplier->supplier_name }}</h3>
        <div class="text-muted">
            <i class="bi bi-upc-scan me-1"></i>Code: {{ $supplier->supplier_code }} | 
            <i class="bi bi-telephone me-1 ms-2"></i>{{ $supplier->contact_number }} |
            <i class="bi bi-envelope me-1 ms-2"></i>{{ $supplier->contact_email }}
        </div>
    </div>
    <div>
        <a class="btn btn-outline-secondary me-2" href="{{ route('suppliers.index') }}">
            <i class="bi bi-arrow-left me-1"></i>Back to Suppliers
        </a>
        <a class="btn btn-outline-primary" href="{{ route('suppliers.edit', $supplier) }}">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Products Supplied Card -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-boxes me-2"></i>Products Supplied
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Total Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($productTotals as $row)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $row->product_name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $row->product_code }}</small>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">{{ $row->total_quantity }} units</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">
                                    <i class="bi bi-box fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted mb-0">No products delivered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery History Card -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Delivery History
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Product</th>
                                <th class="text-end">Quantity</th>
                                <th>Delivery Ref</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($entries as $e)
                            <tr>
                                <td>
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    {{ $e->created_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $e->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $e->product->product_name ?? 'N/A' }}</span>
                                    <br>
                                    <small class="text-muted">{{ $e->product->product_code ?? '' }}</small>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success">{{ $e->quantity }} units</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $e->delivery_reference }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted mb-0">No delivery history yet.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection