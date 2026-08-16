@extends('frontend.layouts.master')

@section('title', setting('site_name', 'SafeX Engineering') . ' - Home')

@push('styles')
<style>
    /* ============================================
       HERO SLIDER STYLES
       ============================================ */
    .hero-slider-section {
        position: relative;
        overflow: hidden;
    }

    .hero-slider-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(6, 88, 220, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    .hero-slider-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(6, 55, 161, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    .hero-slider {
        height: 100%;
        position: relative;
        z-index: 1;
    }

    .hero-slider .swiper-slide {
        height: 100%;
        min-height: 480px;
    }

    /* Animations */
    .hero-slider .swiper-slide-active .text-content {
        animation: fadeInUp 0.8s ease forwards;
    }

    .hero-slider .swiper-slide-active .image-content {
        animation: fadeInRight 0.8s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ============================================
       BUTTON STYLES
       ============================================ */
    .btn-primary {
        background: #0637A1;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #03246E;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(6, 55, 161, 0.3);
    }

    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.6);
        color: white;
        background: transparent;
        transition: all 0.3s ease;
    }
    .btn-outline-light:hover {
        background: white !important;
        color: #021447 !important;
        transform: translateY(-3px);
        border-color: white;
    }

    /* ============================================
       PRODUCT CARD
       ============================================ */
    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: 1px solid #E5E6E9;
        box-shadow: 0 2px 10px rgba(2, 20, 71, 0.04);
    }
    .product-card:hover {
        transform: translateY(-10px);
        border-color: #0637A1;
        box-shadow: 0 20px 50px rgba(2, 20, 71, 0.12);
    }
    .product-card .product-image {
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }
    .product-card .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .product-card:hover .product-image img {
        transform: scale(1.08);
    }
    .product-card .discount-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #CC2717;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(204, 39, 23, 0.3);
    }
    .product-card .wishlist-btn {
        position: absolute;
        top: 12px;
        left: 12px;
        background: white;
        color: #C2C6D0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        z-index: 2;
    }
    .product-card .wishlist-btn:hover {
        color: #CC2717;
        transform: scale(1.1);
    }
    .product-card .product-rating {
        color: #f59e0b;
        font-size: 11px;
        letter-spacing: 1px;
    }
    .product-card .add-cart-btn {
        background: #0637A1;
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .product-card .add-cart-btn:hover {
        background: #03246E;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(6, 55, 161, 0.3);
    }

    /* ============================================
       CATEGORY CARD
       ============================================ */
    .category-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: 1px solid #E5E6E9;
        text-decoration: none;
        display: block;
        box-shadow: 0 2px 10px rgba(2, 20, 71, 0.04);
    }
    .category-card:hover {
        transform: translateY(-8px);
        border-color: #0637A1;
        box-shadow: 0 20px 50px rgba(2, 20, 71, 0.12);
        text-decoration: none;
    }
    .category-card .category-image {
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }
    .category-card .category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .category-card:hover .category-image img {
        transform: scale(1.08);
    }
    .category-card .category-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(2, 20, 71, 0.7), transparent 60%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .category-card:hover .category-overlay {
        opacity: 1;
    }
    .category-card .category-count {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(8px);
        color: #021447;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        z-index: 2;
    }

    /* ============================================
       ABOUT SECTION
       ============================================ */
    .about-section {
        position: relative;
        overflow: hidden;
    }
    .about-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(6, 88, 220, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .about-section .stat-item {
        position: relative;
    }
    .about-section .stat-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 2px;
        height: 30px;
        background: rgba(255, 255, 255, 0.1);
    }

    /* ============================================
       SECTION TITLES
       ============================================ */
    .section-title {
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: #0637A1;
        border-radius: 2px;
    }
    .section-title.text-left::after {
        left: 0;
        transform: none;
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 640px) {
        .hero-slider .swiper-slide {
            min-height: 480px;
        }
        .hero-slider .swiper-slide .image-content img {
            height: 180px !important;
            object-fit: cover !important;
        }
        .about-section .stat-item:not(:last-child)::after {
            display: none;
        }
        .product-card .product-image img {
            height: 180px !important;
        }
        .category-card .category-image {
            aspect-ratio: 1/1;
        }
        .category-card .category-image img {
            height: 150px !important;
        }
        .section-title::after {
            width: 40px;
        }
    }

    @media (min-width: 641px) and (max-width: 768px) {
        .hero-slider .swiper-slide {
            min-height: 520px;
        }
        .hero-slider .swiper-slide .image-content img {
            height: 250px !important;
            object-fit: cover !important;
        }
        .product-card .product-image img {
            height: 200px !important;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .hero-slider .swiper-slide {
            min-height: 600px;
        }
        .hero-slider .swiper-slide .image-content img {
            height: 320px !important;
            object-fit: cover !important;
        }
    }

    @media (min-width: 1025px) {
        .hero-slider .swiper-slide {
            min-height: 650px;
        }
        .hero-slider .swiper-slide .image-content img {
            height: 400px !important;
            object-fit: cover !important;
        }
        .product-card .product-image img {
            height: 240px !important;
        }
        .category-card .category-image img {
            height: 200px !important;
        }
    }
</style>
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
<section class="about-section py-12 md:py-16 bg-gradient-to-r from-[#021447] to-[#03246E] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-[#0658DC]/20 rounded-full flex items-center justify-center mb-4 sm:mb-6">
            <i class="fas fa-shield-alt text-2xl sm:text-3xl text-[#0658DC]"></i>
        </div>

        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4">
            {{ setting('site_name', 'SafeX Engineering') }}
        </h2>

        <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed mb-6 sm:mb-8 px-2">
            {{ setting('about_us', 'We are committed to providing high-quality engineering products and services to our customers. With years of experience in the industry, we ensure reliability and excellence in everything we do.') }}
        </p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 max-w-3xl mx-auto mb-6 sm:mb-8">
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">2</span>
                <span class="text-[10px] sm:text-xs text-white/60">Year Warranty</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">4</span>
                <span class="text-[10px] sm:text-xs text-white/60">Free Service</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">24/7</span>
                <span class="text-[10px] sm:text-xs text-white/60">Support</span>
            </div>
            <div class="stat-item text-center">
                <span class="block text-2xl sm:text-3xl md:text-4xl font-bold text-[#0658DC]">1000+</span>
                <span class="text-[10px] sm:text-xs text-white/60">Happy Clients</span>
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
            <p class="text-[#363C54] text-sm md:text-base mt-4">Browse our wide range of engineering products by category</p>
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
            <p class="text-[#363C54] text-sm md:text-base mt-4">Discover our handpicked selection of premium engineering products</p>
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
                    <span class="text-[10px] sm:text-xs font-medium text-[#0637A1] bg-[#0637A1]/10 px-2 py-0.5 rounded-full">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>
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
                        <span class="text-[10px] text-[#C2C6D0] ml-1">(24)</span>
                    </div>
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#E5E6E9]">
                        <div>
                            <span class="text-sm sm:text-base md:text-lg font-bold text-[#0637A1]">৳ {{ number_format($product->price, 2) }}</span>
                            @if($product->discount_price)
                                <span class="text-[10px] sm:text-xs text-[#C2C6D0] line-through ml-1">৳ {{ number_format($product->selling_price, 2) }}</span>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})" class="add-cart-btn">
                            <i class="fas fa-cart-plus sm:mr-1"></i>
                            <span class="hidden sm:inline">Add</span>
                        </button>
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

    // Add to Cart Function
    function addToCart(productId) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.cart-count').textContent = data.cart_count;
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message);
                } else {
                    alert(data.message);
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message);
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Something went wrong. Please try again.');
            }
        });
    }
</script>
@endpush
