@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Products</h3>
</div>

<div class="card mb-4">
    <div class="card-header">Add Product</div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Product Code</label>
                <input name="product_code" class="form-control" value="{{ old('product_code') }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Product Name</label>
                <input name="product_name" class="form-control" value="{{ old('product_name') }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Price</label>
                <input name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price') }}" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Product List</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Current Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td>{{ $p->product_code }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td class="text-end">{{ number_format($p->price, 2) }}</td>
                    <td class="text-end">{{ $p->current_stock }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('products.show', $p) }}">View</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('products.edit', $p) }}">Edit</a>

                        <form method="POST" action="{{ route('products.destroy', $p) }}" class="d-inline"
                             onsubmit="return confirm('Delete this product?')">
                             @csrf
                             @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center p-4">No products yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection