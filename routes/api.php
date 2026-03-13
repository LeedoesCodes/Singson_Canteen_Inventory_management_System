<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Product;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- PUBLIC ROUTES (No Authentication Required) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Product List
Route::get('/products', function () {
    return response()->json([
        'products' => Product::with('category')->orderBy('product_name')->get()
    ]);
});

// Public Order Tracking
Route::get('/orders/track/{orderNumber}', [OrderController::class, 'trackByNumber']);

// Public Reports (for Landing Page)
Route::get('/reports/public-best-sellers', [ReportsController::class, 'publicBestSellers']);

// Public Reviews (for Landing Page)
Route::get('/reviews', [ReviewController::class, 'index']);

// --- PROTECTED ROUTES (Require Authentication) ---
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Users (Full CRUD Resource - Admin Only)
    Route::apiResource('users', UserController::class);
    
    // Products (Administrative Actions - except index which is public)
    Route::apiResource('products', ProductController::class)->except(['index']);

    // Customer Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);

    // Admin Review Management
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/reviews', [ReviewController::class, 'index']);
        Route::patch('/admin/reviews/{review}/status', [ReviewController::class, 'updateStatus']);
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    
    // Inventory Management
    Route::prefix('inventory')->group(function () {
        Route::get('/logs', [InventoryController::class, 'index']);
        Route::post('/restock', [InventoryController::class, 'restock']);
        Route::post('/products/{product}/adjust', [InventoryController::class, 'adjust']);
        Route::get('/low-stock', [InventoryController::class, 'lowStock']);
    });

    // Protected Reports (Admin/Cashier Only)
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportsController::class, 'sales']);
        Route::get('/best-sellers', [ReportsController::class, 'bestSellers']);
        Route::get('/category-breakdown', [ReportsController::class, 'categoryBreakdown']);
        Route::get('/trends', [ReportsController::class, 'trends']);
    });
});

// Test route (for development)
Route::get('/token-test', function () {
    $user = User::first();
    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }
    $token = $user->createToken('test')->plainTextToken;
    return response()->json(['token' => $token]);
});