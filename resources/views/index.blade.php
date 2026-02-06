<x-app-layout title="Home">
    <div x-data="{ currentSlide: 0 }" x-init="setInterval(() => currentSlide = (currentSlide + 1) % 3, 3500)" class="relative">
        <div class="relative h-screen overflow-hidden">
            <template x-for="(slide, index) in [
                '{{ asset('images/slide1.jpg') }}',
                '{{ asset('images/slide2.jpg') }}',
                '{{ asset('images/slide3.jpg') }}',
            ]" :key="index">
                <div x-show="currentSlide === index"
                     x-transition:enter="transition-opacity duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-1000"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img :src="slide" class="h-full w-full object-cover" alt="Hero image">
                    <div class="absolute inset-0 bg-black/50"></div>
                </div>
            </template>

            <div class="absolute inset-0 flex items-center justify-center">
                <div class="max-w-4xl text-center px-4">
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 tracking-tight">
                        Savor the <span class="text-orange-400">Experience</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-200 mb-12 max-w-2xl mx-auto">
                        Experience the finest selection of local and traditional meals, delivered fresh and hot to your location.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="inline-block bg-orange-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-500 transform hover:scale-105 transition-all">
                                    Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('user.products.index') }}" class="inline-block bg-orange-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-500 transform hover:scale-105 transition-all">
                                    Browse Menu
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="inline-block bg-orange-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-500 transform hover:scale-105 transition-all">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="inline-block bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white/20 transform hover:scale-105 transition-all">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
                <template x-for="(slide, index) in [0, 1, 2]" :key="index">
                    <button @click="currentSlide = index"
                            :class="{'bg-white': currentSlide === index, 'bg-white/50': currentSlide !== index}"
                            class="w-2 h-2 rounded-full transition-all duration-300">
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Why Choose Us</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 tracking-tight">
                    Everything you need for the perfect meal
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Wide Selection</h3>
                    <p class="text-gray-600 text-center">Choose from a wide range of local and traditional cuisines.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Fast Delivery</h3>
                    <p class="text-gray-600 text-center">Get your food delivered within 45 minutes or less.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Quality Guaranteed</h3>
                    <p class="text-gray-600 text-center">All of our cuisines are made with the finest ingredients and love.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 bg-gray-150 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Contact Us</h2>
                <p class="mt-2 text-4xl font-bold text-gray-900 tracking-tight">
                    Get in touch
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Visit Us</h3>
                    <p class="text-gray-600 text-center">SEEU University<br>Tetovo, 1200</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Call Us</h3>
                    <p class="text-gray-600 text-center">+389 (070) 123-456<br>Mon-Fri: 9:00 - 21:00</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg transform hover:-translate-y-2 transition-all">
                    <div class="w-12 h-12 bg-orange-100 mx-auto rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center">Email Us</h3>
                    <p class="text-gray-600 text-center">contact@sabistro.com<br>support@sabistro.com</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>