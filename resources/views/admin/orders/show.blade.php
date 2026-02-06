<?php
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="Order Details">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Order Details: {{ $order->order_number }}</h2>
            <a href="{{ route('admin.orders.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium text-gray-700">Name:</span> {{ $order->user->name }}</p>
                            <p><span class="font-medium text-gray-700">Email:</span> {{ $order->user->email }}</p>
                            <p><span class="font-medium text-gray-700">Phone:</span> {{ $order->contact_phone }}</p>
                            <p><span class="font-medium text-gray-700">Delivery Address:</span> {{ $order->delivery_address }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium text-gray-700">Order Date:</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
                            <p><span class="font-medium text-gray-700">Payment Method:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p><span class="font-medium text-gray-700">Payment Status:</span> {{ ucfirst($order->payment_status) }}</p>
                            <p>
                                <span class="font-medium text-gray-700">Order Status:</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product->image_path)
                                                <img src="{{ Storage::url($item->product->image_path) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-12 h-12 object-cover rounded">
                                            @endif
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">Total:</td>
                                <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Order Details</h3>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="status" value="Order Status" class="text-gray-700" />
                            <select id="status" name="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="payment_status" value="Payment Status" class="text-gray-700" />
                            <select id="payment_status" name="payment_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" @selected($order->payment_status === 'pending')>Pending</option>
                                <option value="paid" @selected($order->payment_status === 'paid')>Paid</option>
                                <option value="failed" @selected($order->payment_status === 'failed')>Failed</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="estimated_delivery_time" value="Estimated Delivery Time" class="text-gray-900" />
                            <x-text-input 
                                id="estimated_delivery_time" 
                                name="estimated_delivery_time" 
                                type="datetime-local" 
                                value="{{ $order->estimated_delivery_time?->format('Y-m-d\TH:i') }}" 
                                class="mt-1 block w-full bg-white rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button type="submit">
                            Update Order Details
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>