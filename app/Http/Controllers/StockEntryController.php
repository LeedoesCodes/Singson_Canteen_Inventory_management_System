<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'delivery_reference' => ['required', 'string', 'max:255', 'unique:stock_entries,delivery_reference'],
        ]);

        DB::transaction(function () use ($validated) {
            StockEntry::create($validated);

            Product::where('id', $validated['product_id'])
                ->increment('current_stock', $validated['quantity']);
        });

        return back()->with('success', 'Stock entry saved. Product stock increased.');
    }
}