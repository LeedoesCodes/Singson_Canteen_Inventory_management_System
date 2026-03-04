<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockEntry;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('product_name')->get();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code'],
            'product_name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        Product::create([
            'product_code' => $validated['product_code'],
            'product_name' => $validated['product_name'],
            'price' => $validated['price'],
            'current_stock' => 0,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        
        $entries = StockEntry::with('supplier')
            ->where('product_id', $product->id)
            ->latest()
            ->get();

       
        $suppliers = $product->suppliers()->orderBy('supplier_name')->get();

        
        $totalDelivered = $entries->sum('quantity');

        return view('products.show', compact('product', 'entries', 'suppliers', 'totalDelivered'));
    }
    public function edit(Product $product)
{
    return view('products.edit', compact('product'));
}

public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code,' . $product->id],
        'product_name' => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ]);

    $product->update($validated);

    return redirect()->route('products.show', $product)->with('success', 'Product updated.');
}

public function destroy(Product $product)
{
    $product->delete();
    return redirect()->route('products.index')->with('success', 'Product deleted.');
}
}