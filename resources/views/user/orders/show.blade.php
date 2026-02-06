<?php
use Illuminate\Support\Facades\Storage;
?>

<x-app-layout title="Order Details">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold">Order #{{ $order->order_number }}</h2>
                <a href="{{ route('user.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Back to Orders</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">Status:</dt>
                                <dd class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">Payment Status:</dt>
                                <dd class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                    @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">Payment Method:</dt>
                                <dd>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">Order Date:</dt>
                                <dd>{{ $order->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">Estimated Delivery:</dt>
                                <dd>{{ $order->estimated_delivery_time->format('M d, Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="font-medium text-gray-600">Delivery Address:</dt>
                                <dd class="mt-1">{{ $order->delivery_address }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600">Contact Phone:</dt>
                                <dd class="mt-1">{{ $order->contact_phone }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product->image_path)
                                                <img src="{{ Storage::url($item->product->image_path) }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="h-10 w-10 object-cover rounded-md mr-3">
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->product->category->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-center">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                <td class="px-6 py-4 text-center font-bold">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>