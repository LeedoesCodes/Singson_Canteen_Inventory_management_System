<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryLog::with(['product', 'user'])
            ->latest();

        // Filter by product
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Date range filter
        if ($request->has('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->has('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $logs = $query->paginate(20);

        return response()->json([
            'logs' => $logs,
            'summary' => [
                'total_restocks' => InventoryLog::where('type', 'restock')->count(),
                'total_orders' => InventoryLog::where('type', 'order')->count(),
                'total_adjustments' => InventoryLog::where('type', 'adjustment')->count(),
                'total_cancellations' => InventoryLog::where('type', 'cancellation')->count(),
            ]
        ]);
    }

    public function restock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $results = [];

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $previousStock = $product->current_stock;
                
                $product->current_stock += $item['quantity'];
                $product->save();

                $log = InventoryLog::create([
                    'product_id' => $product->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->current_stock,
                    'quantity_change' => $item['quantity'],
                    'type' => 'restock',
                    'notes' => $request->notes ?? "Bulk restock - +{$item['quantity']} units",
                    'user_id' => $user->id,
                ]);

                $results[] = [
                    'product' => $product,
                    'log' => $log
                ];
            }

            DB::commit();

            return response()->json([
                'message' => 'Stock restocked successfully',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to restock'], 500);
        }
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string',
        ]);

        $user = $request->user();
        $previousStock = $product->current_stock;
        $change = $request->new_stock - $previousStock;

        DB::beginTransaction();

        try {
            $product->current_stock = $request->new_stock;
            $product->save();

            $log = InventoryLog::create([
                'product_id' => $product->id,
                'previous_stock' => $previousStock,
                'new_stock' => $product->current_stock,
                'quantity_change' => $change,
                'type' => 'adjustment',
                'notes' => $request->reason,
                'user_id' => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Stock adjusted successfully',
                'product' => $product,
                'log' => $log
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to adjust stock'], 500);
        }
    }

    public function lowStock()
    {
        $threshold = 10;
        $products = Product::where('current_stock', '<=', $threshold)
            ->where('is_available', true)
            ->orderBy('current_stock', 'asc')
            ->get();

        return response()->json([
            'threshold' => $threshold,
            'count' => $products->count(),
            'products' => $products
        ]);
    }
}