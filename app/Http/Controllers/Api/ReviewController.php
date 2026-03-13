<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get all approved reviews (public - for landing page)
     */
    public function index()
    {
        $reviews = Review::with('user:id,name')
            ->where('status', 'approved')
            ->latest()
            ->take(6) // Get latest 6 reviews
            ->get();

        $averageRating = Review::where('status', 'approved')->avg('rating');
        $totalReviews = Review::where('status', 'approved')->count();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
        ]);
    }

    /**
     * Submit a review after order completion (customer only)
     */
    public function store(Request $request)
{
    // Get authenticated user
    $user = $request->user();
    
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $validator = Validator::make($request->all(), [
        'order_id' => 'required|exists:orders,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }
    
    // Check if order belongs to user and is completed
    $order = Order::where('id', $request->order_id)
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'You can only review completed orders'
        ], 403);
    }

    // Check if already reviewed this order
    $existingReview = Review::where('user_id', $user->id)
        ->where('order_id', $request->order_id)
        ->first();

    if ($existingReview) {
        return response()->json([
            'message' => 'You already reviewed this order'
        ], 400);
    }

    $review = Review::create([
        'user_id' => $user->id,
        'order_id' => $request->order_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'status' => 'approved',
    ]);

    return response()->json([
        'message' => 'Thank you for your review!',
        'review' => $review->load('user:id,name'),
    ], 201);
}

    /**
     * Get user's review history
     */
    public function myReviews(Request $request)
    {
        $reviews = Review::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }
}