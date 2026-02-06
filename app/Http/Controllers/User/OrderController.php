<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()
            ->with('items.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'total_high':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'total_low':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (!Gate::allows('view', $order)) {
            abort(403);
        }
        
        $order->load('items.product');
        return view('user.orders.show', compact('order'));
    }
}