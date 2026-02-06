<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ReviewManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->rating, function ($query) use ($request) {
                $query->where('rating', $request->rating);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('is_approved', $request->status === 'approved');
            })
            ->when($request->date_from, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            });

        $query->when($request->sort, function ($query) use ($request) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'rating_high':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'rating_low':
                    $query->orderBy('rating', 'asc');
                    break;
                default:
                    $query->latest();
            }
        }, function ($query) {
            $query->latest();
        });

        $reviews = $query->paginate(10)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        if (!Gate::allows('viewAny', Review::class)) {
            abort(403);
        }

        $review->update(['is_approved' => true]);
        
        return redirect()->back()
            ->with('success', 'Review approved successfully');
    }

    public function destroy(Review $review)
    {
        if (!Gate::allows('viewAny', Review::class)) {
            abort(403);
        }

        try {
            $review->delete();
            return redirect()->back()
                ->with('success', 'Review deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to delete review. Please try again.');
        }
    }
}
