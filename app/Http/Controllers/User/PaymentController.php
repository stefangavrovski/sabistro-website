<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Cart;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function checkout()
    {
        $cart = auth()->user()->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Cart is empty');
        }
        
        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
        return view('user.cart.checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string',
            'contact_phone' => 'required|string',
            'payment_method' => 'required|in:credit_card,cash_on_delivery'
        ]);

        $cart = auth()->user()->cart;
        $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

        $order = Order::where('user_id', auth()->id())
        ->where('status', 'pending')
        ->latest()
        ->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $total,
                'status' => 'pending',
                'delivery_address' => $validated['delivery_address'],
                'contact_phone' => $validated['contact_phone'],
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'estimated_delivery_time' => now()->addMinutes(20)
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal' => $item->product->price * $item->quantity
                ]);
            }
        }

        if ($validated['payment_method'] === 'cash_on_delivery') {

            $order->update([
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'status' => 'processing'
            ]);

            $cart->items()->delete();
            $cart->delete();
            
            return redirect()->route('user.orders.show', $order)
                ->with('success', 'Order placed successfully');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order #' . $order->order_number,
                    ],
                    'unit_amount' => (int)($total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('user.checkout.success', ['order' => $order->id]),
            'cancel_url' => route('user.cart.index'),
            'metadata' => [
                'order_id' => $order->id
            ]
        ]);

        return redirect($session->url);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'credit_card',
            'status' => 'processing'
        ]);

        return view('user.checkout.success', compact('order'));
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $order = Order::find($session->metadata->order_id);
            
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}