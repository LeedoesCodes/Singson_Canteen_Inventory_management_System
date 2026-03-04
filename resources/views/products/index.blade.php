@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold">
        <i class="bi bi-box-seam me-2 text-primary"></i>Products Management
    </h3>
</div>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-plus-circle me-2"></i>
        Add New Product
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-upc-scan me-1"></i>Product Code
                </label>
                <input name="product_code" class="form-control" value="{{ old('product_code') }}" 
                       placeholder="e.g., PRD001" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">
                    <i class="bi bi-tag me-1"></i>Product Name
                </label>
                <input name="product_name" class="form-control" value="{{ old('product_name') }}" 
                       placeholder="Enter product name" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-currency-dollar me-1"></i>Price
                </label>
                <input name="price" type="number" step="0.01" min="0" class="form-control" 
                       value="{{ old('price') }}" placeholder="0.00" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-save me-2"></i>Save Product
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-list-ul me-2"></i>
        Product List
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Current Stock</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark">{{ $p->product_code }}</span>
                        </td>
                        <td class="fw-semibold">{{ $p->product_name }}</td>
                        <td class="text-end fw-bold text-primary">
                            ${{ number_format($p->price, 2) }}
                        </td>
                        <td class="text-end">
                            <span class="badge {{ $p->current_stock > 10 ? 'bg-success' : ($p->current_stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $p->current_stock }} units
                            </span>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-info me-1" href="{{ route('products.show', $p) }}" 
                               data-bs-toggle="tooltip" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('products.edit', $p) }}"
                               data-bs-toggle="tooltip" title="Edit Product">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $p) }}" class="d-inline"
                                 onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                 @csrf
                                 @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="tooltip" title="Delete Product">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">No products yet. Add your first product above!</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush
@endsection