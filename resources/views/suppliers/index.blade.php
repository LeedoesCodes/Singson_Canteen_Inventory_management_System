@extends('layouts.app')

@section('content')
<h3 class="mb-3">Suppliers</h3>

<div class="card mb-4">
    <div class="card-header">Add Supplier</div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Supplier Code</label>
                <input name="supplier_code" class="form-control" value="{{ old('supplier_code') }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Supplier Name</label>
                <input name="supplier_name" class="form-control" value="{{ old('supplier_name') }}" required>
            </div>
            <div class="col-md-3">
    <label class="form-label">Contact Email</label>
    <input name="contact_email" type="email" class="form-control" value="{{ old('contact_email') }}" required>
</div>
            <div class="col-md-2">
                <label class="form-label">Contact Number</label>
                <input name="contact_number" class="form-control" value="{{ old('contact_number') }}" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Supplier List</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $s)
                <tr>
                    <td>{{ $s->supplier_code }}</td>
                    <td>{{ $s->supplier_name }}</td>
                    <td>{{ $s->contact_number }}</td>
                    <td class="text-end">
                       <a class="btn btn-sm btn-outline-secondary" href="{{ route('suppliers.show', $s) }}">View</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('suppliers.edit', $s) }}">Edit</a>

                            <form method="POST" action="{{ route('suppliers.destroy', $s) }}" class="d-inline"
                            onsubmit="return confirm('Delete this supplier?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center p-4">No suppliers yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection