<?php
use Illuminate\Support\Facades\Storage;
?>
<x-app-layout title="My Reviews">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Reviews</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    @forelse($reviews as $review)
                        <div class="border-b last:border-b-0 py-6 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                @if($review->product->image_path)
                                    <img src="{{ Storage::url($review->product->image_path) }}" 
                                         alt="{{ $review->product->name }}"
                                         class="w-20 h-20 object-cover rounded-lg">
                                @endif
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('user.products.show', $review->product) }}" 
                                               class="text-lg font-semibold hover:text-blue-600">
                                                {{ $review->product->name }}
                                            </a>
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
                                        <div class="flex items-center gap-4">
                                            <div class="text-sm text-gray-500">
                                                {{ $review->created_at->format('M d, Y') }}
                                            </div>
                                            <form action="{{ route('user.reviews.destroy', $review) }}" 
                                                  method="POST" 
                                                  class="delete-review-form" 
                                                  onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                         class="h-5 w-5" 
                                                         fill="none" 
                                                         viewBox="0 0 24 24" 
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round" 
                                                              stroke-linejoin="round" 
                                                              stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-gray-600">{{ $review->comment }}</p>
                                    <div class="mt-2 text-sm">
                                        <span class="px-2 py-1 rounded {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">You haven't written any reviews yet.</p>
                    @endforelse

                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>