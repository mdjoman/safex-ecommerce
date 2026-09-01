@php
    // Get settings from database
    $siteName = setting('site_name', 'SafeX Engineering');
    $siteLogo = setting('site_logo', 'logo.png');
    $phone = setting('phone', '+880-2-1234567');
    $email = setting('email', 'info@safex.com');
    $address = setting('address', 'House #123, Road #45, Gulshan, Dhaka, Bangladesh');
    $workingHours = setting('working_hours', 'Sunday-Thursday: 9:00 AM - 6:00 PM');
    $whatsappNumber = setting('whatsapp_number', '+8801712345678');
    $facebookPage = setting('facebook_page', '#');
    $twitterHandle = setting('twitter_handle', '#');
    $linkedinPage = setting('linkedin_page', '#');
    $youtubeChannel = setting('youtube_channel', '#');
    $instagramPage = setting('instagram_page', '#');
    $currency = setting('currency', 'BDT');
    $currencySymbol = setting('currency_symbol', '৳');
    $maintenanceMode = setting('maintenance_mode', 'false');
    $maintenanceMessage = setting(
        'maintenance_message',
        'We are currently under maintenance. Please check back later.',
    );
@endphp

@push('header_styles')
    <link rel="stylesheet" href="{{ asset('/frontend/header.css') }}">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const toggle = document.getElementById('mobile-menu-toggle');
            const menu = document.getElementById('mobile-menu');

            if (toggle && menu) {
                toggle.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-bars');
                        icon.classList.toggle('fa-times');
                    }
                });
            }
            // Close mobile menu on outside click
            document.querySelectorAll('.relative.group > button').forEach(function(button) {

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    console.log('Button clicked');

                    const group = this.closest('.relative.group');

                    if (!group) {
                        console.log('Relative group not found');
                        return;
                    }

                    const dropdown = group.querySelector('.lang-dropdown');

                    if (!dropdown) {
                        console.log('Dropdown not found');
                        return;
                    }

                    // Toggle nearest dropdown
                    dropdown.classList.toggle('hidden');
                });

            });

            document.addEventListener('click', function(e) {

                // If click is NOT inside a dropdown group
                if (!e.target.closest('.relative.group')) {

                    document.querySelectorAll('.relative.group .lang-dropdown')
                        .forEach(function(dropdown) {
                            dropdown.classList.add('hidden');
                        });

                }

            });

            function switchLanguage(locale) {
                @if (Route::has('language.switch'))
                    fetch('{{ route('language.switch') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                locale: locale
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                toastr.error('Failed to switch language');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Something went wrong');
                        });
                @endif
            }
        });
    </script>
@endpush

<!-- Maintenance Mode Warning -->
@if ($maintenanceMode == 'true')
    <div class="bg-safex-red text-white text-center py-3 px-4 text-sm font-medium">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        {{ $maintenanceMessage }}
    </div>
@endif

<!-- Top Bar -->
<div class="bg-safex-primary-navy text-white text-sm py-1.5 border-b border-safex-dark-blue">
    <div class="max-w-7xl mx-auto  sm:px-6 ">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="top-bar-link"><i class="fas fa-phone mr-1"></i> {{ $phone }}</span>
                <span class="hidden sm:inline top-bar-link"><i class="fas fa-envelope mr-1"></i>
                    {{ $email }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Currency -->
                <span class="hidden sm:inline text-safex-medium-gray">{{ $currencySymbol }} {{ $currency }}</span>

                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 top-bar-link ">
                        <i class="fas fa-globe"></i>
                        <span
                            class="hidden sm:inline">{{ session('locale', 'en') == 'bn' ? 'বাংলা' : 'English' }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div
                        class="lang-dropdown absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg py-1 hidden z-50 border border-safex-light-gray">
                        <a href="#" onclick="switchLanguage('en')"
                            class="block px-4 py-2 text-sm text-safex-dark-gray hover:bg-safex-light-gray hover:text-safex-royal transition">
                            <i class="fas fa-language mr-2"></i> English
                        </a>
                        <a href="#" onclick="switchLanguage('bn')"
                            class="block px-4 py-2 text-sm text-safex-dark-gray hover:bg-safex-light-gray hover:text-safex-royal transition">
                            <i class="fas fa-language mr-2"></i> বাংলা
                        </a>
                    </div>
                </div>

                <!-- User Menu -->
                @auth
                    <div class="relative group">
                        <button class="flex items-center space-x-1 top-bar-link">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            class="lang-dropdown absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg py-1 hidden z-50 border border-safex-light-gray">

                            <a href="{{ route('orders.index') }}"
                                class="block px-4 py-2 text-sm text-safex-dark-gray hover:bg-safex-light-gray hover:text-safex-royal transition">
                                <i class="fas fa-shopping-bag mr-2"></i> My Orders
                            </a>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-safex-dark-gray hover:bg-safex-light-gray hover:text-safex-royal transition border-t border-safex-light-gray">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                            @endif
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="block px-4 py-2 text-sm text-safex-red hover:bg-red-50 transition border-t border-safex-light-gray">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="top-bar-link">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    {{-- <a href="{{ route('register') }}" class="top-bar-link hidden sm:inline">
                        <i class="fas fa-user-plus mr-1"></i> Register
                    </a> --}}
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav class="bg-safex-white shadow-lg sticky top-0 z-40 border-b border-safex-dark-blue">
    <div class="max-w-7xl mx-auto  sm:px-6 ">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}"
                    class="flex items-center space-x-2 hover:scale-105 transition-transform duration-300">
                    @if ($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-10 w-auto"
                            onerror="this.style.display='none'">
                    @else
                        <span class="text-2xl font-bold text-safex-bright-blue">{{ substr($siteName, 0, 1) }}</span>
                    @endif
                    <div class="logo-text">
                        <span class="main">{{ $siteName }}</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links - Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home mr-1"></i> Home
                </a>
                <a href="{{ route('products.index') }}"
                    class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes mr-1"></i> Products
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle mr-1"></i> About
                </a>
                <a href="{{ route('contact.index') }}"
                    class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope mr-1"></i> Contact
                </a>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-3">
                <!-- Search -->
                <div class="hidden md:block">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Search products..."
                            class="w-48 lg:w-64 px-4 py-2 pr-10 text-sm border border-safex-dark-blue rounded-full  placeholder-safex-medium-gray focus:outline-none focus:border-safex-bright-blue focus:ring-2 focus:ring-safex-bright-blue/20 transition">
                        <button type="submit"
                            class="absolute right-3 top-2 text-safex-medium-gray hover:text-safex-bright-blue transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative  hover:text-safex-bright-blue transition">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="cart-badge cart-count">{{ session('cart_count', 0) }}</span>
                </a>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle"
                    class="md:hidden text-safex-white hover:text-safex-bright-blue transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-safex-white border-t border-safex-light-gray shadow-lg">
        <div class="px-4 py-3 space-y-1 max-h-[calc(100vh-120px)] overflow-y-auto">
            <!-- Mobile Search -->
            <form action="{{ route('products.index') }}" method="GET" class="relative mb-3">
                <input type="text" name="search" placeholder="Search products..."
                    class="w-full px-4 py-2 pr-10 text-sm border border-safex-light-gray rounded-lg focus:outline-none focus:border-safex-royal focus:ring-2 focus:ring-safex-royal/20 transition">
                <button type="submit"
                    class="absolute right-3 top-2 text-safex-medium-gray hover:text-safex-royal transition">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            <a href="{{ route('products.index') }}"
                class="mobile-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="fas fa-boxes mr-2"></i> Products
            </a>
            <a href="{{ route('about') }}"
                class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="fas fa-info-circle mr-2"></i> About
            </a>
            <a href="{{ route('contact.index') }}"
                class="mobile-nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">
                <i class="fas fa-envelope mr-2"></i> Contact
            </a>

            @auth
                <hr class="my-2 border-safex-light-gray">

                <a href="{{ route('orders.index') }}" class="mobile-nav-link">
                    <i class="fas fa-shopping-bag mr-2"></i> My Orders
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                @endif
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="mobile-nav-link text-safex-red hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            @else
                <hr class="my-2 border-safex-light-gray">
                <a href="{{ route('login') }}" class="mobile-nav-link text-safex-royal">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
                {{-- <a href="{{ route('register') }}" class="mobile-nav-link">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a> --}}
            @endauth
        </div>
    </div>
</nav>
