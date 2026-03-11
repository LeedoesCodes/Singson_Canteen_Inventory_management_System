<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $items = $request->items;

        if (!$items || count($items) === 0) {
            return response()->json([
                'message' => 'Order is empty'
            ], 400);
        }

        // First pass: validate all items before creating anything
        foreach ($items as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                return response()->json([
                    'message' => 'Invalid order item data'
                ], 400);
            }

            if ((int) $item['quantity'] <= 0) {
                return response()->json([
                    'message' => 'Quantity must be greater than zero'
                ], 400);
            }

            $product = Product::find($item['product_id']);

            if (!$product) {
                return response()->json([
                    'message' => 'One of the selected products does not exist'
                ], 404);
            }

            if ($product->current_stock < (int) $item['quantity']) {
                return response()->json([
                    'message' => "Insufficient stock for {$product->product_name}. Available stock: {$product->current_stock}"
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'total_amount' => 0,
                'status' => 'pending'
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $product->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ]);

                // Deduct stock
                $product->current_stock = $product->current_stock - $quantity;
                $product->save();

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.product')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        return Order::with('items.product')
            ->latest()
            ->get();
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,completed'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load('items.product')
        ]);
    }
}