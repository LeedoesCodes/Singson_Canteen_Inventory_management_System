@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Product</h3>
    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Product Code</label>
                <input
                    type="text"
                    name="product_code"
                    class="form-control"
                    value="{{ old('product_code', $product->product_code) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input
                    type="text"
                    name="product_name"
                    class="form-control"
                    value="{{ old('product_name', $product->product_name) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="price"
                    class="form-control"
                    value="{{ old('price', $product->price) }}"
                    required
                >
            </div>

            <button class="btn btn-primary">
                Update Product
            </button>

        </form>

    </div>
</div>

@endsection