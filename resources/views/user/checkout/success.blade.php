<x-app-layout title="Order Confirmed">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-center">
                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h2 class="mt-4 text-2xl font-bold">Order Confirmed!</h2>
                    <p class="mt-2">Order #{{ $order->order_number }}</p>
                    <p class="mt-4">Thank you for your order. We'll notify you when it's ready.</p>
                    <div class="mt-6">
                        <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">View Order Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>