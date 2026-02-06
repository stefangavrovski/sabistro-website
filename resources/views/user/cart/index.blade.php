<?php
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="Shopping Cart">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Shopping Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cart->items->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <p class="text-gray-500">Your cart is empty.</p>
                    <a href="{{ route('user.products.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">
                        Continue Shopping
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cart->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product->image_path)
                                                <img src="{{ Storage::url($item->product->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-16 h-16 object-cover rounded">
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->name }}
                                                </div>
                                                @if(!$item->product->is_available)
                                                    <span class="text-red-500 text-xs">Currently unavailable</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                                        ${{ number_format($item->product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('user.cart.items.update', $item) }}" 
                                              method="POST" 
                                              class="flex items-center justify-center space-x-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1"
                                                   class="w-20 rounded-md border-gray-300 shadow-sm text-center focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <x-primary-button>Update</x-primary-button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                                        ${{ number_format($item->product->price * $item->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('user.cart.items.destroy', $item) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to remove this item?');"
                                              class="flex justify-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex justify-between items-center">
                        <div class="space-y-1">
                            <div class="text-sm text-gray-600">
                                Total ({{ $cart->items->sum('quantity') }} items):
                                <span class="font-semibold text-gray-900">
                                    ${{ number_format($cart->items->sum(fn($item) => $item->product->price * $item->quantity), 2) }}
                                </span>
                            </div>
                            @if($cart->items->contains(fn($item) => !$item->product->is_available))
                                <p class="text-red-500 text-sm">
                                    Some items in your cart are currently unavailable
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('user.checkout') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>