@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Supplier</h3>
    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Supplier Code</label>
                <input
                    type="text"
                    name="supplier_code"
                    class="form-control"
                    value="{{ old('supplier_code', $supplier->supplier_code) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Supplier Name</label>
                <input
                    type="text"
                    name="supplier_name"
                    class="form-control"
                    value="{{ old('supplier_name', $supplier->supplier_name) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Email</label>
                <input
                    type="email"
                    name="contact_email"
                    class="form-control"
                    value="{{ old('contact_email', $supplier->contact_email) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input
                    type="text"
                    name="contact_number"
                    class="form-control"
                    value="{{ old('contact_number', $supplier->contact_number) }}"
                    required
                >
            </div>

            <button class="btn btn-primary">
                Update Supplier
            </button>

        </form>

    </div>
</div>

@endsection