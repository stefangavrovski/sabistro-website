<x-app-layout title="Checkout">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Checkout</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="order-2 md:order-1">
                        <form action="{{ route('user.checkout.process') }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Delivery Information</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="delivery_address" class="block text-sm font-medium text-gray-700">
                                                Delivery Address
                                            </label>
                                            <textarea
                                                id="delivery_address"
                                                name="delivery_address"
                                                rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                required
                                            >{{ old('delivery_address', auth()->user()->address) }}</textarea>
                                            @error('delivery_address')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">
                                                Contact Phone
                                            </label>
                                            <input
                                                type="tel"
                                                id="contact_phone"
                                                name="contact_phone"
                                                value="{{ old('contact_phone', auth()->user()->phone) }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                required
                                            >
                                            @error('contact_phone')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900">Payment Method</h3>
                                    <div class="mt-4">
                                        <select
                                            name="payment_method"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            required
                                        >
                                            <option value="">Select payment method</option>
                                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                                Credit Card
                                            </option>
                                            <option value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>
                                                Cash on Delivery
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div id="payment-element" class="mt-4 hidden"></div>
                                    <div id="payment-error" class="mt-2 text-red-600 hidden"></div>

                                </div>

                                <div class="border-t pt-6">
                                    <button type="submit" class="inline-flex w-full justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-s text-white uppercase tracking-widest hover:bg-blue-700">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="order-1 md:order-2">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                            <div class="mt-6 space-y-4">
                                @foreach($cart->items as $item)
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="mt-1 text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                                        </p>
                                    </div>
                                @endforeach

                                <div class="border-t pt-4">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-900">Subtotal</dt>
                                        <dd class="text-sm font-medium text-gray-900">${{ number_format($total, 2) }}</dd>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <dt class="text-base font-medium text-gray-900">Order total</dt>
                                        <dd class="text-base font-medium text-gray-900">${{ number_format($total, 2) }}</dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>