<?php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="Products Management">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Products Management</h2>
            @can('create', App\Models\Product::class)
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <span class="mr-2">+</span> Add Product
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Search by name or description">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" 
                                    id="category" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700">Availability</label>
                            <select name="availability" 
                                    id="availability" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All</option>
                                <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                            <select name="sort" 
                                    id="sort" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                            </select>
                        </div>

                        <div>
                            <label for="featured" class="block text-sm font-medium text-gray-700">Featured</label>
                            <select name="featured" 
                                    id="featured" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All</option>
                                <option value="yes" {{ request('featured') === 'yes' ? 'selected' : '' }}>Featured</option>
                                <option value="no" {{ request('featured') === 'no' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>

                        <div>
                            <label for="price_range" class="block text-sm font-medium text-gray-700">Price Range</label>
                            <select name="price_range" 
                                    id="price_range" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Prices</option>
                                <option value="0-10" {{ request('price_range') === '0-10' ? 'selected' : '' }}>$0 - $10</option>
                                <option value="10-25" {{ request('price_range') === '10-25' ? 'selected' : '' }}>$10 - $25</option>
                                <option value="25-50" {{ request('price_range') === '25-50' ? 'selected' : '' }}>$25 - $50</option>
                                <option value="50-100" {{ request('price_range') === '50-100' ? 'selected' : '' }}>$50 - $100</option>
                                <option value="100-999999" {{ request('price_range') === '100-999999' ? 'selected' : '' }}>$100+</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-start space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($product->image_path)
                                                <img src="{{ Storage::url($product->image_path) }}" 
                                                     alt="{{ $product->name }}"
                                                     class="h-10 w-10 rounded-full object-cover mr-3">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                @if($product->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <span class="px-2 inline-flex justify-center text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $product->category->name }}
                                            </span>
                                        </div> 
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-gray-900">
                                        <div class="flex flex-col space-y-1">
                                            ${{ number_format($product->price, 2) }}
                                        </div> 
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <span class="px-2 inline-flex justify-center text-xs leading-5 font-semibold rounded-full 
                                                {{ $product->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                            @if($product->is_featured)
                                                <span class="px-2 inline-flex justify-center text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Featured
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            @can('update', $product)
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endcan
                                            @can('delete', $product)
                                                <form action="{{ route('admin.products.destroy', $product) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No products found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>