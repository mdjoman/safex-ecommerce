@extends('frontend.layouts.master')

@section('title', 'Products - SafeX Engineering')

@push('styles')
<style>
    /* ===== CUSTOM SCROLLBAR ===== */

    /* ===== SIDEBAR - SMOOTH SCROLL ===== */
    .sidebar-wrapper {
        position: sticky;
        top: 20px;
        overflow-y: auto;
        padding-right: 8px;
        scroll-behavior: smooth;
        scrollbar-width: thin;
    }




    /* ===== PRODUCT CARD ===== */
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
        height: 200px;
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

    .product-card .p-3 {
        padding: 12px;
    }

    .product-card .category-tag {
        font-size: 10px;
        font-weight: 500;
        color: #0637A1;
        background: rgba(6, 55, 161, 0.1);
        padding: 2px 10px;
        border-radius: 20px;
        display: inline-block;
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

    .product-name {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        margin: 6px 0 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-name a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .product-name a:hover {
        color: #0637A1;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 2px;
        margin: 4px 0 8px;
    }

    .product-rating i {
        color: #f59e0b;
        font-size: 11px;
    }

    .product-rating .rating-count {
        font-size: 10px;
        color: #9ca3af;
        margin-left: 4px;
    }

    .product-price {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: auto;
        padding-top: 8px;
        border-top: 1px solid #f3f4f6;
    }

    .price-current {
        font-size: 16px;
        font-weight: 700;
        color: #0637A1;
    }

    .price-original {
        font-size: 12px;
        color: #9ca3af;
        text-decoration: line-through;
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
        opacity: 0.7;
    }

    /* ===== RESULTS SECTION ===== */
    .results-section {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .results-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .results-info .results-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(6, 55, 161, 0.1), rgba(6, 88, 220, 0.1));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0637A1;
        font-size: 18px;
    }

    .results-info .results-text {
        display: flex;
        flex-direction: column;
    }

    .results-info .results-text .main-text {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }

    .results-info .results-text .main-text strong {
        color: #0637A1;
    }

    .results-info .results-text .sub-text {
        font-size: 13px;
        color: #6b7280;
    }

    .results-info .results-text .sub-text .highlight {
        color: #0637A1;
        font-weight: 500;
    }

    /* ===== DROPDOWN ===== */
    .dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    .dropdown-trigger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 160px;
        justify-content: space-between;
    }

    .dropdown-trigger:hover {
        border-color: #0637A1;
        box-shadow: 0 2px 8px rgba(6, 55, 161, 0.1);
    }

    .dropdown-trigger .dropdown-icon {
        transition: transform 0.3s ease;
        font-size: 12px;
        color: #9ca3af;
    }

    .dropdown-trigger.open .dropdown-icon {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        padding: 8px;
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) scale(0.95);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 50;
        border: 1px solid #f3f4f6;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #374151;
        font-size: 13px;
        text-decoration: none;
    }

    .dropdown-menu .dropdown-item:hover {
        background: #f3f4f6;
        color: #0637A1;
    }

    .dropdown-menu .dropdown-item.active {
        background: rgba(6, 55, 161, 0.1);
        color: #0637A1;
        font-weight: 600;
    }

    .dropdown-menu .dropdown-item .check-icon {
        margin-left: auto;
        color: #0637A1;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .dropdown-menu .dropdown-item.active .check-icon {
        opacity: 1;
    }

    /* ===== FILTER SIDEBAR ===== */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 16px 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 12px;
    }

    .filter-card:last-child {
        margin-bottom: 0;
    }

    .filter-card h3 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }

    .filter-card h3 i {
        color: #0637A1;
    }

    .filter-card h3 .toggle-icon {
        font-size: 12px;
        color: #9ca3af;
        transition: transform 0.3s ease;
    }

    .filter-card h3 .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    .filter-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: auto;
    }



    .filter-list li {
        margin-bottom: 4px;
    }

    .filter-list li:last-child {
        margin-bottom: 0;
    }

    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 8px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #4b5563;
        font-size: 13px;
        text-decoration: none;
    }

    .filter-checkbox:hover {
        background: #f3f4f6;
    }

    .filter-checkbox input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #0637A1;
        cursor: pointer;
        flex-shrink: 0;
    }

    .filter-checkbox .count {
        margin-left: auto;
        font-size: 11px;
        color: #9ca3af;
        background: #f3f4f6;
        padding: 0 8px;
        border-radius: 12px;
    }

    .filter-checkbox .check-mark {
        color: #0637A1;
        margin-left: auto;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .filter-checkbox input:checked ~ .check-mark {
        opacity: 1;
    }

    /* ===== PRICE RANGE ===== */
    .price-range-container {
        padding: 4px 0;
    }

    .price-range-slider {
        width: 100%;
        height: 6px;
        -webkit-appearance: none;
        appearance: none;
        background: #e5e7eb;
        border-radius: 3px;
        outline: none;
        transition: background 0.2s;
    }

    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #0637A1;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(6, 55, 161, 0.3);
        transition: all 0.2s;
    }

    .price-range-slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(6, 55, 161, 0.4);
    }

    .price-range-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #0637A1;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 8px rgba(6, 55, 161, 0.3);
    }

    .price-range-values {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        font-size: 13px;
        color: #4b5563;
    }

    .price-range-values .min-price,
    .price-range-values .max-price {
        font-weight: 600;
        color: #0637A1;
    }

    .btn-apply-filter {
        width: 100%;
        margin-top: 10px;
        padding: 8px;
        background: #0637A1;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-apply-filter:hover {
        background: #03246E;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(6, 55, 161, 0.3);
    }

    /* ===== CLEAR FILTERS ===== */
    .clear-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        padding: 8px 0;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(6, 55, 161, 0.1);
        color: #0637A1;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .filter-tag .remove-filter {
        cursor: pointer;
        font-size: 12px;
        color: #0637A1;
        transition: color 0.2s;
    }

    .filter-tag .remove-filter:hover {
        color: #ef4444;
    }

    /* ===== PAGINATION - CENTER ===== */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 32px;
    }

    .pagination-wrapper .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-wrapper .pagination .page-item {
        list-style: none;
    }

    .pagination-wrapper .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        color: #4b5563;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        background: white;
    }

    .pagination-wrapper .pagination .page-link:hover:not(.active) {
        border-color: #0637A1;
        color: #0637A1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 55, 161, 0.15);
    }

    .pagination-wrapper .pagination .page-item.active .page-link {
        background: #0637A1;
        border-color: #0637A1;
        color: white;
        box-shadow: 0 4px 15px rgba(6, 55, 161, 0.3);
    }

    .pagination-wrapper .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-wrapper .pagination .page-item .page-link .pagination-icon {
        font-size: 14px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .sidebar-wrapper {
            position: relative;
            max-height: none;
            overflow-y: visible;
        }

        .filter-card {
            padding: 12px 14px;
        }

        .results-section {
            flex-direction: column;
            align-items: stretch;
            padding: 14px;
        }

        .results-actions {
            flex-wrap: wrap;
        }

        .results-actions .dropdown-wrapper {
            flex: 1;
        }

        .results-actions .dropdown-trigger {
            width: 100%;
            min-width: unset;
        }

        .dropdown-menu {
            min-width: unset;
            width: 100%;
        }

        .pagination-wrapper .pagination .page-link {
            min-width: 36px;
            height: 36px;
            font-size: 13px;
            padding: 0 10px;
        }
    }

    @media (max-width: 640px) {
        .results-section {
            padding: 12px;
        }

        .results-info .results-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }

        .results-info .results-text .main-text {
            font-size: 13px;
        }

        .results-info .results-text .sub-text {
            font-size: 11px;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="/" class="hover:text-blue-600">Home</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Products</span>
    </nav>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- ===== SIDEBAR WITH SMOOTH SCROLL ===== -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="sidebar-wrapper">
                <!-- Categories -->
                <div class="filter-card">
                    <h3 onclick="toggleFilter(this)">
                        <span><i class="fas fa-th-large"></i> Categories</span>
                        <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                    </h3>
                    <div class="filter-content">
                        <ul class="filter-list ">
                            @foreach($categories as $category)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="checkbox"
                                           value="{{ $category->id }}"
                                           class="category-filter"
                                           {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                           onchange="applyFilters()">
                                    <span>{{ $category->name }}</span>
                                    <span class="count">{{ $category->products_count }}</span>
                                    <span class="check-mark"><i class="fas fa-check"></i></span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Sub Categories -->
                @if($subCategories->count())
                <div class="filter-card">
                    <h3 onclick="toggleFilter(this)">
                        <span><i class="fas fa-tags"></i> Sub Categories</span>
                        <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                    </h3>
                    <div class="filter-content">
                        <ul class="filter-list ">
                            @foreach($subCategories as $subCategory)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="checkbox"
                                           value="{{ $subCategory->id }}"
                                           class="subcategory-filter"
                                           {{ in_array($subCategory->id, $selectedSubCategories) ? 'checked' : '' }}
                                           onchange="applyFilters()">
                                    <span>{{ $subCategory->name }}</span>
                                    <span class="count">{{ $subCategory->products_count }}</span>
                                    <span class="check-mark"><i class="fas fa-check"></i></span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Brands -->
                @if($brands->count())
                <div class="filter-card">
                    <h3 onclick="toggleFilter(this)">
                        <span><i class="fas fa-building"></i> Brands</span>
                        <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                    </h3>
                    <div class="filter-content">
                        <ul class="filter-list ">
                            @foreach($brands as $brand)
                            <li>
                                <label class="filter-checkbox">
                                    <input type="checkbox"
                                           value="{{ $brand->id }}"
                                           class="brand-filter"
                                           {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }}
                                           onchange="applyFilters()">
                                    <span>{{ $brand->name }}</span>
                                    <span class="count">{{ $brand->products_count }}</span>
                                    <span class="check-mark"><i class="fas fa-check"></i></span>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Price Range -->
                <div class="filter-card">
                    <h3 onclick="toggleFilter(this)">
                        <span><i class="fas fa-dollar-sign"></i> Price Range</span>
                        <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                    </h3>
                    <div class="filter-content">
                        <div class="price-range-container">
                            <input type="range"
                                   id="priceRange"
                                   class="price-range-slider"
                                   min="{{ $minPrice ?? 0 }}"
                                   max="{{ $maxPrice ?? 1000 }}"
                                   value="{{ request('price_max', $maxPrice ?? 1000) }}"
                                   step="10"
                                   oninput="updatePriceRange(this.value)">

                            <div class="price-range-values">
                                <span class="min-price">{{ setting('currency_symbol', '৳') }} 0</span>
                                <span class="range-separator">—</span>
                                <span class="max-price" id="priceDisplay">{{ setting('currency_symbol', '৳') }} {{ number_format(request('price_max', $maxPrice ?? 1000), 0) }}</span>
                            </div>

                            <button onclick="applyPriceFilter()" class="btn-apply-filter">
                                <i class="fas fa-filter mr-2"></i>
                                Apply Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['categories', 'subcategories', 'brands', 'price_max']))
                <div class="filter-card">
                    <h3>
                        <span><i class="fas fa-sliders-h"></i> Active Filters</span>
                    </h3>
                    <div class="clear-filters">
                        @if(request('categories'))
                            @foreach(explode(',', request('categories')) as $catId)
                                @php $category = $categories->firstWhere('id', $catId); @endphp
                                @if($category)
                                <span class="filter-tag">
                                    {{ $category->name }}
                                    <span class="remove-filter" onclick="removeFilter('categories', '{{ $catId }}')">×</span>
                                </span>
                                @endif
                            @endforeach
                        @endif
                        @if(request('subcategories'))
                            @foreach(explode(',', request('subcategories')) as $subId)
                                @php $subCategory = $subCategories->firstWhere('id', $subId); @endphp
                                @if($subCategory)
                                <span class="filter-tag">
                                    {{ $subCategory->name }}
                                    <span class="remove-filter" onclick="removeFilter('subcategories', '{{ $subId }}')">×</span>
                                </span>
                                @endif
                            @endforeach
                        @endif
                        @if(request('brands'))
                            @foreach(explode(',', request('brands')) as $brandId)
                                @php $brand = $brands->firstWhere('id', $brandId); @endphp
                                @if($brand)
                                <span class="filter-tag">
                                    {{ $brand->name }}
                                    <span class="remove-filter" onclick="removeFilter('brands', '{{ $brandId }}')">×</span>
                                </span>
                                @endif
                            @endforeach
                        @endif
                        @if(request('price_max'))
                            <span class="filter-tag">
                                Under {{ setting('currency_symbol', '৳') }} {{ number_format(request('price_max'), 0) }}
                                <span class="remove-filter" onclick="clearPriceFilter()">×</span>
                            </span>
                        @endif
                        <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-700 text-xs font-medium">
                            <i class="fas fa-times-circle"></i> Clear All
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- ===== PRODUCTS GRID ===== -->
        <div class="flex-1 min-w-0">
            <!-- Results Section -->
            <div class="results-section">
                <div class="results-info">
                    <div class="results-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="results-text">
                        <div class="main-text">
                            Showing <strong>{{ $products->firstItem() ?? 0 }}</strong>
                            to <strong>{{ $products->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $products->total() }}</strong> results
                        </div>
                        <div class="sub-text">
                            @if(request('categories') || request('subcategories') || request('brands') || request('price_max'))
                                @if(request('categories'))
                                    in <span class="highlight">{{ count(explode(',', request('categories'))) }} categories</span>
                                @endif
                                @if(request('subcategories'))
                                    @if(request('categories')) & @endif
                                    <span class="highlight">{{ count(explode(',', request('subcategories'))) }} subcategories</span>
                                @endif
                                @if(request('brands'))
                                    @if(request('categories') || request('subcategories')) & @endif
                                    <span class="highlight">{{ count(explode(',', request('brands'))) }} brands</span>
                                @endif
                                @if(request('price_max'))
                                    @if(request('categories') || request('subcategories') || request('brands')) & @endif
                                    under <span class="highlight">{{ setting('currency_symbol', '৳')}} {{ number_format(request('price_max'), 0) }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">All products available</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="results-actions">
                    <!-- Sort Dropdown - HIDE RESULT COUNT TEXT -->
                    <div class="dropdown-wrapper">
                        <button class="dropdown-trigger" onclick="toggleDropdown(this)">
                            <span>
                                <i class="fas fa-sort-amount-down-alt" style="color: #0637A1;"></i>
                                <span class="selected-text">
                                    @switch(request('sort'))
                                        @case('price_low')
                                            Price: Low to High
                                            @break
                                        @case('price_high')
                                            Price: High to Low
                                            @break
                                        @case('popular')
                                            Most Popular
                                            @break
                                        @default
                                            Newest First
                                    @endswitch
                                </span>
                            </span>
                            <span class="dropdown-icon">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('products.index', array_merge(request()->except('sort', 'page'), ['sort' => 'newest'])) }}"
                               class="dropdown-item {{ !request('sort') || request('sort') == 'newest' ? 'active' : '' }}">
                                <span><i class="fas fa-clock"></i> Newest First</span>
                                <span class="check-icon"><i class="fas fa-check"></i></span>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort', 'page'), ['sort' => 'price_low'])) }}"
                               class="dropdown-item {{ request('sort') == 'price_low' ? 'active' : '' }}">
                                <span><i class="fas fa-arrow-up"></i> Price: Low to High</span>
                                <span class="check-icon"><i class="fas fa-check"></i></span>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort', 'page'), ['sort' => 'price_high'])) }}"
                               class="dropdown-item {{ request('sort') == 'price_high' ? 'active' : '' }}">
                                <span><i class="fas fa-arrow-down"></i> Price: High to Low</span>
                                <span class="check-icon"><i class="fas fa-check"></i></span>
                            </a>
                            <a href="{{ route('products.index', array_merge(request()->except('sort', 'page'), ['sort' => 'popular'])) }}"
                               class="dropdown-item {{ request('sort') == 'popular' ? 'active' : '' }}">
                                <span><i class="fas fa-fire"></i> Most Popular</span>
                                <span class="check-icon"><i class="fas fa-check"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($products as $product)
                @php
                    $availableStock = $product->getAvailableStock();
                    $isInStock = $availableStock > 0;
                    $isLowStock = $availableStock > 0 && $availableStock <= 5;
                @endphp
                <div class="product-card">
                    <div class="product-image">
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
                            @elseif($imagePath)
                                <img src="{{ asset('storage/' . $imagePath) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @endif
                        </a>
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <div class="p-3">
                        <div class="flex items-center justify-between">
                            <span class="category-tag">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                            @if($isInStock)
                                <span class="stock-badge {{ $isLowStock ? 'low-stock' : 'in-stock' }}">
                                    <i class="fas fa-circle"></i>
                                    {{ $isLowStock ? 'Only ' . $availableStock . ' left' : 'In Stock' }}
                                </span>
                            @else
                                <span class="stock-badge sold-out">
                                    <i class="fas fa-circle"></i>
                                    Sold Out
                                </span>
                            @endif
                        </div>

                        <h3 class="product-name">
                            <a href="{{ route('product.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="rating-count">({{ $product->views }})</span>
                        </div>

                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#E5E6E9]">
                            <div class="product-price">
                                <span class="price-current">{{ setting('currency_symbol', '৳') }} {{ number_format($product->price, 2) }}</span>
                                @if($product->discount_price)
                                    <span class="price-original">{{ setting('currency_symbol', '৳') }} {{ number_format($product->selling_price, 2) }}</span>
                                @endif
                            </div>
                            @if($isInStock)
                                <button onclick="addToCart({{ $product->id }})" class="add-cart-btn">
                                    <i class="fas fa-cart-plus"></i>
                                    <span class="hidden sm:inline">Add</span>
                                </button>
                            @else
                                <button class="add-cart-btn" disabled>
                                    <i class="fas fa-times-circle"></i>
                                    <span class="hidden sm:inline">Out</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-6xl text-gray-300 mb-4">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Products Found</h3>
                    <p class="text-gray-500">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 px-6 py-3 bg-[#0637A1] text-white rounded-lg hover:bg-[#03246E] transition">
                        <i class="fas fa-undo mr-2"></i> Reset Filters
                    </a>
                </div>
                @endforelse
            </div>

            <!-- ===== PAGINATION - CENTERED ===== -->
            @if($products->hasPages())
            <div class="pagination-wrapper">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ===== TOGGLE FILTER =====
    function toggleFilter(element) {
        const content = element.nextElementSibling;
        const icon = element.querySelector('.toggle-icon');

        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.classList.remove('rotated');
        } else {
            content.style.display = 'none';
            icon.classList.add('rotated');
        }
    }

    // ===== DROPDOWN =====
    function toggleDropdown(button) {
        const wrapper = button.closest('.dropdown-wrapper');
        const menu = wrapper.querySelector('.dropdown-menu');
        const isOpen = menu.classList.contains('show');

        document.querySelectorAll('.dropdown-menu.show').forEach(el => {
            if (el !== menu) {
                el.classList.remove('show');
                el.closest('.dropdown-wrapper').querySelector('.dropdown-trigger').classList.remove('open');
            }
        });

        if (isOpen) {
            menu.classList.remove('show');
            button.classList.remove('open');
        } else {
            menu.classList.add('show');
            button.classList.add('open');
        }
    }

    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-wrapper')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(el => {
                el.classList.remove('show');
                el.closest('.dropdown-wrapper').querySelector('.dropdown-trigger').classList.remove('open');
            });
        }
    });

    // ===== APPLY MULTIPLE FILTERS =====
    function applyFilters() {
        const categories = getSelectedValues('.category-filter');
        const subcategories = getSelectedValues('.subcategory-filter');
        const brands = getSelectedValues('.brand-filter');

        let url = new URL(window.location.href);

        url.searchParams.delete('categories');
        url.searchParams.delete('subcategories');
        url.searchParams.delete('brands');
        url.searchParams.delete('page');

        if (categories.length) {
            url.searchParams.set('categories', categories.join(','));
        }
        if (subcategories.length) {
            url.searchParams.set('subcategories', subcategories.join(','));
        }
        if (brands.length) {
            url.searchParams.set('brands', brands.join(','));
        }

        window.location.href = url.toString();
    }

    function getSelectedValues(selector) {
        const checkboxes = document.querySelectorAll(selector);
        const values = [];
        checkboxes.forEach(cb => {
            if (cb.checked) {
                values.push(cb.value);
            }
        });
        return values;
    }

    // ===== REMOVE FILTER =====
    function removeFilter(type, value) {
        const url = new URL(window.location.href);
        const current = url.searchParams.get(type);
        if (current) {
            const values = current.split(',');
            const filtered = values.filter(v => v !== value);
            if (filtered.length) {
                url.searchParams.set(type, filtered.join(','));
            } else {
                url.searchParams.delete(type);
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
    }

    // ===== CLEAR PRICE FILTER =====
    function clearPriceFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('price_max');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // ===== PRICE RANGE =====
    function updatePriceRange(value) {
        const currencySymbol = '{{ setting('currency_symbol', '৳')}}';
        document.getElementById('priceDisplay').textContent = currencySymbol + ' ' + parseInt(value).toLocaleString();
        const slider = document.getElementById('priceRange');
        const max = slider.max;
        const percentage = (value / max) * 100;
        slider.style.background = `linear-gradient(to right, #0637A1 0%, #0637A1 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
    }

    function applyPriceFilter() {
        const maxPrice = document.getElementById('priceRange').value;
        const url = new URL(window.location.href);

        if (maxPrice && maxPrice > 0) {
            url.searchParams.set('price_max', maxPrice);
        } else {
            url.searchParams.delete('price_max');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // Initialize slider
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('priceRange');
        if (slider) {
            updatePriceRange(slider.value);
        }

        document.querySelectorAll('.filter-content').forEach(el => {
            el.style.display = 'block';
        });

        // Custom pagination styling - center with nice design
        const pagination = document.querySelector('.pagination-wrapper .pagination');
        if (pagination) {
            pagination.classList.add('pagination');
        }
    });

    // ===== ADD TO CART =====
    function addToCart(productId) {
        const btn = event?.target?.closest('.add-cart-btn');
        const originalText = btn?.innerHTML;

        if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
        }

        fetch('{{ route('cart.add') }}', {
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
                document.querySelectorAll('.cart-count').forEach(el => {
                    el.textContent = data.cart_count;
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Added to Cart!',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to add product.',
                    confirmButtonColor: '#0637A1'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Please try again later.',
                confirmButtonColor: '#0637A1'
            });
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
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
</script>
@endpush
@endsection
