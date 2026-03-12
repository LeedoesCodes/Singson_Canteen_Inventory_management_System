<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\UserController; // Ensure this matches your folder structure
use App\Models\User;
use App\Models\Product;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- PUBLIC ROUTES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Product List
Route::get('/products', function () {
    return response()->json([
        'products' => Product::with('category')->orderBy('product_name')->get()
    ]);
});

Route::get('/orders/track/{orderNumber}', [OrderController::class, 'trackByNumber']);

// --- PROTECTED ROUTES ---
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Users (Full CRUD Resource)
    Route::apiResource('users', UserController::class);
    
    // Products (Administrative Actions)
    Route::apiResource('products', ProductController::class)->except(['index']);

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

    // Reports
    Route::prefix('reports')->group(function () {
        // ... (existing report routes)
        Route::get('/sales', [ReportsController::class, 'sales']);
        Route::get('/best-sellers', [ReportsController::class, 'bestSellers']);
        Route::get('/category-breakdown', [ReportsController::class, 'categoryBreakdown']);
        Route::get('/trends', [ReportsController::class, 'trends']);
    });
});

// Test route
Route::get('/token-test', function () {
    $user = User::first();
    if (!$user) return response()->json(['error' => 'No user found'], 404);
    $token = $user->createToken('test')->plainTextToken;
    return $token;
});