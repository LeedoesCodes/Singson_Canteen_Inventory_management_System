@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">{{ $product->product_name }}</h3>
        <div class="text-muted">Code: {{ $product->product_code }}</div>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Back</a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Product Info</div>
            <div class="card-body">
                <div><strong>Price:</strong> {{ number_format($product->price, 2) }}</div>
                <div><strong>Current Stock:</strong> {{ $product->current_stock }}</div>
                <div><strong>Total Delivered (history):</strong> {{ $totalDelivered }}</div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Add Stock Entry (Delivery)</div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock-entries.store') }}" class="row g-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="col-12">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- choose --</option>
                            @foreach(\App\Models\Supplier::orderBy('supplier_name')->get() as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->supplier_name }} ({{ $sup->supplier_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="1" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Delivery Reference (must be unique)</label>
                        <input name="delivery_reference" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Suppliers who delivered this product</div>
            <div class="card-body">
                @if($suppliers->isEmpty())
                    <div class="text-muted">No suppliers yet.</div>
                @else
                    <ul class="mb-0">
                        @foreach($suppliers as $s)
                            <li>
                                <a href="{{ route('suppliers.show', $s) }}">{{ $s->supplier_name }}</a>
                                ({{ $s->supplier_code }})
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">Stock Entry History</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th class="text-end">Qty</th>
                            <th>Delivery Ref</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($entries as $e)
                        <tr>
                            <td>{{ $e->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $e->supplier->supplier_name ?? 'N/A' }}</td>
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
    </div>
</div>
@endsection