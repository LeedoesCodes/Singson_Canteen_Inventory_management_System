@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="text-dark fw-bold mb-1">{{ $product->product_name }}</h3>
        <div class="text-muted">
            <i class="bi bi-upc-scan me-1"></i>Code: {{ $product->product_code }}
        </div>
    </div>
    <div>
        <a class="btn btn-outline-secondary me-2" href="{{ route('products.index') }}">
            <i class="bi bi-arrow-left me-1"></i>Back to Products
        </a>
        <a class="btn btn-outline-primary" href="{{ route('products.edit', $product) }}">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column -->
    <div class="col-md-4">
        <!-- Product Info Card -->
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Product Information
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Price:</span>
                    <span class="fw-bold fs-5 text-primary">${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Current Stock:</span>
                    <span class="badge {{ $product->current_stock > 10 ? 'bg-success' : ($product->current_stock > 0 ? 'bg-warning' : 'bg-danger') }} fs-6">
                        {{ $product->current_stock }} units
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Delivered:</span>
                    <span class="fw-bold">{{ $totalDelivered }} units</span>
                </div>
            </div>
        </div>

        <!-- Add Stock Entry Card -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2 text-success"></i>Add Stock Entry
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock-entries.store') }}" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-truck me-1"></i>Supplier
                        </label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="" selected disabled>-- Select a supplier --</option>
                            @foreach(\App\Models\Supplier::orderBy('supplier_name')->get() as $sup)
                                <option value="{{ $sup->id }}">
                                    {{ $sup->supplier_name }} ({{ $sup->supplier_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-boxes me-1"></i>Quantity
                        </label>
                        <input type="number" name="quantity" min="1" class="form-control" 
                               placeholder="Enter quantity" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-upc-scan me-1"></i>Delivery Reference
                        </label>
                        <input name="delivery_reference" class="form-control" 
                               placeholder="e.g., INV-2024-001" required>
                        <small class="text-muted">Must be unique</small>
                    </div>

                    <button class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>Add Stock Entry
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-8">
        <!-- Suppliers Card -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>Suppliers
            </div>
            <div class="card-body">
                @if($suppliers->isEmpty())
                    <div class="text-center py-4">
                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">No suppliers yet for this product.</p>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($suppliers as $s)
                            <a href="{{ route('suppliers.show', $s) }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $s->supplier_name }}
                                <small class="text-muted">({{ $s->supplier_code }})</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Entry History Card -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Stock Entry History
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Supplier</th>
                                <th class="text-end">Quantity</th>
                                <th>Delivery Reference</th>
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
                                    <span class="fw-semibold">{{ $e->supplier->supplier_name ?? 'N/A' }}</span>
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
                                    <p class="text-muted mb-0">No stock entries yet.</p>
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