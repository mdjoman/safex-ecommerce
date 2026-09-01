@extends('frontend.layouts.master')

@section('title', 'Shopping Cart - SafeX Engineering')

@push('styles')
    <style>
        /* Payment options inline styling */
        .payment-options-inline {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .payment-options-inline .option-icon {
            display: inline-flex;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            background: #f8f9fa;
            width: 60px;
            height: 60px;
            align-items: center;
            justify-content: center;
        }

        .payment-options-inline .option-icon img {
            max-width: 40px;
            max-height: 40px;
            object-fit: contain;
        }

        .payment-options-inline .option-icon.active {
            border-color: #007bff;
            background: #e7f3ff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
        }

        .payment-process {
            display: none;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            margin: 10px 0;
        }

        .payment-process.active {
            display: block;
        }

        .transaction-input {
            margin-top: 15px;
        }

        .transaction-input label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }

        .transaction-input input[type="text"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .transaction-input input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        /* Disabled state for inputs */
        .form-control:disabled {
            background-color: #f7fafc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .payment-img {
            max-width: 100% !important;
            height: 60px !important;
        }

        /* ===== CART CONTAINER ===== */
        .cart-container {
            background: #f8fafc;
            border-radius: 16px;
        }

        /* ===== CART ITEMS ===== */
        .cart-item {
            background: white;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.2;
        }

        .item-sku {
            font-size: 10px;
            color: #9ca3af;
        }

        .price-current {
            font-size: 14px;
            font-weight: 700;
            color: #0637A1;
        }

        .price-original {
            font-size: 11px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .discount-badge-sm {
            font-size: 9px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 10px;
        }

        .discount-badge-sm.product-discount {
            background: #fee2e2;
            color: #dc2626;
        }

        .discount-badge-sm.coupon-discount {
            background: #dcfce7;
            color: #16a34a;
        }

        /* ===== COUPON APPLIED ROW ===== */
        .coupon-applied-row {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            padding: 2px 10px;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
        }

        .coupon-applied-row .code {
            font-weight: 700;
            color: #16a34a;
        }

        .coupon-applied-row .amount {
            color: #16a34a;
            font-weight: 600;
        }

        /* ===== QUANTITY SELECTOR ===== */
        .qty-selector {
            display: inline-flex;
            align-items: center;
            border: 1.5px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .qty-selector button {
            width: 28px;
            height: 28px;
            background: transparent;
            border: none;
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-selector button:hover {
            background: #f3f4f6;
            color: #0637A1;
        }

        .qty-selector input {
            width: 36px;
            height: 28px;
            border: none;
            border-left: 1.5px solid #e5e7eb;
            border-right: 1.5px solid #e5e7eb;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            outline: none;
        }

        .qty-selector input:focus {
            background: #f9fafb;
        }

        .remove-btn {
            color: #9ca3af;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .remove-btn:hover {
            color: #ef4444;
            transform: scale(1.1);
        }

        /* ===== SUMMARY CARD ===== */
        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 13px;
        }

        .summary-row .label {
            color: #6b7280;
        }

        .summary-row .value {
            font-weight: 600;
            color: #1f2937;
        }

        .summary-row .value.discount {
            color: #16a34a;
        }

        .summary-row .value.total {
            font-size: 18px;
            font-weight: 700;
            color: #0637A1;
        }

        .summary-divider {
            border-top: 1px dashed #e5e7eb;
            margin: 6px 0;
        }

        .summary-divider-solid {
            border-top: 2px solid #f3f4f6;
            margin: 6px 0;
        }

        /* ===== COUPON INPUT ===== */
        .coupon-input-group {
            display: flex;
            gap: 6px;
        }

        .coupon-input-group input {
            flex: 1;
            padding: 6px 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 6px;
            font-size: 12px;
            outline: none;
            transition: border-color 0.2s;
        }

        .coupon-input-group input:focus {
            border-color: #0637A1;
            box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.1);
        }

        .coupon-input-group button {
            padding: 6px 14px;
            background: #0637A1;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .coupon-input-group button:hover {
            background: #03246E;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6, 55, 161, 0.3);
        }

        /* ===== DELIVERY SELECT ===== */
        .delivery-select {
            padding: 2px 6px;
            border: 1.5px solid #e5e7eb;
            border-radius: 4px;
            font-size: 11px;
            outline: none;
            background: white;
            cursor: pointer;
        }

        .delivery-select:focus {
            border-color: #0637A1;
        }

        /* ===== CHECKOUT FORM ===== */
        .checkout-form {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            margin-top: 14px;
        }

        .checkout-form .form-title {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 14px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 3px;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0637A1;
            box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.1);
        }

        .form-group textarea {
            min-height: 60px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* ===== PAYMENT METHODS - IMPROVED ===== */
        .payment-methods-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 6px;
        }

        .payment-method-card {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .payment-method-card:hover {
            border-color: #0637A1;
            background: #f8fafc;
        }

        .payment-method-card.active {
            border-color: #0637A1;
            background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.1);
        }

        .payment-method-card .icon {
            width: 100px;
        }

        .payment-method-card .info .name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
        }

        .payment-method-card .info .sub-text {
            font-size: 10px;
            color: #9ca3af;
        }

        .payment-method-card .radio-circle {
            margin-left: auto;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .payment-method-card.active .radio-circle {
            border-color: #0637A1;
        }

        .payment-method-card.active .radio-circle::after {
            content: '';
            width: 10px;
            height: 10px;
            background: #0637A1;
            border-radius: 50%;
        }

        /* ===== ONLINE PAYMENT DETAILS ===== */
        .online-payment-details {
            display: none;
            margin-top: 12px;
            padding: 14px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .online-payment-details.show {
            display: block;
        }

        .online-payment-details .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .online-payment-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 10px;
        }

        .online-payment-option {
            padding: 8px 10px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .online-payment-option:hover {
            border-color: #0637A1;
            background: #f8fafc;
        }

        .online-payment-option.active {
            border-color: #0637A1;
            background: #eff6ff;
        }

        .online-payment-option .option-icon {
            font-size: 20px;
            display: block;
        }

        .online-payment-option .option-name {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
        }

        .online-payment-option .option-number {
            font-size: 9px;
            color: #6b7280;
            display: block;
        }

        .transaction-input {
            margin-top: 8px;
        }

        .transaction-input label {
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            display: block;
            margin-bottom: 2px;
        }

        .transaction-input input {
            width: 100%;
            padding: 6px 10px;
            border: 1.5px solid #e5e7eb;
            border-radius: 4px;
            font-size: 12px;
            outline: none;
        }

        .transaction-input input:focus {
            border-color: #0637A1;
            box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.1);
        }

        /* ===== BUTTONS ===== */
        .btn-confirm {
            display: block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-confirm:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
        }

        .btn-confirm:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-outline {
            padding: 8px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            color: #4b5563;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
        }

        .btn-outline:hover {
            border-color: #0637A1;
            color: #0637A1;
        }

        /* ===== EMPTY CART ===== */
        .empty-cart-icon {
            width: 100px;
            height: 100px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-cart-icon svg {
            width: 50px;
            height: 50px;
            color: #9ca3af;
        }

        /* ===== LOADING ===== */
        .spinner-sm {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .cart-container {
                padding: 12px;
            }

            .cart-item {
                padding: 10px 12px;
            }

            .item-image {
                width: 50px;
                height: 50px;
            }

            .summary-card {
                position: relative;
                top: 0;
                margin-top: 14px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .payment-methods-grid {
                grid-template-columns: 1fr;
            }

            .online-payment-options {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .cart-item .flex-wrap {
                gap: 6px;
            }

            .item-name {
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6  py-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    @if (isset($orderConfirmed) && $orderConfirmed)
                        Order Confirmed! 🎉
                    @else
                        Shopping Cart
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if (isset($orderConfirmed) && $orderConfirmed)
                        Your order has been placed successfully
                    @else
                        Review your items and complete your order
                    @endif
                </p>
            </div>
            <div class="mt-3 md:mt-0">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>

        @if (isset($orderConfirmed) && $orderConfirmed)
            <!-- ===== ORDER CONFIRMATION ===== -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Thank You for Your Order!</h2>
                <p class="text-gray-500 mt-2">Your order has been placed successfully.</p>
                <div class="text-xl font-bold text-blue-600 mt-2">Order #{{ $order->order_number ?? 'N/A' }}</div>
                <p class="text-sm text-gray-500">We'll send you a confirmation email with your order details.</p>
                <div class="mt-6 flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('order.track.form') }}" class="btn-outline">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Track Order
                    </a>
                    <a href="{{ route('products.index') }}" class="btn-outline">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Continue Shopping
                    </a>
                </div>
            </div>
        @elseif($cart && $cart->items->count())
            <div class="cart-container">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    <!-- ===== LEFT COLUMN: Cart Items + Checkout Form ===== -->
                    <div class="lg:col-span-3">
                        <!-- Cart Items -->
                        <div class="space-y-2">
                            @foreach ($cartItems as $item)
                                <div class="cart-item" id="item-{{ $item->id }}">
                                    <div class="flex flex-wrap items-start gap-2">
                                        <!-- Product Image & Info -->
                                        <div class="flex items-start gap-3 flex-1 min-w-[180px]">
                                            <img src="{{ asset('storage/' . $item->product->featured_image) }}"
                                                alt="{{ $item->product->name }}" class="item-image">
                                            <div class="min-w-0 flex-1">
                                                <h4 class="item-name">{{ $item->product->name }}</h4>
                                                <p class="item-sku">SKU: {{ $item->product->sku }}</p>
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @if ($item->has_discount)
                                                        <span class="discount-badge-sm product-discount">
                                                            {{ number_format((($item->product->selling_price - $item->product->discount_price) / $item->product->selling_price) * 100, 0) }}%
                                                            OFF <span
                                                                class="amount">{{ setting('currency', 'BDT') }}({{ $item->row_discount }})
                                                            </span>
                                                        </span>
                                                    @endif
                                                    @if ($item->has_coupon)
                                                        <span class="discount-badge-sm coupon-discount">
                                                            <span>✓ Coupon <span
                                                                    class="code">{{ $item->coupon_code }}</span>
                                                                applied</span>
                                                            <span class="amount">{{ setting('currency', 'BDT') }}
                                                                {{ number_format($item->row_coupon_discount, 2) }}
                                                            </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Price Column -->
                                        <div class="min-w-[70px]">
                                            <div class="text-center">
                                                <span class="price-current">{{ setting('currency', 'BDT') }}
                                                    {{ number_format($item->unit_price, 2) }}</span>

                                            </div>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="flex items-center gap-2">
                                            <div class="qty-selector">
                                                <button onclick="updateQuantity({{ $item->id }}, -1)">−</button>
                                                <input type="number" id="qty-{{ $item->id }}"
                                                    value="{{ $item->quantity }}" min="1"
                                                    max="{{ $item->product->stock_qty }}"
                                                    onchange="updateCart({{ $item->id }}, this.value)">
                                                <button onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                            </div>
                                            <button onclick="removeFromCart({{ $item->id }})"
                                                class="remove-btn inline-flex h-7 w-7 items-center justify-center rounded-full border border-red-300 bg-red-50 text-lg font-semibold leading-none text-red-600 hover:bg-red-100"
                                                title="Remove item"> x
                                            </button>
                                        </div>

                                        <!-- Row Total with Breakdown -->
                                        <div class="min-w-[110px] text-right">


                                            <!-- Row Total -->
                                            <div class="text-sm font-bold text-blue-600">
                                                {{ setting('currency', 'BDT') }}
                                                {{ number_format($item->row_subtotal, 2) }}
                                            </div>

                                            <!-- Calculation Breakdown -->
                                            @if ($item->row_coupon_discount + $item->row_discount > 0)
                                                <div class="text-xs text-gray-400" style="text-decoration: line-through;">
                                                    {{ setting('currency', 'BDT') }}
                                                    {{ number_format($item->row_coupon_discount + $item->row_discount, 2) }}
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- ===== CHECKOUT FORM ===== -->
                        <div class="checkout-form">
                            <h3 class="form-title">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Shipping Information
                            </h3>

                            <form id="checkoutForm" action="{{route('order.place')}}" method="POST">
                                @csrf
                                 <input type="hidden" name="cart_id" id="cart_id" value="{{$cart->id}}">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Full Name <span class="required">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name"
                                            placeholder="Enter your full name" required
                                            value="{{ auth()->check() ? auth()->user()->name : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address </label>
                                        <input type="email" name="customer_email" id="customer_email"
                                            placeholder="Enter your email"
                                            value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Phone Number <span class="required">*</span></label>
                                    <input type="tel" name="customer_phone" id="customer_phone"
                                        placeholder="Enter your phone number" required
                                        value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Shipping Address <span class="required">*</span></label>
                                    <textarea name="shipping_address" id="shipping_address" placeholder="Enter your full shipping address" required></textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label>City </label>
                                        <input type="text" name="city" id="city"
                                            placeholder="Enter your city">
                                    </div>
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input type="text" name="postal_code" id="postal_code"
                                            placeholder="Enter postal code">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Order Notes (Optional)</label>
                                    <textarea name="order_notes" id="order_notes" placeholder="Any special instructions for delivery"></textarea>
                                </div>

                                <!-- ===== IMPROVED PAYMENT METHODS ===== -->
                                <div class="form-group">
                                    <label>Payment Method <span class="required">*</span></label>

                                    <div class="payment-methods-grid">
                                        <!-- Cash on Delivery -->
                                        <div class="payment-method-card active" onclick="selectPaymentType(this, 'cash')">
                                            <div class="icon"><img class="payment-img"
                                                    src="{{ asset('frontend/img/cash-on-delivery.png') }}"
                                                    alt="Cash on Delivery"></div>
                                            <div class="info">
                                                <div class="name">Cash on Delivery</div>
                                                <div class="sub-text">Pay when you receive</div>
                                            </div>
                                            <div class="radio-circle"></div>
                                        </div>

                                        <!-- Online Payment -->
                                        <div class="payment-method-card" onclick="selectPaymentType(this, 'online')">
                                            <div class="icon"><img class="payment-img"
                                                    src="{{ asset('frontend/img/online-payment.png') }}"
                                                    alt="Online Payment">
                                            </div>
                                            <div class="info">
                                                <div class="name">Online Payment</div>
                                                <div class="sub-text">bKash, Nagad, Rocket</div>
                                            </div>
                                            <div class="radio-circle"></div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="payment_type" id="payment_type" value="cash">
                                    <input type="hidden" name="payment_method" id="payment_method" value="cash">

                                    <!-- Online Payment Details -->
                                    <div class="online-payment-details" id="onlinePaymentDetails">
                                        <div class="section-title">Select Your Payment Method</div>

                                        <div class="online-payment-options">
                                            <div class="payment-options-inline">
                                                <!-- bKash -->
                                                <span class="option-icon  active" id="bkash_payment"
                                                    onclick="selectOnlinePayment(this, 'bkash')">
                                                    <img class="payment-img"
                                                        src="{{ asset('frontend/img/bkash-logo.png') }}" alt="bKash">
                                                </span>
                                                <!-- Rocket -->
                                                <span class="option-icon" onclick="selectOnlinePayment(this, 'rocket')">
                                                    <img class="payment-img"
                                                        src="{{ asset('frontend/img/rocket-logo.png') }}" alt="Rocket">
                                                </span>
                                                <!-- Nagad -->
                                                <span class="option-icon" onclick="selectOnlinePayment(this, 'nagad')">
                                                    <img class="payment-img"
                                                        src="{{ asset('frontend/img/nagad-logo.png') }}" alt="Nagad">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="">
                                            <!-- Payment Process Descriptions -->
                                            <div id="bkash_payment_process" class="payment-process active"
                                                style="margin-top: 0px; padding-top: 0px">
                                                {!! setting('bkash_payment_process', 'Bkash Payment Process Details') !!}
                                            </div>
                                            <div id="nagad_payment_process" class="payment-process"
                                                style="margin-top: 0px; padding-top: 0px">
                                                {!! setting('nagad_payment_process', 'Nagad Payment Process Details') !!}
                                            </div>
                                            <div id="rocket_payment_process" class="payment-process"
                                                style="margin-top: 0px; padding-top: 0px">
                                                {!! setting('rocket_payment_process', 'Rocket Payment Process Details') !!}
                                            </div>

                                        </div>

                                        <div class="transaction-input">

                                            <label>Transaction ID / Reference Number <span
                                                    class="required">*</span></label>
                                            <input type="text" name="transaction_number" id="transaction_number"
                                                placeholder="Enter transaction Number">

                                            <label>Transaction ID / Reference Number <span
                                                    class="required">*</span></label>
                                            <input type="text" name="transaction_id" id="transaction_id"
                                                placeholder="Enter transaction ID from your payment app">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn-confirm" id="confirmOrderBtn">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Confirm Order
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- ===== RIGHT COLUMN: Order Summary ===== -->
                    <div class="lg:col-span-2">
                        <div class="summary-card">
                            <h3 class="summary-title">Order Summary</h3>

                            <!-- Subtotal -->
                            <div class="summary-row">
                                <span class="label">Subtotal</span>
                                <span class="value">{{ setting('currency', 'BDT') }}
                                    {{ number_format($subtotal, 2) }}</span>
                            </div>

                            <!-- Product Discount -->
                            @if ($totalDiscount > 0)
                                <div class="summary-row">
                                    <span class="label">Product Discount</span>
                                    <span class="value discount"> {{ setting('currency', 'BDT') }}
                                        {{ number_format($totalDiscount, 2) }}</span>
                                </div>
                            @endif

                            <!-- Coupon Discount -->
                            @if ($totalCouponDiscount > 0)
                                <div class="summary-row">
                                    <span class="label">Coupon Discount</span>
                                    <span class="value discount"> {{ setting('currency', 'BDT') }}
                                        {{ number_format($totalCouponDiscount, 2) }}</span>
                                </div>
                            @endif

                            <div class="summary-divider"></div>

                            <!-- Total After Discount -->
                            <div class="summary-row"
                                style="background: #eff6ff; padding: 4px 10px; border-radius: 6px; margin: 2px 0;">
                                <span class="label" style="font-weight: 600;">Total After Discount</span>
                                <span class="value"
                                    style="color: #0637A1; font-size: 15px;">{{ setting('currency', 'BDT') }}
                                    {{ number_format($totalAfterDiscount, 2) }}</span>
                            </div>

                            <div class="summary-divider-solid"></div>

                            <!-- Coupon Code -->
                            <div class="mt-2">
                                @if (session('applied_coupon'))
                                    <div class="coupon-success-box">
                                        <div class="flex items-center justify-between gap-3">

                                            <!-- Coupon Badge -->
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full border border-green-200 bg-green-50 px-3 py-1 text-sm font-medium text-green-700">
                                                ✓ Coupon: {{ session('applied_coupon')['code'] }} |
                                                {{ setting('currency', 'BDT') }}
                                                {{ number_format(session('applied_coupon')['discount'], 2) }}
                                            </span>
                                            <!-- Remove Button -->
                                            <button type="button" onclick="removeCoupon()"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-red-300 bg-red-50 text-lg font-semibold leading-none text-red-600 hover:bg-red-100"
                                                title="Remove Coupon">
                                                ×
                                            </button>

                                        </div>
                                    </div>
                                @else
                                    <div class="coupon-input-group">
                                        <input type="text" id="couponCode" placeholder="Enter coupon code">
                                        <button onclick="applyCoupon()">Apply</button>
                                    </div>
                                @endif
                                <div id="couponMessage" class="mt-1 text-xs hidden"></div>
                            </div>

                            <div class="summary-divider-solid"></div>

                            <!-- Delivery Charge -->
                            <div class="summary-row">
                                <span class="label">
                                    <div class="flex items-center gap-4 mt-1">
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" name="deliveryType" value="inside"
                                                {{ $delivery_type == 'inside' ? 'checked' : '' }}
                                                onchange="updateDeliveryCharge()" class="w-4 h-4">
                                            <span>Inside City</span>
                                        </label>

                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                            <input type="radio" name="deliveryType" value="outside"
                                                {{ $delivery_type == 'outside' ? 'checked' : '' }}
                                                onchange="updateDeliveryCharge()" class="w-4 h-4">
                                            <span>Outside City</span>
                                        </label>
                                    </div>
                                </span>
                                <span id="deliveryChargeDisplay" class="value">{{ setting('currency', 'BDT') }}
                                    {{ number_format($delivery_charge, 2) }}</span>
                            </div>

                            <!-- VAT -->
                            <div class="summary-row">
                                <span class="label">VAT ({{ $vatRate }}%)</span>
                                <span id="vatAmount" class="value">{{ setting('currency', 'BDT') }}
                                    {{ number_format($vatAmount, 2) }}</span>
                            </div>

                            <div class="summary-divider-solid"></div>

                            <!-- Grand Total -->
                            <div class="summary-row" style="padding: 4px 0;">
                                <span class="label" style="font-size: 16px; font-weight: 700;">Grand Total</span>
                                <span id="grandTotal" class="value total">{{ setting('currency', 'BDT') }}
                                    {{ number_format($grandTotal, 2) }}</span>
                            </div>

                            <!-- Cart Actions -->
                            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                                <a href="{{ route('cart.clear') }}"
                                    class="text-red-600 hover:text-red-700 text-xs font-medium flex items-center gap-1"
                                    onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear Cart
                                </a>
                                <span class="text-xs text-gray-500">
                                    <span class="font-medium">{{ $cart->items->count() }}</span> items
                                </span>
                            </div>

                            <!-- Payment Methods Display -->
                            <div class="flex justify-center items-center gap-3 mt-3 pt-3 border-t border-gray-200">
                                <span class="text-xs text-gray-500">Secure Checkout</span>
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- ===== EMPTY CART ===== -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="empty-cart-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>


            // PAYMENT METHOD SELECTION
            function selectPaymentType(element, type) {
                // Validate element exists
                if (!element) return;

                // Update payment method cards
                const cards = document.querySelectorAll('.payment-method-card');
                cards.forEach(el => {
                    el.classList.remove('active');
                });
                element.classList.add('active');


                // Get DOM elements
                const onlineDetails = document.getElementById('onlinePaymentDetails');
                const paymentMethod = document.getElementById('payment_method');
                const paymentForm = document.getElementById('payment_type');
                const transactionNumber = document.getElementById('transaction_number');
                const transactionId = document.getElementById('transaction_id');
                const bkashPayment = document.getElementById('bkash_payment');

                if (type === 'online') {
                    // Show online payment details
                    onlineDetails.classList.add('show');
                    onlineDetails.style.display = 'block';

                    // Make transaction fields required
                    if (transactionNumber) {
                        transactionNumber.setAttribute('required', 'required');
                        transactionNumber.disabled = false;
                    }
                    if (transactionId) {
                        transactionId.setAttribute('required', 'required');
                        transactionId.disabled = false;
                    }

                    // Set default online payment method (bKash)
                    if (paymentMethod) {
                        paymentMethod.value = 'bkash';
                    }
                    if (paymentForm) {
                        paymentForm.value = 'online';
                    }
                    bkashPayment.classList.add('active');
                    const targetElement = document.getElementById('bkash_payment_process');
                    if (targetElement) {
                        targetElement.classList.add('active');
                        // Trigger animation
                        targetElement.style.animation = 'none';
                        setTimeout(() => {
                            targetElement.style.animation = 'fadeIn 0.3s ease-out';
                        }, 10);
                    }

                    // Scroll to online payment section smoothly
                    setTimeout(() => {
                        onlineDetails.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);

                } else {
                    // Hide online payment details
                    onlineDetails.classList.remove('show');
                    onlineDetails.style.display = 'none';

                    // Remove required attribute from transaction fields
                    if (transactionNumber) {
                        transactionNumber.removeAttribute('required');
                        transactionNumber.disabled = true;
                        transactionNumber.value = ''; // Clear value
                    }
                    if (transactionId) {
                        transactionId.removeAttribute('required');
                        transactionId.disabled = true;
                        transactionId.value = ''; // Clear value
                    }

                    // Reset payment method values
                    if (paymentMethod) {
                        paymentMethod.value = 'cash';
                    }
                    if (paymentForm) {
                        paymentForm.value = 'cash';
                    }

                    // Reset active states on online payment options
                    document.querySelectorAll('.option-icon').forEach(el => {
                        el.classList.remove('active');
                    });
                    const allDetails = document.querySelectorAll('.payment-process');
                    allDetails.forEach(detail => {
                        detail.classList.remove('active');
                    });

                }
            }

            //  online payment selection function
            function selectOnlinePayment(element, paymentType) {
                if (!element) return;

                // Remove active class from all options
                const allOptions = document.querySelectorAll('.option-icon');
                allOptions.forEach(option => {
                    option.classList.remove('active');
                });

                // Add active class to selected
                element.classList.add('active');

                // Update hidden inputs
                const paymentForm = document.getElementById('payment_type');
                const paymentMethod = document.getElementById('payment_method');

                if (paymentForm) {
                    paymentForm.value = 'online';
                }
                if (paymentMethod) {
                    paymentMethod.value = paymentType;
                }

                // Show selected payment process
                const allDetails = document.querySelectorAll('.payment-process');
                allDetails.forEach(detail => {
                    detail.classList.remove('active');
                });

                const targetId = paymentType + '_payment_process';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.classList.add('active');
                    // Trigger animation
                    targetElement.style.animation = 'none';
                    setTimeout(() => {
                        targetElement.style.animation = 'fadeIn 0.3s ease-out';
                    }, 10);
                }
            }

            // UPDATE CART - RELOAD ON SUCCESS
            function updateCart(itemId, quantity) {
                if (quantity < 1) return;

                const btn = document.querySelector(`#item-${itemId} .qty-selector`);
                if (btn) {
                    btn.style.opacity = '0.6';
                    btn.style.pointerEvents = 'none';
                }

                fetch('{{ route('cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            item_id: itemId,
                            quantity: parseInt(quantity)
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to update cart',
                                confirmButtonColor: '#2563eb'
                            });
                            if (btn) {
                                btn.style.opacity = '1';
                                btn.style.pointerEvents = 'auto';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error',
                            text: 'Please check your connection and try again.',
                            confirmButtonColor: '#2563eb'
                        });
                        if (btn) {
                            btn.style.opacity = '1';
                            btn.style.pointerEvents = 'auto';
                        }
                    });
            }

            // UPDATE QUANTITY
            function updateQuantity(itemId, change) {
                const input = document.getElementById('qty-' + itemId);
                if (!input) return;

                let newQty = parseInt(input.value) + change;
                const min = parseInt(input.min) || 1;
                const max = parseInt(input.max) || 999;

                if (newQty < min) newQty = min;
                if (newQty > max) newQty = max;

                input.value = newQty;
                updateCart(itemId, newQty);
            }

            // REMOVE FROM CART
            function removeFromCart(itemId) {
                Swal.fire({
                    title: 'Remove Item?',
                    text: 'Are you sure you want to remove this item from your cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        const btn = document.querySelector(`#item-${itemId} .remove-btn`);
                        if (btn) {
                            btn.innerHTML = '<span class="spinner-sm"></span>';
                            btn.disabled = true;
                        }

                        fetch('{{ route('cart.remove') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    item_id: itemId
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Removed!',
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Failed to remove item',
                                        confirmButtonColor: '#2563eb'
                                    });
                                    if (btn) {
                                        btn.innerHTML =
                                            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                                        btn.disabled = false;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Please check your connection and try again.',
                                    confirmButtonColor: '#2563eb'
                                });
                                if (btn) {
                                    btn.innerHTML =
                                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                                    btn.disabled = false;
                                }
                            });
                    }
                });
            }

            // APPLY COUPON
            function applyCoupon() {
                const couponCode = document.getElementById('couponCode');
                const messageDiv = document.getElementById('couponMessage');

                if (!couponCode) return;

                const code = couponCode.value.trim();
                messageDiv.classList.add('hidden');

                if (!code) {
                    messageDiv.className = 'mt-1 text-xs text-red-600';
                    messageDiv.textContent = 'Please enter a coupon code';
                    messageDiv.classList.remove('hidden');
                    couponCode.focus();
                    return;
                }

                const applyBtn = document.querySelector('.coupon-input-group button');
                const originalText = applyBtn.textContent;
                applyBtn.textContent = 'Applying...';
                applyBtn.disabled = true;

                fetch('{{ route('cart.apply-coupon') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            coupon_code: code
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        applyBtn.textContent = originalText;
                        applyBtn.disabled = false;

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Coupon Applied!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            messageDiv.className = 'mt-1 text-xs text-red-600';
                            messageDiv.textContent = data.message;
                            messageDiv.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        applyBtn.textContent = originalText;
                        applyBtn.disabled = false;
                        messageDiv.className = 'mt-1 text-xs text-red-600';
                        messageDiv.textContent = 'Error applying coupon. Please try again.';
                        messageDiv.classList.remove('hidden');
                    });
            }

            // REMOVE COUPON
            function removeCoupon() {
                Swal.fire({
                    title: 'Remove Coupon?',
                    text: 'Are you sure you want to remove this coupon?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const removeBtn = document.querySelector('.coupon-success-box .remove-coupon');
                        if (removeBtn) {
                            removeBtn.innerHTML =
                                '<span class="spinner-sm" style="border-color:rgba(220,38,38,0.3); border-top-color:#dc2626;"></span>';
                            removeBtn.disabled = true;
                        }

                        fetch('{{ route('cart.remove-coupon') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Removed!',
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message || 'Failed to remove coupon',
                                        confirmButtonColor: '#2563eb'
                                    });
                                    if (removeBtn) {
                                        removeBtn.innerHTML = '×';
                                        removeBtn.disabled = false;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Please check your connection and try again.',
                                    confirmButtonColor: '#2563eb'
                                });
                                if (removeBtn) {
                                    removeBtn.innerHTML = '×';
                                    removeBtn.disabled = false;
                                }
                            });
                    }
                });
            }

            // UPDATE DELIVERY CHARGE
            function updateDeliveryCharge() {
                const deliveryType = document.querySelector(
                    'input[name="deliveryType"]:checked'
                );

                if (!deliveryType) return;

                // Show loading state
                const select = deliveryType;
                select.style.opacity = '0.6';
                select.style.pointerEvents = 'none';

                fetch('{{ route('cart.update-delivery') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            delivery_type: deliveryType.value
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Reload page to update all calculations
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to update delivery charge',
                                confirmButtonColor: '#2563eb'
                            });
                            select.style.opacity = '1';
                            select.style.pointerEvents = 'auto';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error',
                            text: 'Please check your connection and try again.',
                            confirmButtonColor: '#2563eb'
                        });
                        select.style.opacity = '1';
                        select.style.pointerEvents = 'auto';
                    });
            }
            // ORDER CONFIRMATION
            document.addEventListener('DOMContentLoaded', function() {
                // Coupon enter key
                const couponInput = document.getElementById('couponCode');
                if (couponInput) {
                    couponInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            applyCoupon();
                        }
                    });
                }

            // Checkout form submission
                const checkoutForm = document.getElementById('checkoutForm');
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const btn = document.getElementById('confirmOrderBtn');
                        const originalText = btn.innerHTML;

                        // Validate form
                        const name = document.getElementById('customer_name')?.value.trim();
                        const cart_id = document.getElementById('cart_id')?.value.trim();
                        const phone = document.getElementById('customer_phone')?.value.trim();
                        const address = document.getElementById('shipping_address')?.value.trim();
                        const city = document.getElementById('city')?.value.trim();

                        if (!name || !cart_id || !phone || !address ) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Missing Information',
                                text: 'Please fill in all required fields.',
                                confirmButtonColor: '#2563eb'
                            });
                            return;
                        }

                        // Validate online payment
                        const paymentType = document.getElementById('payment_type').value;
                        if (paymentType === 'online') {
                            const transactionId = document.getElementById('transaction_id')?.value.trim();
                            const transaction_number = document.getElementById('transaction_number')?.value.trim();
                            if (!transactionId && !transaction_number) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Transaction Number & ID Required',
                                    text: 'Please enter transaction number  your transaction ID for online payment.',
                                    confirmButtonColor: '#2563eb'
                                });
                                document.getElementById('transaction_id')?.focus();
                                return;
                            }
                        }

                        // Show loading
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-sm"></span> Processing...';
                        const formData = new FormData(this);
                        fetch('{{ route('order.place') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data)
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Order Placed!',
                                        text: 'Your order has been placed successfully.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        if (data.redirect_url) {
                                            window.location.href = data.redirect_url;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Order Failed',
                                        text: data.message ||
                                            'Failed to place order. Please try again.',
                                        confirmButtonColor: '#2563eb'
                                    });
                                    btn.disabled = false;
                                    btn.innerHTML = originalText;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Please check your connection and try again.',
                                    confirmButtonColor: '#2563eb'
                                });
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            });
                    });
                }
            });

            // TOAST NOTIFICATION HELPER
            function showToast(message, type = 'success') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: type,
                    title: message
                });
            }
        </script>
    @endpush
@endsection
