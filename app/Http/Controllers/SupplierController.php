<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_code' => ['required', 'string', 'max:255', 'unique:suppliers,supplier_code'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255', 'unique:suppliers,contact_email'],
            'contact_number' => ['required', 'string', 'max:255'],
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created.');
    }

    public function show(Supplier $supplier)
    {
     
        $supplier->load('products');

        
        $productTotals = $supplier->products
            ->groupBy('id')
            ->map(function ($items) {
                $first = $items->first();

                return (object) [
                    'product_id' => $first->id,
                    'product_code' => $first->product_code,
                    'product_name' => $first->product_name,
                    'total_quantity' => $items->sum(fn($p) => (int) $p->pivot->quantity),
                ];
            })
            ->values();

    
        $entries = $supplier->stockEntries()
            ->with('product')
            ->latest()
            ->get();

        return view('suppliers.show', compact('supplier', 'productTotals', 'entries'));
    }
    public function edit(Supplier $supplier)
{
    return view('suppliers.edit', compact('supplier'));
}

public function update(Request $request, Supplier $supplier)
{
    $validated = $request->validate([
        'supplier_code' => ['required', 'string', 'max:255', 'unique:suppliers,supplier_code,' . $supplier->id],
        'supplier_name' => ['required', 'string', 'max:255'],
        'contact_email' => ['required', 'email', 'max:255', 'unique:suppliers,contact_email,' . $supplier->id],
        'contact_number' => ['required', 'string', 'max:255'],
    ]);

    $supplier->update($validated);

    return redirect()->route('suppliers.show', $supplier)->with('success', 'Supplier updated.');
}

public function destroy(Supplier $supplier)
{
    $supplier->delete();
    return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
}
}