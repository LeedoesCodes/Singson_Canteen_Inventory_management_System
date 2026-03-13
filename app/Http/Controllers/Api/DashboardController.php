<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user
        $user = $request->user();
        
        // Total counts
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Low stock items (less than or equal to 10)
        $lowStockItems = Product::where('current_stock', '<=', 10)->count();
        
        // Sales calculations
        $todaySales = Order::whereDate('created_at', today())
            ->whereIn('status', ['completed', 'ready'])
            ->sum('total_amount');
        
        $weekSales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('status', ['completed', 'ready'])
            ->sum('total_amount');
        
        $monthSales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['completed', 'ready'])
            ->sum('total_amount');
        
        // Order status counts
        $orderStatusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        // Get recent orders with relationships
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? 'Unknown',
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at->toISOString(),
                    'items_count' => $order->items->count(),
                ];
            });
        
        return response()->json([
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'lowStockItems' => $lowStockItems,
            'todaySales' => $todaySales,
            'weekSales' => $weekSales,
            'monthSales' => $monthSales,
            'recentOrders' => $recentOrders,
            'orderStatusCounts' => $orderStatusCounts,
        ]);
    }
}