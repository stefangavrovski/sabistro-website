<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
?>
<x-app-layout title="Today's Specials">
    <div class="py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-8 text-center">Today's Specials</h2>
            <p class="text-center text-gray-500 mb-12">Experience our chef's carefully selected dishes</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featured as $product)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden text-gray-800 transform hover:scale-105 transition-transform flex flex-col h-full">
                        @if ($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-56 object-cover">
                        @endif
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold">{{ $product->name }}</h3>
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Featured</span>
                                </div>
                                <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-sm">
                                        {{ $product->preparation_time }} mins prep
                                    </span>
                                    <span class="bg-gray-100 px-2 py-1 rounded text-sm">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold">${{ number_format($product->price, 2) }}</span>
                                    <div class="flex gap-2">
                                        <form action="{{ route('user.cart.items.store') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <x-primary-button type="submit">Add To Cart</x-primary-button>
                                        </form>
                                        <a href="{{ route('user.products.show', $product) }}">
                                            <x-secondary-button>Details</x-secondary-button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>