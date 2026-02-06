<?php

namespace App\Http\Controllers\User;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => auth()->id()]);
        }
        
        $cart->load('items.product');
        return view('user.cart.index', compact('cart'));
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = auth()->user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => auth()->id()]);
        }

        $existingItem = $cart->items()
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $validated['quantity']
            ]);
        } else {
            $cart->items()->create($validated);
        }

        return redirect()->back()->with('success', 'Item added to cart');
    }

    public function updateItem(Request $request, CartItem $item)
    {
        if (!Gate::allows('update', $item)) {
            abort(403);
        }
        
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item->update($validated);
        return redirect()->back()->with('success', 'Cart updated');
    }

    public function removeItem(CartItem $item)
    {
        if (!Gate::allows('delete', $item)) {
            abort(403);
        }
        
        $item->delete();
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}