<?php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
?>
<x-app-layout title="Products">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Our Products</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white p-6 rounded-lg shadow">
                <form action="{{ route('user.products.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select name="category" class="rounded-md">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="price_range" class="rounded-md">
                            <option value="">Price Range</option>
                            <option value="0-10" @selected(request('price_range') == '0-10')>Under $10</option>
                            <option value="10-20" @selected(request('price_range') == '10-20')>$10 - $20</option>
                            <option value="20-30" @selected(request('price_range') == '20-30')>$20 - $30</option>
                            <option value="30-up" @selected(request('price_range') == '30-up')>Over $30</option>
                        </select>

                        <select name="sort" class="rounded-md">
                            <option value="latest" @selected(request('sort') == 'latest')>Newest First</option>
                            <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                            <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A to Z</option>
                            <option value="name_desc" @selected(request('sort') == 'name_desc')>Name: Z to A</option>
                            <option value="preparation_time" @selected(request('sort') == 'preparation_time')>Preparation Time</option>
                        </select>

                        <div class="flex gap-2">
                            <input type="text" name="search" placeholder="Search products..." 
                                value="{{ request('search') }}" class="rounded-md flex-grow">
                        </div>
                    </div>
                    <div class="flex justify-start gap-2 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Apply Filters
                        </button>
                        <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg flex flex-col h-full">
                        @if($product->image_path)
                            <img src="{{ Storage::url($product->image_path) }}" 
                                alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                        {{ $product->preparation_time }} mins prep
                                    </span>
                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold">${{ number_format($product->price, 2) }}</span>
                                    <div class="flex gap-2">
                                        <form action="{{ route('user.cart.items.store') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <x-primary-button type="submit">Add to Cart</x-primary-button>
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

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>