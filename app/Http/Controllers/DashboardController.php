<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockEntry;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalDeliveries = StockEntry::count();


        $totalCurrentStock = Product::sum('current_stock');


        $recentDeliveries = StockEntry::with(['product', 'supplier'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalDeliveries',
            'totalCurrentStock',
            'recentDeliveries'
        ));
    }
}