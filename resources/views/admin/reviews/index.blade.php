<?php
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="Review Management">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Review Management</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" 
                                    name="search" 
                                    id="search" 
                                    value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Search by product or user">
                            </div>

                            <div>
                                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                <select name="rating" 
                                        id="rating" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Ratings</option>
                                    @foreach(range(1, 5) as $rating)
                                        <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                            {{ $rating }} Star{{ $rating > 1 ? 's' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" 
                                        id="status" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                </select>
                            </div>

                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                                <select name="sort" 
                                        id="sort" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                    <option value="rating_high" {{ request('sort') === 'rating_high' ? 'selected' : '' }}>Rating (High to Low)</option>
                                    <option value="rating_low" {{ request('sort') === 'rating_low' ? 'selected' : '' }}>Rating (Low to High)</option>
                                </select>
                            </div>

                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" 
                                    name="date_from" 
                                    id="date_from" 
                                    value="{{ request('date_from') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" 
                                    name="date_to" 
                                    id="date_to" 
                                    value="{{ request('date_to') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-start space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>

                <div class="bg-white shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-1/5 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="w-1/5 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="w-28 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="w-14 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="w-1/4 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Comment</th>
                                <th class="w-20 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="w-24 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-start">
                                            @if($review->product->image_path)
                                                <img src="{{ Storage::url($review->product->image_path) }}" 
                                                    alt="{{ $review->product->name }}" 
                                                    class="w-10 h-10 object-cover rounded">
                                            @endif
                                            <div class="ml-2">
                                                <p class="text-sm font-medium text-gray-900 truncate max-w-[180px]">{{ $review->product->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-center text-sm">
                                            <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                            <p class="text-gray-500">{{ $review->user->email }}</p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ $review->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex justify-center">
                                            <span class="px-2 inline-flex justify-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $review->rating }}/5
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div x-data="{ expanded: false }">
                                            <div 
                                                @click="expanded = !expanded" 
                                                class="cursor-pointer"
                                            >
                                                <p 
                                                    class="text-sm text-gray-900" 
                                                    :class="{ 'truncate max-w-[250px]': !expanded }"
                                                >
                                                    {{ $review->comment }}
                                                </p>
                                                <span 
                                                    x-show="!expanded && {{ strlen($review->comment) > 100 }}" 
                                                    class="text-xs text-blue-600 mt-1"
                                                >
                                                    Read more
                                                </span>
                                                <span 
                                                    x-show="expanded" 
                                                    class="text-xs text-blue-600 mt-1"
                                                >
                                                    Show less
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex justify-center">
                                            <span class="px-2 inline-flex justify-center text-xs leading-5 font-semibold rounded-full 
                                                {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col items-center space-y-2">
                                            @if(!$review->is_approved)
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No reviews found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>