<nav x-data="{ open: false, cartCount: 0 }" x-init="
    // Initialize cart count on page load
    cartCount = JSON.parse(localStorage.getItem('cart') || '[]').length;
    
    // Listen for storage changes to update count
    window.addEventListener('storage', () => {
        cartCount = JSON.parse(localStorage.getItem('cart') || '[]').length;
    });
" class="bg-[#505355] shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-9xl mx-auto px-4 sm:px-10 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo on the left -->
            <div class="flex-shrink-0 flex items-center ml-2 sm:ml-10">
                <a href="{{ route('dashboard') }}" class="flex items-center group py-2">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-16 w-auto transition-transform duration-200 group-hover:scale-105">
                </a>
            </div>

            <!-- Navigation links on the right -->
            <div class="hidden md:flex items-center space-x-8 mr-0 sm:mr-8">
                <!-- Other nav links remain the same -->

                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" 
                    class="text-white hover:text-[#c7ccd5] font-medium text-sm uppercase tracking-wider transition-colors duration-200">
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link href="{{ route('user.album-search') }}" :active="request()->routeIs('user.album-search')" 
                    class="text-white hover:text-[#c7ccd5] font-medium text-sm uppercase tracking-wider transition-colors duration-200">
                    {{ __('Albums') }}
                </x-nav-link>
                <x-nav-link href="{{ route('user.orders.list') }}" :active="request()->routeIs('user.orders.list')" 
                    class="text-white hover:text-[#c7ccd5] font-medium text-sm uppercase tracking-wider transition-colors duration-200">
                    {{ __('Orders') }}
                </x-nav-link>
                <x-nav-link href="{{ route('user.review') }}" :active="request()->routeIs('user.review')" 
                    class="text-white hover:text-[#c7ccd5] font-medium text-sm uppercase tracking-wider transition-colors duration-200">
                    {{ __('Reviews') }}
                </x-nav-link>
                
                <!-- Cart Link -->
                <x-nav-link href="{{ route('user.cart') }}" :active="request()->routeIs('user.cart')" 
                    class="text-white hover:text-[#c7ccd5] font-medium text-sm uppercase tracking-wider transition-colors duration-200 flex items-center">
                    <i class='bx bx-cart mr-1'></i>
                    <span x-text="cartCount" class="ml-1 bg-[#757474] text-white text-xs font-semibold px-2 py-0.5 rounded-full"></span>
                </x-nav-link>

                <!-- Teams Dropdown -->
                

                <!-- Settings Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-[#D1D5DB] transition-all duration-200 hover:ring-2 hover:ring-[#D1D5DB]">
                                    <img class="size-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white hover:text-[#D1D5DB] focus:outline-none transition-all duration-200 group">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4 group-hover:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>


                        <x-slot name="content">
                            <div class="bg-[#505355] border border-[#6B7280] rounded-lg shadow-xl py-1">
                                <x-dropdown-link href="{{ route('profile.show') }}" class="text-white hover:bg-[#9b9b9bf1] hover:text-white px-4 py-2 text-sm transition-colors duration-150">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        @click.prevent="$root.submit();"
                                        class="text-white hover:bg-[#9b9b9bf1] hover:text-white px-4 py-2 text-sm transition-colors duration-150">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden mr-1">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] focus:outline-none transition-all duration-200">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-[#505355] border-t border-[#6B7280]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('user.album-search') }}" :active="request()->routeIs('user.album-search')"
                class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                {{ __('Albums') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  href="{{ route('user.orders.list') }}" :active="request()->routeIs('user.orders.list')"
                class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                {{ __('Orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link  href="{{ route('user.review') }}" :active="request()->routeIs('user.review')"
                class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                {{ __('Orders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('user.cart') }}" :active="request()->routeIs('user.cart')"
                class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                <i class='bx bx-cart'></i>
                <span x-text="cartCount" class="ml-1 bg-[#6366F1] text-white text-xs font-semibold px-2 py-0.5 rounded-full"></span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-[#6B7280]">
            <div class="flex items-center px-4 py-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover ring-2 ring-[#6B7280]" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @else
                    <div class="h-10 w-10 rounded-full bg-[#6B7280] flex items-center justify-center text-white font-medium me-3 ring-2 ring-[#6B7280]">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                @endif

                <div>
                    <div class="font-semibold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-[#D1D5DB]">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')"
                    class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}"
                        class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                        @click.prevent="$root.submit();"
                        class="block px-4 py-3 text-base font-medium text-white hover:text-[#D1D5DB] hover:bg-[#6B7280] transition-colors duration-200">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>