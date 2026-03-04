@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold">
        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Supplier
    </h3>
    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Supplier
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-truck me-2"></i>Supplier Details
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc-scan me-1"></i>Supplier Code
                    </label>
                    <input type="text" name="supplier_code" 
                           class="form-control @error('supplier_code') is-invalid @enderror" 
                           value="{{ old('supplier_code', $supplier->supplier_code) }}" 
                           placeholder="Enter supplier code" required>
                    @error('supplier_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-building me-1"></i>Supplier Name
                    </label>
                    <input type="text" name="supplier_name" 
                           class="form-control @error('supplier_name') is-invalid @enderror" 
                           value="{{ old('supplier_name', $supplier->supplier_name) }}" 
                           placeholder="Enter supplier name" required>
                    @error('supplier_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-envelope me-1"></i>Contact Email
                    </label>
                    <input type="email" name="contact_email" 
                           class="form-control @error('contact_email') is-invalid @enderror" 
                           value="{{ old('contact_email', $supplier->contact_email) }}" 
                           placeholder="email@example.com" required>
                    @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        <i class="bi bi-telephone me-1"></i>Contact Number
                    </label>
                    <input type="text" name="contact_number" 
                           class="form-control @error('contact_number') is-invalid @enderror" 
                           value="{{ old('contact_number', $supplier->contact_number) }}" 
                           placeholder="Enter contact number" required>
                    @error('contact_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection