<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with('items.product', 'user');

        // If user is customer, show only their orders
        if ($user->role === 'customer') {
            $query->where('user_id', $user->id);
        }

        $orders = $query->latest()->get();

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $items = $request->items;

        if (!$items || count($items) === 0) {
            return response()->json([
                'message' => 'Order is empty'
            ], 400);
        }

        // Get the authenticated user
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
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

            $product = Product::with('category')->find($item['product_id']);

            if (!$product) {
                return response()->json([
                    'message' => 'One of the selected products does not exist'
                ], 404);
            }

            if (!$product->is_available) {
                return response()->json([
                    'message' => "{$product->product_name} is currently unavailable"
                ], 400);
            }

            if ($product->current_stock < (int) $item['quantity']) {
                return response()->json([
                    'message' => "Insufficient stock for {$product->product_name}. Available stock: {$product->current_stock}"
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            // Generate order number
            $latestOrder = Order::latest('id')->first();
            $nextId = $latestOrder ? $latestOrder->id + 1 : 1;
            $orderNumber = 'ORD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_amount' => 0,
                'status' => 'pending'
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $quantity = (int) $item['quantity'];
                $subtotal = $product->price * $quantity;
                $previousStock = $product->current_stock;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ]);

                // Deduct stock
                $product->current_stock = $product->current_stock - $quantity;
                $product->save();

                // Create inventory log
                InventoryLog::create([
                    'product_id' => $product->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->current_stock,
                    'quantity_change' => -$quantity,
                    'type' => 'order',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Order #{$orderNumber} - {$quantity} units",
                    'user_id' => $user->id,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total_amount' => $total
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.product', 'user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        // Check if user can view this order
        $user = request()->user();
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized to view this order'
            ], 403);
        }

        return response()->json([
            'order' => $order->load('items.product', 'user')
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled'
        ]);

        // Only admin and cashier can update status
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'cashier'])) {
            return response()->json([
                'message' => 'Unauthorized to update order status'
            ], 403);
        }

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load('items.product', 'user')
        ]);
    }

    public function cancel(Order $order)
    {
        $user = request()->user();
        
        if (!in_array($user->role, ['admin', 'cashier'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['message' => 'Order is already cancelled'], 400);
        }

        if ($order->status === 'completed') {
            return response()->json(['message' => 'Completed orders cannot be cancelled'], 400);
        }

        DB::beginTransaction();

        try {
            // Restore stock for each item
            foreach ($order->items as $item) {
                $product = $item->product;
                $previousStock = $product->current_stock;
                
                $product->current_stock += $item->quantity;
                $product->save();

                // Log the stock restoration
                InventoryLog::create([
                    'product_id' => $product->id,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->current_stock,
                    'quantity_change' => $item->quantity,
                    'type' => 'cancellation',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Order #{$order->order_number} cancelled - {$item->quantity} units returned",
                    'user_id' => $user->id,
                ]);
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => $order->load('items.product')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to cancel order'], 500);
        }
    }

    public function trackByNumber($orderNumber)
    {
        $order = Order::with('items.product', 'user')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'order' => $order
        ]);
    }
}