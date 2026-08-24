@extends('frontend.layouts.master')

@section('title', setting('site_name', 'SafeX Engineering') . ' - Home')

@push('styles')
    <link rel="stylesheet" href="{{asset('/frontend/home.css')}}">
@endpush

@section('content')

<!-- ============================================
     HERO SLIDER
     ============================================ -->
@if($banners->count())
<section class="hero-slider-section">
    <div class="swiper-container hero-slider">
        <div class="swiper-wrapper">
            @foreach($banners as $banner)
            <div class="swiper-slide" style="{{ $banner->background_style ?? 'background: linear-gradient(135deg, #021447 0%, #03246E 100%);' }}">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-full">
                    <div class="flex flex-col md:flex-row items-center min-h-[480px] md:min-h-[600px] lg:min-h-[650px] py-8 md:py-12">

                        <!-- Left Content -->
                        <div class="flex-1 text-center md:text-left z-10 px-3 sm:px-4 md:pr-8 lg:pr-12 w-full md:w-1/2" style="color: {{ $banner->text_color ?? '#FFFFFF' }}">
                            <div class="text-content">
                                @if($banner->badge)
                                <span class="inline-block bg-[#0658DC] text-white text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1.5 rounded-full mb-2 sm:mb-4 tracking-wider uppercase">
                                    {{ $banner->badge }}
                                </span>
                                @endif

                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight mb-2 sm:mb-4">
                                    {{ $banner->title }}
                                </h1>

                                @if($banner->description)
                                <p class="text-sm sm:text-base md:text-lg max-w-lg mx-auto md:mx-0 mb-3 sm:mb-6 leading-relaxed" style="color: {{ $banner->text_color ?? '#FFFFFF' }}; opacity: 0.85;">
                                    {{ $banner->description }}
                                </p>
                                @endif

                                <div class="flex flex-wrap gap-3 sm:gap-6 mb-3 sm:mb-6 justify-center md:justify-start">
                                    @foreach($banner->stats as $stat)
                                    <div class="text-center">
                                        <span class="block text-lg sm:text-2xl md:text-3xl font-bold text-[#0658DC]">{{ $stat['value'] }}</span>
                                        <span class="text-[8px] sm:text-xs uppercase tracking-wider" style="color: {{ $banner->text_color ?? '#FFFFFF' }}; opacity: 0.6;">
                                            {{ $stat['label'] }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="flex flex-wrap gap-2 sm:gap-4 justify-center md:justify-start">
                                    @if($banner->button_url)
                                    <a href="{{ $banner->button_url }}"
                                       class="btn-primary inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 hover:scale-105 shadow-lg">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        {{ $banner->button_text ?? 'Shop Now' }}
                                    </a>
                                    @endif
                                    <a href="#categories" class="btn-outline-light inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 hover:scale-105">
                                        <i class="fas fa-th-large mr-2"></i>
                                        Browse Categories
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Image - Full Width -->
                        <div class="flex-1 mt-4 md:mt-0 flex justify-center md:justify-end px-0 w-full md:w-1/2">
                            <div class="image-content relative w-full">
                                @php
                                    $imagePath = $banner->image;
                                    // Check if it's a URL or local path
                                    $isUrl = filter_var($imagePath, FILTER_VALIDATE_URL);
                                @endphp
                                @if($isUrl)
                                    <img src="{{ $imagePath }}"
                                         alt="{{ $banner->title }}"
                                         class="w-full rounded-2xl shadow-2xl"
                                         style="height: 200px; object-fit: cover;"
                                         loading="lazy">
                                @else
                                    <img src="{{ asset('storage/' . $imagePath) }}"
                                         alt="{{ $banner->title }}"
                                         class="w-full rounded-2xl shadow-2xl"
                                         style="height: 200px; object-fit: cover;"
                                         loading="lazy">
                                @endif

                                @if($banner->badge)
                                <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 bg-[#CC2717] text-white text-[10px] sm:text-sm font-bold px-2 sm:px-4 py-1 sm:py-2 rounded-lg shadow-lg rotate-6 hidden sm:block">
                                    <i class="fas fa-fire mr-1"></i> {{ $banner->badge }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!-- ============================================
     ABOUT SECTION
     ============================================ -->
<section class="about-section py-12 md:py-16 bg-gradient-to-r from-[#021447] to-[#111827] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-[#0658DC]/20 rounded-full flex items-center justify-center mb-4 sm:mb-6">
            <i class="fas fa-shield-alt text-2xl sm:text-3xl text-[#0658DC]"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4">
            {{ setting('about_title', 'SafeX Engineering') }}
        </h2>

        <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed mb-6 sm:mb-8 px-2">
            {{ setting('about_us', 'We are committed to providing high-quality engineering products and services to our customers. With years of experience in the industry, we ensure reliability and excellence in everything we do.') }}
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 max-w-3xl mx-auto mb-6 sm:mb-8">
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">{{ setting('about_sub_data_1', '2') }}</span>
                <span class="text-[10px] sm:text-xs text-white/60">{{ setting('about_sub_title_1', 'Service years') }}</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">{{ setting('about_sub_data_2', '4') }}</span>
                <span class="text-[10px] sm:text-xs text-white/60">{{ setting('about_sub_title_2', 'Free Service') }}</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">{{ setting('about_sub_data_3', '24/7') }}</span>
                <span class="text-[10px] sm:text-xs text-white/60">{{ setting('about_sub_title_3', 'Support') }}</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">{{ setting('about_sub_data_4', '2') }}</span>
                <span class="text-[10px] sm:text-xs text-white/60">{{ setting('about_sub_title_4', 'Happy Clients') }}</span>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
            <a href="{{ route('about') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-[#0658DC] hover:bg-[#0637A1] text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-[#0658DC]/30">
                Learn More
                <i class="fas fa-arrow-right"></i>
            </a>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-white/50 hover:border-white text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 hover:scale-105 hover:bg-white/10">
                <i class="fas fa-envelope"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>
<!-- ============================================
     CATEGORIES
     ============================================ -->
@if($categories->count())
<section class="py-12 md:py-16 bg-[#F4F7FA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="section-title text-2xl sm:text-3xl md:text-4xl font-bold text-[#021447]">
                Shop by <span class="text-[#0637A1]">Category</span>
            </h2>
            <p class="text-[#363C54] text-sm md:text-base mt-4">{{ setting('category_section_description', 'Browse our wide range of engineering products by category') }}</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                <div class="category-image" style="height: 150px; sm:height: 180px; md:height: 200px;">
                    @php
                        $imagePath = $category->image;
                        $isUrl = filter_var($imagePath, FILTER_VALIDATE_URL);
                    @endphp
                    @if($imagePath && $isUrl)
                        <img src="{{ $imagePath }}"
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover"
                             loading="lazy">
                    @elseif($imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}"
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover"
                             loading="lazy">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#0637A1]/10 to-[#E5E6E9] flex items-center justify-center">
                            <i class="fas fa-folder-open text-3xl sm:text-4xl text-[#0637A1]/40"></i>
                        </div>
                    @endif
                    <div class="category-overlay"></div>
                    <span class="category-count">
                        <i class="fas fa-box mr-1"></i> {{ $category->products->count() }}
                    </span>
                </div>
                <div class="p-2 sm:p-3 text-center">
                    <h3 class="text-sm sm:text-base font-semibold text-[#021447] group-hover:text-[#0637A1] transition-colors">
                        {{ $category->name }}
                    </h3>
                    <p class="text-[10px] sm:text-xs text-[#C2C6D0] mt-0.5">
                        {{ $category->products->count() }} products available
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
<!-- ============================================
     FEATURED PRODUCTS
     ============================================ -->
@if($featuredProducts->count())
<section class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="section-title text-2xl sm:text-3xl md:text-4xl font-bold text-[#021447]">
                <span class="text-[#0637A1]">Featured</span> Products
            </h2>
            <p class="text-[#363C54] text-sm md:text-base mt-4">{{ setting('product_section_description', 'Discover our handpicked selection of premium engineering products') }}</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image" style="height: 180px; sm:height: 200px; md:height: 240px;">
                    <a href="{{ route('product.show', $product->slug) }}" class="block h-full">
                        @if($product->discount_price)
                        <span class="discount-badge">
                            -{{ number_format((($product->selling_price - $product->discount_price) / $product->selling_price) * 100, 0) }}%
                        </span>
                        @endif
                        @php
                            $imagePath = $product->featured_image;
                            $isUrl = filter_var($imagePath, FILTER_VALIDATE_URL);
                        @endphp
                        @if($isUrl)
                            <img src="{{ $imagePath }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                                loading="lazy">
                        @else
                            <img src="{{ asset('storage/' . $imagePath) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                                loading="lazy">
                        @endif
                    </a>
                    <button class="wishlist-btn" onclick="event.preventDefault();">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="p-3 sm:p-4">
                    <!-- Category and Stock Status -->
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] sm:text-xs font-medium text-[#0637A1] bg-[#0637A1]/10 px-2 py-0.5 rounded-full">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                        <!-- Stock Status -->
                        @php
                            $availableStock = $product->getAvailableStock();
                        @endphp
                        @if($availableStock > 0)
                            <span class="text-[10px] sm:text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                                <i class="fas fa-circle text-[6px] text-green-600 mr-1"></i>
                                In Stock
                            </span>
                        @else
                            <span class="text-[10px] sm:text-xs font-medium text-red-600 bg-red-100 px-2 py-0.5 rounded-full">
                                <i class="fas fa-circle text-[6px] text-red-600 mr-1"></i>
                                Sold Out
                            </span>
                        @endif
                    </div>

                    <h3 class="text-xs sm:text-sm md:text-base font-semibold text-[#021447] mt-1.5 mb-0.5 line-clamp-1">
                        <a href="{{ route('product.show', $product->slug) }}" class="hover:text-[#0637A1] transition-colors">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <div class="product-rating mt-1">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="text-[10px] text-[#C2C6D0] ml-1">({{ $product->views }})</span>
                    </div>

                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#E5E6E9]">
                        <div>
                            <span class="text-sm sm:text-base md:text-lg font-bold text-[#0637A1]">{{setting('currency_symbol', '৳')}} {{ number_format($product->price, 2) }}</span>
                            @if($product->discount_price)
                                <span class="text-[10px] sm:text-xs text-[#C2C6D0] line-through ml-1">{{setting('currency_symbol', '৳')}} {{ number_format($product->selling_price, 2) }}</span>
                            @endif
                        </div>
                        @if($product->getAvailableStock() > 0)
                            <button onclick="addToCart({{ $product->id }})" class="add-cart-btn">
                                <i class="fas fa-cart-plus sm:mr-1"></i>
                                <span class="hidden sm:inline">Add</span>
                            </button>
                        @else
                            <button class="add-cart-btn bg-gray-300 hover:bg-gray-300 cursor-not-allowed opacity-60" disabled>
                                <i class="fas fa-times-circle sm:mr-1"></i>
                                <span class="hidden sm:inline">Out of Stock</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8 md:mt-12">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 bg-[#0637A1] hover:bg-[#03246E] text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-[#0637A1]/30">
                View All Products
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hero Slider
        const heroSlider = document.querySelector('.hero-slider');
        if (heroSlider) {
            new Swiper(heroSlider, {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                speed: 1000,
                allowTouchMove: false,
                autoHeight: true,
            });
        }

        // Wishlist button toggle
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const icon = this.querySelector('i');
                icon.classList.toggle('far');
                icon.classList.toggle('fas');
                icon.classList.toggle('text-[#CC2717]');
                if (icon.classList.contains('fas')) {
                    this.style.color = '#CC2717';
                } else {
                    this.style.color = '#C2C6D0';
                }
            });
        });
    });
</script>
@endpush
