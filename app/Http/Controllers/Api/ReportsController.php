<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Get daily sales for a date range.
     */
    public function sales(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        // Daily sales (only completed or ready orders count as revenue)
        $sales = Order::whereBetween('created_at', [$from, $to])
            ->whereIn('status', ['completed', 'ready'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as sales')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Summary totals
        $totalSales = Order::whereBetween('created_at', [$from, $to])
            ->whereIn('status', ['completed', 'ready'])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$from, $to])
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        return response()->json([
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
        ]);
    }

    /**
     * Get best-selling products.
     */
    public function bestSellers(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $bestSellers = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('orders.status', ['completed', 'ready'])
            ->select(
                'products.id',
                'products.product_name as name',
                'categories.name as category',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.product_name', 'categories.name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        return response()->json([
            'products' => $bestSellers,
        ]);
    }

    /**
     * Get sales breakdown by category.
     */
    public function categoryBreakdown(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        // Aggregate sales per category
        $categories = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->whereIn('orders.status', ['completed', 'ready'])
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total')
            )
            ->groupBy('categories.id', 'categories.name')
            ->having('total', '>', 0)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->name,
                    'value' => (float) $row->total,
                ];
            });

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Get order volume trends (daily order counts).
     */
    public function trends(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $trends = Order::whereBetween('created_at', [$from, $to])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'trends' => $trends,
        ]);
    }
}