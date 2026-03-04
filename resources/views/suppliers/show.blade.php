@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">{{ $supplier->supplier_name }}</h3>
        <div class="text-muted">Code: {{ $supplier->supplier_code }} | Contact: {{ $supplier->contact_number }}</div>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('suppliers.index') }}">Back</a>
</div>

<div class="card mb-3">
    <div class="card-header">Products supplied (total quantities)</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th class="text-end">Total Qty Delivered</th>
                </tr>
            </thead>
            <tbody>
            @forelse($productTotals as $row)
                <tr>
                    <td>{{ $row->product_code }}</td>
                    <td>{{ $row->product_name }}</td>
                    <td class="text-end">{{ $row->total_quantity }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center p-4">No deliveries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">Delivery history</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th class="text-end">Qty</th>
                    <th>Delivery Ref</th>
                </tr>
            </thead>
            <tbody>
            @forelse($entries as $e)
                <tr>
                    <td>{{ $e->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $e->product->product_name ?? 'N/A' }}</td>
                    <td class="text-end">{{ $e->quantity }}</td>
                    <td>{{ $e->delivery_reference }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center p-4">No entries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection