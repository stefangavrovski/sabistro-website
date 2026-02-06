<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category')
            ->where('is_available', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            if ($max === 'up') {
                $query->where('price', '>=', $min);
            } else {
                $query->whereBetween('price', [$min, $max]);
            }
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'preparation_time':
                $query->orderBy('preparation_time', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        return view('user.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_available) {
            abort(404);
        }
        
        $product->load(['category', 'reviews' => function($query) {
            $query->where('is_approved', true)->with('user');
        }]);
        return view('user.products.show', compact('product'));
    }

    public function featured()
    {
        $featured = Product::with('category')
            ->where('is_featured', true)
            ->where('is_available', true)
            ->inRandomOrder()
            ->take(6)
            ->get();
        return view('user.products.featured', compact('featured'));
    }
}