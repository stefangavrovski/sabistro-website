<?php
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="Product Details">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
            <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                    {{-- Product Image --}}
                    <div>
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-auto rounded-lg">
                        @endif
                    </div>

                    {{-- Product Details --}}
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-start">
                                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                                <span class="px-2 py-1 text-sm rounded {{ $product->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_available ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                        </div>

                        <div>
                            <span class="text-3xl font-bold">${{ number_format($product->price, 2) }}</span>
                            @if($product->preparation_time)
                                <p class="text-sm text-gray-600 mt-1">
                                    Preparation Time: {{ $product->preparation_time }} minutes
                                </p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Category: {{ $product->category->name }}</p>
                        </div>

                        {{-- Add to Cart Form --}}
                        @if($product->is_available)
                            <form action="{{ route('user.cart.items.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                                           class="mt-1 block w-20 rounded-md border-gray-300">
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <x-primary-button type="submit">Add to Cart</x-primary-button>
                            </form>
                        @else
                            <p class="text-red-600 font-medium">This product is currently unavailable</p>
                        @endif
                    </div>
                </div>

                {{-- Reviews Section --}}
                <div class="border-t mt-6 p-6">
                    <h2 class="text-xl font-semibold mb-4">Customer Reviews</h2>
                    
                    {{-- Review Form --}}
                    @if(auth()->user()->orders()->whereHas('items', fn($q) => $q->where('product_id', $product->id))->where('status', 'completed')->exists() && 
                        !$product->reviews()->where('user_id', auth()->id())->exists())
                        <form action="{{ route('user.reviews.store', $product) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                    <select name="rating" id="rating" class="mt-1 block w-full rounded-md border-gray-300">
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }} Star{{ $i !== 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                                    <textarea name="comment" id="comment" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                                    @error('comment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <x-primary-button type="submit">Submit Review</x-primary-button>
                            </div>
                        </form>
                    @endif

                    {{-- Reviews List --}}
                    <div class="space-y-4">
                        @forelse($product->reviews()->where('is_approved', true)->latest()->get() as $review)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium">{{ $review->user->name }}</div>
                                        <div class="text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <p class="mt-2 text-gray-600">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>