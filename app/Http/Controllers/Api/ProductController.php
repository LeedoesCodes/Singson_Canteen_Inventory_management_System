<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('product_name')->get();
        
        // Append full image URL
        $products->each(function ($product) {
            if ($product->image) {
                $product->image_url = url('storage/' . $product->image);
            }
        });

        return response()->json([
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string|unique:products,product_code',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'current_stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);
        $product->load('category');

        if ($product->image) {
            $product->image_url = url('storage/' . $product->image);
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load('category');
        if ($product->image) {
            $product->image_url = url('storage/' . $product->image);
        }

        return response()->json(['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'product_code' => 'required|string|unique:products,product_code,' . $product->id,
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'current_stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);
        $product->load('category');

        if ($product->image) {
            $product->image_url = url('storage/' . $product->image);
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}