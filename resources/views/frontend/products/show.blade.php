@extends('frontend.layouts.master')

@section('title', $product->name . ' - SafeX Engineering')

@push('styles')
<style>
    /* ===== PRODUCT DETAIL CONTAINER ===== */
    .product-detail-wrapper {
        background: #f8fafc;
        border-radius: 20px;
        padding: 24px;
    }

    /* ===== GALLERY ===== */
    .main-image-container {
        position: relative;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        aspect-ratio: 1 / 1;
    }

    .main-image-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
        background: #fafafa;
        padding: 20px;
    }

    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-top: 12px;
    }

    .thumbnail-item {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        aspect-ratio: 1 / 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .thumbnail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .thumbnail-item.active {
        border-color: #0637A1;
        box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.2);
    }

    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ===== STOCK STATUS ===== */
    .stock-badge-sm {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
    }

    .stock-badge-sm.in-stock {
        background: #dcfce7;
        color: #166534;
    }

    .stock-badge-sm.low-stock {
        background: #fef9c3;
        color: #854d0e;
    }

    .stock-badge-sm.sold-out {
        background: #fee2e2;
        color: #991b1b;
    }

    .stock-badge-sm .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .stock-badge-sm.in-stock .dot {
        background: #22c55e;
    }

    .stock-badge-sm.low-stock .dot {
        background: #eab308;
        animation: pulse-dot 1.5s ease-in-out infinite;
    }

    .stock-badge-sm.sold-out .dot {
        background: #ef4444;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    .stock-progress {
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 6px;
    }

    .stock-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease;
    }

    .stock-progress-bar.high { background: #22c55e; }
    .stock-progress-bar.medium { background: #eab308; }
    .stock-progress-bar.low { background: #ef4444; }

    /* ===== PRODUCT INFO ===== */
    .product-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .product-sku {
        font-size: 13px;
        color: #9ca3af;
    }

    .product-price {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .price-current {
        font-size: 28px;
        font-weight: 700;
        color: #0637A1;
    }

    .price-original {
        font-size: 18px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .price-save {
        background: #fee2e2;
        color: #dc2626;
        padding: 2px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ===== COMPACT META ===== */
    .meta-compact {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 20px;
        background: #f9fafb;
        padding: 10px 16px;
        border-radius: 10px;
        margin: 10px 0;
    }

    .meta-compact .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        color: #4b5563;
    }

    .meta-compact .meta-item .label {
        color: #9ca3af;
        font-weight: 500;
    }

    .meta-compact .meta-item .value {
        font-weight: 600;
        color: #1f2937;
    }

    /* ===== QUANTITY & BUTTONS ===== */
    .action-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin: 12px 0;
    }

    .quantity-selector {
        display: inline-flex;
        align-items: center;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .quantity-selector button {
        width: 38px;
        height: 38px;
        background: transparent;
        border: none;
        font-size: 18px;
        font-weight: 600;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-selector button:hover {
        background: #f3f4f6;
        color: #0637A1;
    }

    .quantity-selector input {
        width: 50px;
        height: 38px;
        border: none;
        border-left: 2px solid #e5e7eb;
        border-right: 2px solid #e5e7eb;
        text-align: center;
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        outline: none;
    }

    .quantity-selector input:focus {
        background: #f9fafb;
    }

    .btn-add-to-cart {
        flex: 1;
        min-width: 160px;
        background: linear-gradient(135deg, #0637A1, #0658DC);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add-to-cart:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 55, 161, 0.3);
    }

    .btn-add-to-cart:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-add-to-cart .spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    .btn-add-to-cart.loading .spinner {
        display: inline-block;
    }

    .btn-add-to-cart.loading .btn-text {
        display: none;
    }

    .btn-buy-now {
        flex: 1;
        min-width: 140px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-buy-now:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-buy-now:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        opacity: 0.7;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== SHARE BUTTONS ===== */
    .share-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        margin-top: 12px;
    }

    .share-buttons .share-label {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 500;
        margin-right: 4px;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 16px;
        background: #f3f4f6;
        color: #4b5563;
    }

    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .share-btn.facebook {
        background: #1877f2;
        color: white;
    }

    .share-btn.facebook:hover {
        background: #166fe5;
        box-shadow: 0 4px 15px rgba(24, 119, 242, 0.4);
    }

    .share-btn.twitter {
        background: #000000;
        color: white;
    }

    .share-btn.twitter:hover {
        background: #1a1a1a;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }

    .share-btn.whatsapp {
        background: #25d366;
        color: white;
    }

    .share-btn.whatsapp:hover {
        background: #20bd5a;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
    }

    .share-btn.linkedin {
        background: #0a66c2;
        color: white;
    }

    .share-btn.linkedin:hover {
        background: #0958a8;
        box-shadow: 0 4px 15px rgba(10, 102, 194, 0.4);
    }

    .share-btn.copy {
        background: #f3f4f6;
        color: #4b5563;
    }

    .share-btn.copy:hover {
        background: #e5e7eb;
        color: #0637A1;
    }

    .share-btn.share-alt {
        background: #0637A1;
        color: white;
    }

    .share-btn.share-alt:hover {
        background: #03246E;
        box-shadow: 0 4px 15px rgba(6, 55, 161, 0.4);
    }

    .share-btn .icon {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .share-btn .icon svg {
        width: 100%;
        height: 100%;
        fill: currentColor;
    }

    /* ===== TABS ===== */
    .tab-container {
        margin-top: 24px;
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .tab-nav {
        display: flex;
        border-bottom: 2px solid #f3f4f6;
        background: #fafafa;
        overflow-x: auto;
    }

    .tab-nav button {
        padding: 12px 24px;
        background: transparent;
        border: none;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        white-space: nowrap;
    }

    .tab-nav button:hover {
        color: #0637A1;
    }

    .tab-nav button.active {
        color: #0637A1;
    }

    .tab-nav button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #0637A1;
    }

    .tab-content {
        padding: 20px 24px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== PRODUCT CARD (Same as Featured) ===== */
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .wishlist-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 36px;
        height: 36px;
        background: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 2;
        color: #9ca3af;
    }

    .wishlist-btn:hover {
        background: #ef4444;
        color: white;
        transform: scale(1.1);
    }

    .wishlist-btn.active {
        color: #ef4444;
    }

    .wishlist-btn.active i {
        color: #ef4444;
    }

    /* Stock Badge */
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .stock-badge.in-stock {
        background: #dcfce7;
        color: #166534;
    }

    .stock-badge.low-stock {
        background: #fef9c3;
        color: #854d0e;
    }

    .stock-badge.sold-out {
        background: #fee2e2;
        color: #991b1b;
    }

    .stock-badge i {
        font-size: 6px;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .product-rating i {
        color: #f59e0b;
        font-size: 11px;
    }

    .add-cart-btn {
        background: linear-gradient(135deg, #0637A1, #0658DC);
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .add-cart-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(6, 55, 161, 0.3);
    }

    .add-cart-btn:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .add-cart-btn:disabled:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .product-detail-wrapper {
            padding: 16px;
        }

        .product-title {
            font-size: 22px;
        }

        .price-current {
            font-size: 24px;
        }
    }

    @media (max-width: 768px) {
        .product-detail-wrapper {
            padding: 12px;
        }

        .product-title {
            font-size: 20px;
        }

        .price-current {
            font-size: 22px;
        }

        .price-original {
            font-size: 16px;
        }

        .meta-compact {
            padding: 8px 12px;
            gap: 6px 14px;
        }

        .meta-compact .meta-item {
            font-size: 12px;
        }

        .action-row {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-add-to-cart,
        .btn-buy-now {
            min-width: unset;
            padding: 10px 16px;
            font-size: 14px;
        }

        .thumbnail-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }

        .tab-nav button {
            padding: 10px 16px;
            font-size: 13px;
        }

        .tab-content {
            padding: 14px 16px;
        }

        .share-buttons {
            gap: 6px;
        }

        .share-btn {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .product-detail-wrapper {
            padding: 8px;
        }

        .product-title {
            font-size: 18px;
        }

        .price-current {
            font-size: 20px;
        }

        .price-original {
            font-size: 14px;
        }

        .meta-compact {
            flex-direction: column;
            gap: 4px;
        }

        .thumbnail-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .tab-nav {
            flex-wrap: nowrap;
        }

        .tab-nav button {
            padding: 8px 12px;
            font-size: 12px;
        }

        .share-btn {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="/" class="hover:text-blue-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-blue-600">{{ $product->category->name ?? 'Category' }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 truncate">{{ $product->name }}</span>
    </nav>

    <!-- Product Detail -->
    <div class="product-detail-wrapper">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- ===== LEFT: GALLERY ===== -->
            <div>
                <!-- Main Image -->
                <div class="main-image-container" id="mainImageContainer">
                    <img
                        id="mainImage"
                        src="{{ asset('storage/' . $product->featured_image) }}"
                        alt="{{ $product->name }}"
                        class="cursor-zoom-in"
                        style="transform-origin: 0 0;"
                    >

                    <!-- Stock Badge Overlay -->
                    @php
                        $availableStock = $product->getAvailableStock();
                        $isInStock = $availableStock > 0;
                        $isLowStock = $availableStock > 0 && $availableStock <= 5;
                    @endphp

                    <div class="absolute top-4 left-4 z-10">
                        <span class="stock-badge-sm {{ $isInStock ? ($isLowStock ? 'low-stock' : 'in-stock') : 'sold-out' }}">
                            <span class="dot"></span>
                            @if($isInStock)
                                {{ $isLowStock ? 'Only ' . $availableStock . ' left' : 'In Stock' }}
                            @else
                                Sold Out
                            @endif
                        </span>
                    </div>

                    <!-- Zoom & Lightbox Controls -->
                    <div class="absolute bottom-4 right-4 flex space-x-2 z-10">
                        <button onclick="toggleZoom()" class="bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </button>
                        <button onclick="openLightbox()" class="bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                @php
                    $allImages = [];
                    if (!empty($product->featured_image)) {
                        $allImages[] = $product->featured_image;
                    }

                    if (!empty($product->gallery)) {
                        if (is_string($product->gallery)) {
                            $decoded = json_decode($product->gallery, true);
                            if (!is_array($decoded) || empty($decoded)) {
                                $cleanString = stripslashes($product->gallery);
                                $decoded = json_decode($cleanString, true);
                            }
                            if (!is_array($decoded) || empty($decoded)) {
                                if (str_starts_with($product->gallery, '"') && str_ends_with($product->gallery, '"')) {
                                    $inner = substr($product->gallery, 1, -1);
                                    $inner = stripslashes($inner);
                                    $decoded = json_decode($inner, true);
                                }
                            }
                            $galleryImages = is_array($decoded) ? $decoded : [];
                        } elseif (is_array($product->gallery)) {
                            $galleryImages = $product->gallery;
                        } else {
                            $galleryImages = [];
                        }
                        $allImages = array_merge($allImages, $galleryImages);
                    }
                @endphp

                @if(count($allImages) > 0)
                    <div class="thumbnail-grid">
                        @foreach($allImages as $index => $image)
                            <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ asset('storage/' . $image) }}', {{ $index }})"
                                 id="thumb-{{ $index }}">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- ===== RIGHT: PRODUCT INFO ===== -->
            <div>
                <!-- Title & SKU -->
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-sku">SKU: {{ $product->sku }}</div>

                <!-- Rating & Views -->
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <div class="flex text-yellow-400">
                        @php
                            $avgRating = $reviewStats['average'] ?? 0;
                            $fullStars = floor($avgRating);
                            $halfStar = ($avgRating - $fullStars) >= 0.5;
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <i class="fas fa-star text-xs"></i>
                            @elseif($i == $fullStars + 1 && $halfStar)
                                <i class="fas fa-star-half-alt text-xs"></i>
                            @else
                                <i class="far fa-star text-xs"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">({{ $product->views ?? 0 }} )</span>
                    <span class="text-sm text-gray-300">|</span>
                    <span class="text-sm text-gray-500">
                        <i class="far fa-eye mr-1"></i> {{ $product->views ?? 0 }} views
                    </span>
                </div>

                <!-- Price -->
                <div class="product-price mt-3">
                    <span class="price-current">{{ setting('currency', 'BDT') }} {{ number_format($product->price, 2) }}</span>
                    @if($product->discount_price)
                        <span class="price-original">{{ setting('currency', 'BDT') }} {{ number_format($product->selling_price, 2) }}</span>
                        <span class="price-save">
                            Save {{ number_format((($product->selling_price - $product->discount_price) / $product->selling_price) * 100, 0) }}%
                        </span>
                    @endif
                </div>

                <!-- Stock Progress -->
                @if($isInStock)
                    <div class="mt-1">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Product available</span>
                            @if($isLowStock)
                                <span class="text-orange-600 font-medium">⚠️ Hurry! Only few left</span>
                            @endif
                        </div>
                        @php
                            $maxStock = 100;
                            $percentage = min(($availableStock / $maxStock) * 100, 100);
                            $barClass = $percentage > 70 ? 'high' : ($percentage > 30 ? 'medium' : 'low');
                        @endphp
                        <div class="stock-progress">
                            <div class="stock-progress-bar {{ $barClass }}" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </div>
                @endif

                <!-- Compact Meta -->
                <div class="meta-compact">
                    <div class="meta-item">
                        <span class="label">Brand:</span>
                        <span class="value">{{ $product->brand?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="label">Model:</span>
                        <span class="value">{{ $product->model ?? 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="label">Category:</span>
                        <span class="value">{{ $product->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="label">Sub Category:</span>
                        <span class="value">{{ $product->subCategory->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                    <div class="text-sm text-gray-600 leading-relaxed mb-3">
                        {{ $product->short_description }}
                    </div>
                @endif

                <!-- ===== ACTION ROW: Compact ===== -->
                <div class="action-row">
                    <!-- Quantity -->
                    <div class="quantity-selector">
                        <button onclick="changeQuantity(-1)">−</button>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_qty }}">
                        <button onclick="changeQuantity(1)">+</button>
                    </div>

                    <!-- Add to Cart -->
                    <button onclick="addToCart({{ $product->id }})" id="addToCartBtn"
                            class="btn-add-to-cart {{ $product->stock_qty <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $product->stock_qty <= 0 ? 'disabled' : '' }}>
                        <span class="spinner"></span>
                        <span class="btn-text">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            {{ $product->stock_qty > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </span>
                    </button>

                    <!-- Buy Now -->
                    @if($product->stock_qty > 0)
                        <button onclick="buyNow({{ $product->id }})" class="btn-buy-now">
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    @endif
                </div>

                <!-- Delivery Info -->
              <div class="grid grid-cols-2 gap-3 mt-3">
                <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg border border-blue-100">
                    <i class="fas fa-truck text-blue-600"></i>
                    <div>
                        <p class="text-xs font-medium text-gray-800">{{ setting('delivery_title', 'Free Delivery') }}</p>
                        <p class="text-[10px] text-gray-600">{{ setting('delivery_time', '3-5 business days') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 bg-green-50 rounded-lg border border-green-100">
                    <i class="fas fa-undo-alt text-green-600"></i>
                    <div>
                        <p class="text-xs font-medium text-gray-800">{{ setting('return_title', '30 Days Return') }}</p>
                        <p class="text-[10px] text-gray-600">{{ setting('return_policy', 'Easy returns') }}</p>
                    </div>
                </div>
            </div>

                <!-- ===== SHARE BUTTONS - WORKING ===== -->
                <div class="share-buttons">
                    <span class="share-label">
                        <i class="fas fa-share-alt mr-1"></i> Share:
                    </span>

                    <!-- Share (Native) -->
                    <button onclick="shareProduct()" class="share-btn share-alt" title="Share">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Facebook -->
                    <button onclick="shareOnFacebook()" class="share-btn facebook" title="Share on Facebook">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Twitter / X -->
                    <button onclick="shareOnTwitter()" class="share-btn twitter" title="Share on Twitter">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- WhatsApp -->
                    <button onclick="shareOnWhatsApp()" class="share-btn whatsapp" title="Share on WhatsApp">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- LinkedIn -->
                    <button onclick="shareOnLinkedIn()" class="share-btn linkedin" title="Share on LinkedIn">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Copy Link -->
                    <button onclick="copyLink()" class="share-btn copy" title="Copy Link">
                        <span class="icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M13.06 8.11l1.06 1.06-6.01 6.01-1.06-1.06 6.01-6.01zm-2.12 2.12l1.06 1.06-3.01 3.01-1.06-1.06 3.01-3.01zm9.09-5.31l-1.96-1.96a1.5 1.5 0 00-2.12 0L3.21 17.18a1.5 1.5 0 000 2.12l1.96 1.96a1.5 1.5 0 002.12 0l12.86-12.86a1.5 1.5 0 000-2.12z"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== TABS ===== -->
        <div class="tab-container">
            <div class="tab-nav">
                <button class="active" onclick="switchTab(this, 'description')">Description</button>
                @if($product->specification)
                    <button onclick="switchTab(this, 'specifications')">Specifications</button>
                @endif
                <button onclick="switchTab(this, 'reviews')">
                    Reviews ({{ $reviewStats['total'] ?? 0 }})
                </button>
            </div>

            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="tab-description">
                    <div class="prose max-w-none text-gray-700 leading-relaxed text-sm">
                        {!! $product->long_description ?? $product->short_description ?? 'No description available.' !!}
                    </div>
                </div>

                <!-- Specifications Tab -->
                @if($product->specification)
                    <div class="tab-pane" id="tab-specifications">
                        <div class="prose max-w-none text-gray-700 leading-relaxed text-sm">
                            {!! $product->specification !!}
                        </div>
                    </div>
                @endif

                <!-- Reviews Tab -->
                <div class="tab-pane" id="tab-reviews">
                    @if(($reviewStats['total'] ?? 0) > 0)
                        <div class="space-y-4">
                            @foreach($product->reviews ?? [] as $review)
                                <div class="border-b border-gray-100 pb-4 last:border-0">
                                    <div class="flex items-center gap-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-xs"></i>
                                                @else
                                                    <i class="far fa-star text-xs"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium">{{ $review->title ?? 'Review' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-4xl text-gray-300 mb-3">
                                <i class="far fa-comments"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700">No reviews yet</h3>
                            <p class="text-sm text-gray-500 mt-1">Be the first to review this product</p>
                            @auth
                                <button class="mt-3 px-5 py-2 bg-[#0637A1] text-white text-sm rounded-lg hover:bg-[#03246E] transition">
                                    Write a Review
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="mt-3 inline-block px-5 py-2 bg-[#0637A1] text-white text-sm rounded-lg hover:bg-[#03246E] transition">
                                    Login to Review
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===== RELATED PRODUCTS (Like Featured) ===== -->
        @if($relatedProducts->count())
        <section class="py-8 md:py-12 bg-white" style="background: transparent;">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-6 md:mb-10">
                    <h2 class="section-title text-2xl sm:text-3xl md:text-4xl font-bold text-[#021447]">
                        <span class="text-[#0637A1]">Related</span> Products
                    </h2>
                    <p class="text-[#363C54] text-sm md:text-base mt-3">Discover more products you might like</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @php
                            $relatedStock = $relatedProduct->getAvailableStock();
                            $relatedInStock = $relatedStock > 0;
                            $relatedLowStock = $relatedStock > 0 && $relatedStock <= 5;
                        @endphp
                        <div class="product-card">
                            <div class="product-image" style="height: 180px; sm:height: 200px; md:height: 240px;">
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="block h-full">
                                    @if($relatedProduct->discount_price)
                                    <span class="discount-badge">
                                        -{{ number_format((($relatedProduct->selling_price - $relatedProduct->discount_price) / $relatedProduct->selling_price) * 100, 0) }}%
                                    </span>
                                    @endif
                                    @php
                                        $imagePath = $relatedProduct->featured_image;
                                        $isUrl = filter_var($imagePath, FILTER_VALIDATE_URL);
                                    @endphp
                                    @if($isUrl)
                                        <img src="{{ $imagePath }}"
                                             alt="{{ $relatedProduct->name }}"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    @else
                                        <img src="{{ asset('storage/' . $imagePath) }}"
                                             alt="{{ $relatedProduct->name }}"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    @endif
                                </a>
                                <button class="wishlist-btn" onclick="event.preventDefault(); toggleWishlist({{ $relatedProduct->id }}, this)">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-3 sm:p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] sm:text-xs font-medium text-[#0637A1] bg-[#0637A1]/10 px-2 py-0.5 rounded-full">
                                        {{ $relatedProduct->category->name ?? 'Uncategorized' }}
                                    </span>
                                    @if($relatedInStock)
                                        <span class="text-[10px] sm:text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">
                                            <i class="fas fa-circle text-[6px] text-green-600 mr-1"></i>
                                            {{ $relatedLowStock ? 'Only ' . $relatedStock . ' left' : 'In Stock' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] sm:text-xs font-medium text-red-600 bg-red-100 px-2 py-0.5 rounded-full">
                                            <i class="fas fa-circle text-[6px] text-red-600 mr-1"></i>
                                            Sold Out
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xs sm:text-sm md:text-base font-semibold text-[#021447] mt-1.5 mb-0.5 line-clamp-1">
                                    <a href="{{ route('product.show', $relatedProduct->slug) }}" class="hover:text-[#0637A1] transition-colors">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>

                                <div class="product-rating mt-1">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-[10px] text-[#C2C6D0] ml-1">({{ $relatedProduct->views ?? 0 }})</span>
                                </div>

                                <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#E5E6E9]">
                                    <div>
                                        <span class="text-sm sm:text-base md:text-lg font-bold text-[#0637A1]">{{ setting('currency', 'BDT') }} {{ number_format($relatedProduct->price, 2) }}</span>
                                        @if($relatedProduct->discount_price)
                                            <span class="text-[10px] sm:text-xs text-[#C2C6D0] line-through ml-1">{{ setting('currency', 'BDT') }} {{ number_format($relatedProduct->selling_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($relatedInStock)
                                        <button onclick="addToCart({{ $relatedProduct->id }})" class="add-cart-btn">
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
            </div>
        </section>
        @endif
    </div>
</div>

<!-- ===== LIGHTBOX MODAL ===== -->
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-95 flex items-center justify-center">
    <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white text-5xl hover:text-gray-300 transition z-50">
        ×
    </button>
    <button onclick="prevImage()" class="absolute left-6 text-white text-5xl hover:text-gray-300 transition z-50">
        ‹
    </button>
    <button onclick="nextImage()" class="absolute right-6 text-white text-5xl hover:text-gray-300 transition z-50">
        ›
    </button>
    <div class="relative max-w-6xl max-h-screen p-8">
        <img id="lightboxImage" src="" alt="Product" class="max-w-full max-h-[80vh] object-contain">
    </div>
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white text-sm bg-black bg-opacity-50 px-4 py-2 rounded-full">
        <span id="lightboxCounter">1 / 0</span>
    </div>
</div>

@push('scripts')
<script>
    let currentIndex = 0;
    let allImages = @json($allImages);
    let autoSlideInterval = null;
    let isZoomEnabled = false;
    let lightboxOpen = false;

    // ===== QUANTITY =====
    function changeQuantity(change) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + change;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 999;

        if (value < min) value = min;
        if (value > max) value = max;

        input.value = value;
    }

    // ===== GALLERY =====
    function changeMainImage(src, index) {
        stopAutoSlide();
        currentIndex = index || 0;

        const mainImage = document.getElementById('mainImage');
        mainImage.src = src;

        document.querySelectorAll('.thumbnail-item').forEach(el => {
            el.classList.remove('active');
        });
        const activeThumb = document.getElementById('thumb-' + currentIndex);
        if (activeThumb) {
            activeThumb.classList.add('active');
        }

        if (lightboxOpen) {
            document.getElementById('lightboxImage').src = src;
            document.getElementById('lightboxCounter').textContent = (currentIndex + 1) + ' / ' + allImages.length;
        }

        if (isZoomEnabled) {
            updateZoom();
        }
    }

    // ===== AUTO SLIDE =====
    function startAutoSlide() {
        if (allImages.length > 1) {
            autoSlideInterval = setInterval(() => {
                let nextIndex = (currentIndex + 1) % allImages.length;
                changeMainImage('{{ asset('storage/') }}/' + allImages[nextIndex], nextIndex);
            }, 4000);
        }
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }

    // ===== ZOOM =====
    function toggleZoom() {
        isZoomEnabled = !isZoomEnabled;
        const mainImage = document.getElementById('mainImage');
        const container = document.getElementById('mainImageContainer');

        if (isZoomEnabled) {
            mainImage.classList.add('cursor-zoom-in');
            container.addEventListener('mousemove', updateZoom);
            container.addEventListener('mouseleave', hideZoom);
        } else {
            mainImage.classList.remove('cursor-zoom-in');
            mainImage.style.transform = 'scale(1)';
            container.removeEventListener('mousemove', updateZoom);
            container.removeEventListener('mouseleave', hideZoom);
        }
    }

    function updateZoom(e) {
        if (!isZoomEnabled) return;

        const mainImage = document.getElementById('mainImage');
        const container = document.getElementById('mainImageContainer');
        const rect = container.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        if (x < 0 || y < 0 || x > rect.width || y > rect.height) {
            hideZoom();
            return;
        }

        const xPercent = x / rect.width;
        const yPercent = y / rect.height;

        mainImage.style.transform = 'scale(2.5)';
        mainImage.style.transformOrigin = (xPercent * 100) + '% ' + (yPercent * 100) + '%';
    }

    function hideZoom() {
        const mainImage = document.getElementById('mainImage');
        mainImage.style.transform = 'scale(1)';
    }

    // ===== LIGHTBOX =====
    function openLightbox() {
        lightboxOpen = true;
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        lightboxImage.src = document.getElementById('mainImage').src;
        document.getElementById('lightboxCounter').textContent = (currentIndex + 1) + ' / ' + allImages.length;
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        stopAutoSlide();
    }

    function closeLightbox() {
        lightboxOpen = false;
        document.getElementById('lightbox').classList.add('hidden');
        document.body.style.overflow = '';
        startAutoSlide();
    }

    function prevImage() {
        if (allImages.length > 0) {
            let index = (currentIndex - 1 + allImages.length) % allImages.length;
            changeMainImage('{{ asset('storage/') }}/' + allImages[index], index);
        }
    }

    function nextImage() {
        if (allImages.length > 0) {
            let index = (currentIndex + 1) % allImages.length;
            changeMainImage('{{ asset('storage/') }}/' + allImages[index], index);
        }
    }

    // ===== TABS =====
    function switchTab(button, tabId) {
        document.querySelectorAll('.tab-nav button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');

        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });
        document.getElementById('tab-' + tabId).classList.add('active');
    }

    // ===== SHARE FUNCTIONS =====
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: 'Check out this product: {{ $product->name }}',
                url: window.location.href
            }).catch(() => {});
        } else {
            copyLink();
        }
    }

    function shareOnFacebook() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent('{{ $product->name }}');
        window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`,
            'facebook-share-dialog',
            'width=626,height=436'
        );
    }

    function shareOnTwitter() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Check out this product: {{ $product->name }}');
        window.open(
            `https://twitter.com/intent/tweet?text=${text}&url=${url}`,
            'twitter-share-dialog',
            'width=626,height=436'
        );
    }

    function shareOnWhatsApp() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Check out this product: {{ $product->name }} - ');
        window.open(
            `https://api.whatsapp.com/send?text=${text}${url}`,
            'whatsapp-share-dialog',
            'width=626,height=436'
        );
    }

    function shareOnLinkedIn() {
        const url = encodeURIComponent(window.location.href);
        const title = encodeURIComponent('{{ $product->name }}');
        window.open(
            `https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`,
            'linkedin-share-dialog',
            'width=626,height=436'
        );
    }

    function copyLink() {
        const url = window.location.href;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Link copied to clipboard! 📋', 'success');
            }).catch(() => {
                fallbackCopy(url);
            });
        } else {
            fallbackCopy(url);
        }
    }

    function fallbackCopy(text) {
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        input.remove();
        showNotification('Link copied to clipboard! 📋', 'success');
    }

    // ===== ADD TO CART =====
    function addToCart(productId) {
        const quantity = document.getElementById('quantity')?.value || 1;
        const btn = document.getElementById('addToCartBtn');
        if (!btn) return;

        btn.classList.add('loading');
        btn.disabled = true;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) cartCount.textContent = data.cart_count;
                showNotification('Product added to cart! 🎉', 'success');
            } else {
                showNotification(data.message || 'Error adding product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            btn.classList.remove('loading');
            btn.disabled = false;
        });
    }

    // ===== BUY NOW =====
    function buyNow(productId) {
        const quantity = document.getElementById('quantity')?.value || 1;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('cart.index') }}';
            } else {
                showNotification(data.message || 'Error adding product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    }

    // ===== NOTIFICATION =====
    function showNotification(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${colors[type] || 'bg-blue-500'} text-white px-5 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-500 translate-x-full max-w-sm text-sm`;
        notification.innerHTML = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3500);
    }

    // ===== WISHLIST =====
    function toggleWishlist(productId, element) {
        const icon = element.querySelector('i');
        icon.classList.toggle('far');
        icon.classList.toggle('fas');
        element.classList.toggle('active');

        if (icon.classList.contains('fas')) {
            Swal.fire({
                icon: 'success',
                title: 'Added to Wishlist!',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    }

    // ===== KEYBOARD NAVIGATION =====
    document.addEventListener('keydown', function(e) {
        if (lightboxOpen) {
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });

    // ===== INITIALIZE =====
    document.addEventListener('DOMContentLoaded', function() {
        startAutoSlide();
    });

    document.querySelectorAll('.thumbnail-item').forEach(el => {
        el.addEventListener('click', () => {
            stopAutoSlide();
            setTimeout(() => startAutoSlide(), 5000);
        });
    });
</script>
@endpush
@endsection
