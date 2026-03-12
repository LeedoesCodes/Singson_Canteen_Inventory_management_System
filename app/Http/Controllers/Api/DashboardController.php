<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $lowStockItems = Product::where('current_stock', '>', 0)
            ->where('current_stock', '<=', 10)
            ->count();

        $recentOrders = Order::latest()
            ->take(5)
            ->get(['id', 'status', 'total_amount', 'created_at']);

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'recentOrders' => $recentOrders,
        ]);
    }
}