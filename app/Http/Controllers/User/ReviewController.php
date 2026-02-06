<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        if ($product->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already reviewed this product');
        }

        $hasPurchased = auth()->user()->orders()
        ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
        ->where('status', 'completed')
        ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You must purchase this product before reviewing');
        }

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully');
    }

    public function userReviews()
    {
        $reviews = auth()->user()->reviews()
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'image_path');
            }])
            ->latest()
            ->paginate(10);
            
        return view('user.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }
        
        $review->delete();
        
        return redirect()->route('user.reviews.index')
            ->with('success', 'Review deleted successfully');
    }
}

