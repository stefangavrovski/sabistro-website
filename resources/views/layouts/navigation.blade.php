<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo/>
                        </a>
                    @else
                        <a href="{{ route('user.products.index') }}">
                            <x-application-logo/>
                        </a>
                    @endif
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->check() && !auth()->user()->isAdmin())
                        <x-nav-link :href="route('user.products.index')" :active="request()->routeIs('user.products.*')" class="text-gray-300 hover:text-white">
                            {{ __('Products') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('user.products.featured')" :active="request()->routeIs('user.products.featured')" class="text-gray-300 hover:text-white">
                            {{ __('Featured') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.orders.index')" :active="request()->routeIs('user.orders.*')" class="text-gray-300 hover:text-white">
                            {{ __('My Orders') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.reviews.index')" :active="request()->routeIs('user.reviews.index')" class="text-gray-300 hover:text-white">
                            {{ __('My Reviews') }}
                        </x-nav-link>

                        <x-nav-link :href="route('user.cart.index')" :active="request()->routeIs('user.cart.*')" class="text-gray-300 hover:text-white">
                            {{ __('Cart') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->check() && auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-300 hover:text-white">
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-gray-300 hover:text-white">
                            {{ __('Manage Products') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-gray-300 hover:text-white">
                            {{ __('Categories') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-gray-300 hover:text-white">
                            {{ __('Orders') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" class="text-gray-300 hover:text-white">
                            {{ __('Reviews') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-800 hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            @auth
                            <div>{{ Auth::user()->name }}</div>
                            @endauth

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-gray-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->check() && !auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('user.products.index')" :active="request()->routeIs('user.products.*')" class="text-gray-300">
                    {{ __('Products') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('user.products.featured')" :active="request()->routeIs('user.products.featured')" class="text-gray-300">
                    {{ __('Featured') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('user.orders.index')" :active="request()->routeIs('user.orders.*')" class="text-gray-300">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('user.reviews.index')" :active="request()->routeIs('user.reviews.index')" class="text-gray-300">
                    {{ __('My Reviews') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('user.cart.index')" :active="request()->routeIs('user.cart.*')" class="text-gray-300">
                    {{ __('Cart') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->check() && auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-300">
                    {{ __('Admin Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-gray-300">
                    {{ __('Manage Products') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-gray-300">
                    {{ __('Categories') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-gray-300">
                    {{ __('Orders') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.reviews.index')" :active="request()->routeIs('admin.reviews.*')" class="text-gray-300">
                    {{ __('Reviews') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="px-4">
                @auth
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                @endauth
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-gray-300">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>