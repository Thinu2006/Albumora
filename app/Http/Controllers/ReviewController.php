<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $reviews = Review::with('customer')->latest()->paginate(10);
            return ReviewResource::collection($reviews);
        } catch (\Exception $e) {
            \Log::error('Error fetching reviews: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching reviews',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'customer_id' => 'required|exists:users,id'
        ]);

        $review = Review::create([
            'customer_id' => $validated['customer_id'],
            'text' => $validated['text']
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'data' => new ReviewResource($review->load('customer'))
        ]);
    }

    public function show(string $id)
    {
        $review = Review::with('customer')->findOrFail($id);
        return new ReviewResource($review);
    }

    public function update(Request $request, string $id)
    {
        $review = Review::findOrFail($id);
        
        // Add authorization check (optional but recommended)
        

        $validated = $request->validate([
            'text' => 'required|string|max:1000'
        ]);

        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'data' => new ReviewResource($review->load('customer'))
        ]);
    }

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }
}