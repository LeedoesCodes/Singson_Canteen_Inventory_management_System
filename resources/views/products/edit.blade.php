@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold">
        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Product
    </h3>
    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Product
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-box me-2"></i>Product Details
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc-scan me-1"></i>Product Code
                    </label>
                    <input type="text" name="product_code" 
                           class="form-control @error('product_code') is-invalid @enderror" 
                           value="{{ old('product_code', $product->product_code) }}" 
                           placeholder="Enter product code" required>
                    @error('product_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-tag me-1"></i>Product Name
                    </label>
                    <input type="text" name="product_name" 
                           class="form-control @error('product_name') is-invalid @enderror" 
                           value="{{ old('product_name', $product->product_name) }}" 
                           placeholder="Enter product name" required>
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-currency-dollar me-1"></i>Price
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" name="price" 
                               class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $product->price) }}" 
                               placeholder="0.00" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-boxes me-1"></i>Current Stock
                    </label>
                    <input type="number" class="form-control" 
                           value="{{ $product->current_stock }}" readonly disabled>
                    <small class="text-muted">Stock is managed through deliveries</small>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection