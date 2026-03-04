@extends('layouts.app')

@section('content')
<h3 class="text-dark fw-bold mb-4">
    <i class="bi bi-truck me-2 text-primary"></i>Suppliers Management
</h3>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-plus-circle me-2"></i>
        Add New Supplier
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-upc-scan me-1"></i>Supplier Code
                </label>
                <input name="supplier_code" class="form-control" value="{{ old('supplier_code') }}" 
                       placeholder="e.g., SUP001" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">
                    <i class="bi bi-building me-1"></i>Supplier Name
                </label>
                <input name="supplier_name" class="form-control" value="{{ old('supplier_name') }}" 
                       placeholder="Enter supplier name" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-envelope me-1"></i>Contact Email
                </label>
                <input name="contact_email" type="email" class="form-control" 
                       value="{{ old('contact_email') }}" placeholder="email@example.com" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-telephone me-1"></i>Contact Number
                </label>
                <input name="contact_number" class="form-control" value="{{ old('contact_number') }}" 
                       placeholder="+1234567890" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-save me-2"></i>Save
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="bi bi-list-ul me-2"></i>
        Supplier List
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($suppliers as $s)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark">{{ $s->supplier_code }}</span>
                        </td>
                        <td class="fw-semibold">{{ $s->supplier_name }}</td>
                        <td>
                            <i class="bi bi-envelope me-1 text-muted"></i>{{ $s->contact_email }}
                            <br>
                            <small><i class="bi bi-telephone me-1 text-muted"></i>{{ $s->contact_number }}</small>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-info me-1" href="{{ route('suppliers.show', $s) }}" 
                               data-bs-toggle="tooltip" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('suppliers.edit', $s) }}"
                               data-bs-toggle="tooltip" title="Edit Supplier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('suppliers.destroy', $s) }}" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this supplier? This will also affect related stock entries.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="tooltip" title="Delete Supplier">
                                <i class="bi bi-trash"></i>
                            </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">No suppliers yet. Add your first supplier above!</p>
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