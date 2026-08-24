@extends('frontend.layouts.master')

@section('title', 'Shopping Cart - SafeX Engineering')

@push('styles')
<style>
    /* ===== CART CONTAINER ===== */
    .cart-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
    }

    /* ===== CART ITEMS ===== */
    .cart-item {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .item-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-name {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .item-sku {
        font-size: 11px;
        color: #9ca3af;
    }

    .price-current {
        font-size: 15px;
        font-weight: 700;
        color: #0637A1;
    }

    .price-original {
        font-size: 12px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .discount-badge-sm {
        font-size: 10px;
        font-weight: 600;
        padding: 1px 8px;
        border-radius: 12px;
    }

    .discount-badge-sm.product-discount {
        background: #fee2e2;
        color: #dc2626;
    }

    .discount-badge-sm.coupon-discount {
        background: #dcfce7;
        color: #16a34a;
    }

    /* ===== QUANTITY SELECTOR ===== */
    .qty-selector {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-selector button {
        width: 30px;
        height: 30px;
        background: transparent;
        border: none;
        font-size: 16px;
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
        width: 40px;
        height: 30px;
        border: none;
        border-left: 1.5px solid #e5e7eb;
        border-right: 1.5px solid #e5e7eb;
        text-align: center;
        font-size: 14px;
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
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        position: sticky;
        top: 20px;
    }

    .summary-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 14px;
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
        font-size: 20px;
        font-weight: 700;
        color: #0637A1;
    }

    .summary-divider {
        border-top: 1px dashed #e5e7eb;
        margin: 8px 0;
    }

    .summary-divider-solid {
        border-top: 2px solid #f3f4f6;
        margin: 8px 0;
    }

    /* ===== COUPON INPUT ===== */
    .coupon-input-group {
        display: flex;
        gap: 8px;
    }

    .coupon-input-group input {
        flex: 1;
        padding: 8px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s;
    }

    .coupon-input-group input:focus {
        border-color: #0637A1;
        box-shadow: 0 0 0 3px rgba(6, 55, 161, 0.1);
    }

    .coupon-input-group button {
        padding: 8px 16px;
        background: #0637A1;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
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
        padding: 4px 8px;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        font-size: 12px;
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
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        margin-top: 16px;
    }

    .checkout-form .form-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }

    .form-group label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
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
        min-height: 80px;
        resize: vertical;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* ===== PAYMENT METHODS ===== */
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin: 10px 0;
    }

    .payment-method {
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .payment-method:hover {
        border-color: #0637A1;
        background: #f8fafc;
    }

    .payment-method.active {
        border-color: #0637A1;
        background: #eff6ff;
    }

    .payment-method .icon {
        font-size: 24px;
        display: block;
        margin-bottom: 4px;
    }

    .payment-method .name {
        font-size: 12px;
        font-weight: 600;
        color: #1f2937;
    }

    /* ===== ORDER CONFIRMATION ===== */
    .order-confirmation {
        background: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .order-confirmation .success-icon {
        width: 80px;
        height: 80px;
        background: #dcfce7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .order-confirmation .success-icon svg {
        width: 40px;
        height: 40px;
        color: #16a34a;
    }

    .order-confirmation .order-number {
        font-size: 20px;
        font-weight: 700;
        color: #0637A1;
        margin: 8px 0;
    }

    /* ===== BUTTONS ===== */
    .btn-checkout {
        display: block;
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0637A1, #0658DC);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .btn-checkout:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 55, 161, 0.3);
    }

    .btn-checkout:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-confirm {
        display: block;
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
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
        padding: 10px 24px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #4b5563;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-outline:hover {
        border-color: #0637A1;
        color: #0637A1;
    }

    /* ===== EMPTY CART ===== */
    .empty-cart-icon {
        width: 120px;
        height: 120px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .empty-cart-icon svg {
        width: 60px;
        height: 60px;
        color: #9ca3af;
    }

    /* ===== LOADING ===== */
    .spinner-sm {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .cart-container {
            padding: 12px;
        }

        .cart-item {
            padding: 12px;
        }

        .item-image {
            width: 60px;
            height: 60px;
        }

        .summary-card {
            position: relative;
            top: 0;
            margin-top: 16px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .payment-methods {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .payment-methods {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                @if(isset($orderConfirmed) && $orderConfirmed)
                    Order Confirmed! 🎉
                @else
                    Shopping Cart
                @endif
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                @if(isset($orderConfirmed) && $orderConfirmed)
                    Your order has been placed successfully
                @else
                    Review your items and complete your order
                @endif
            </p>
        </div>
        <div class="mt-3 md:mt-0">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Continue Shopping
            </a>
        </div>
    </div>

    @if(isset($orderConfirmed) && $orderConfirmed)
        <!-- ===== ORDER CONFIRMATION ===== -->
        <div class="order-confirmation">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Thank You for Your Order!</h2>
            <p class="text-gray-500 mt-2">Your order has been placed successfully.</p>
            <div class="order-number">Order #{{ $order->order_number ?? 'N/A' }}</div>
            <p class="text-sm text-gray-500">We'll send you a confirmation email with your order details.</p>
            <div class="mt-6 flex flex-wrap gap-4 justify-center">
                <a href="{{ route('order.track.form') }}" class="btn-outline">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Track Order
                </a>
                <a href="{{ route('products.index') }}" class="btn-outline">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
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
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="cart-item" id="item-{{ $item->id }}">
                            <div class="flex flex-wrap items-center gap-3">
                                <!-- Product Image & Info -->
                                <div class="flex items-center gap-3 flex-1 min-w-[180px]">
                                    <img src="{{ asset('storage/' . $item->product->featured_image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="item-image">
                                    <div>
                                        <h4 class="item-name">{{ $item->product->name }}</h4>
                                        <p class="item-sku">SKU: {{ $item->product->sku }}</p>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @if($item->has_discount)
                                                <span class="discount-badge-sm product-discount">
                                                    {{ number_format((($item->product->selling_price - $item->product->discount_price) / $item->product->selling_price) * 100, 0) }}% OFF
                                                </span>
                                            @endif
                                            @if($item->has_coupon)
                                                <span class="discount-badge-sm coupon-discount">
                                                    Coupon: {{ $item->coupon_code }}
                                                </span>

                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="min-w-[70px] text-center">
                                    <span class="price-current">{{ setting('currency', 'BDT') }} {{ number_format($item->unit_price, 2) }}</span>
                                    @if($item->has_discount)
                                        <span class="price-original block">{{ setting('currency', 'BDT') }} {{ number_format($item->original_price, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Quantity -->
                                <div class="flex items-center gap-2">
                                    <div class="qty-selector">
                                        <button onclick="updateQuantity({{ $item->id }}, -1)">−</button>
                                        <input type="number" id="qty-{{ $item->id }}"
                                               value="{{ $item->quantity }}"
                                               min="1"
                                               max="{{ $item->product->stock_qty }}"
                                               onchange="updateCart({{ $item->id }}, this.value)">
                                        <button onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                    </div>
                                    <button onclick="removeFromCart({{ $item->id }})" class="remove-btn" title="Remove item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Row Total -->
                                <div class=" text-xs text-green-600 block">
                                    <span class="text-sm font-bold text-blue-600">{{ setting('currency', 'BDT') }} {{ number_format($item->row_total, 2) }}</span>
                                    @php $totalRowDiscount = $item->row_discount  @endphp
                                    @if($totalRowDiscount > 0)
                                        <span class="price-original block">{{ setting('currency', 'BDT') }} {{ number_format($totalRowDiscount, 2) }}</span>
                                    @endif
                                    @if( $item->row_coupon_discount; > 0)
                                        <span class="price-original block">{{ setting('currency', 'BDT') }} {{ number_format( $item->row_coupon_discount, 2) }}</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Shipping Information
                        </h3>

                        <form id="checkoutForm" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name <span class="required">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name"
                                           placeholder="Enter your full name" required
                                           value="{{ auth()->check() ? auth()->user()->name : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Email Address <span class="required">*</span></label>
                                    <input type="email" name="customer_email" id="customer_email"
                                           placeholder="Enter your email" required
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
                                <textarea name="shipping_address" id="shipping_address"
                                          placeholder="Enter your full shipping address" required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>City <span class="required">*</span></label>
                                    <input type="text" name="city" id="city" placeholder="Enter your city" required>
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" name="postal_code" id="postal_code" placeholder="Enter postal code">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Order Notes (Optional)</label>
                                <textarea name="order_notes" id="order_notes"
                                          placeholder="Any special instructions for delivery"></textarea>
                            </div>

                            <!-- Payment Methods -->
                            <div class="form-group">
                                <label>Payment Method <span class="required">*</span></label>


                                <input type="hidden" name="payment_method" id="payment_method" value="bkash">
                            </div>

                            <button type="submit" class="btn-confirm" id="confirmOrderBtn">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                            <span class="value">{{ setting('currency', 'BDT') }} {{ number_format($subtotal, 2) }}</span>
                        </div>

                        <!-- Product Discount -->
                        @if($totalDiscount > 0)
                        <div class="summary-row">
                            <span class="label">Product Discount</span>
                            <span class="value discount">- {{ setting('currency', 'BDT') }} {{ number_format($totalDiscount, 2) }}</span>
                        </div>
                        @endif

                        <!-- Coupon Discount -->
                        @if($totalCouponDiscount > 0)
                        <div class="summary-row">
                            <span class="label">Coupon Discount</span>
                            <span class="value discount">- {{ setting('currency', 'BDT') }} {{ number_format($totalCouponDiscount, 2) }}</span>
                        </div>
                        @if($appliedCoupons)
                            <div class="text-xs text-gray-500 pl-2">
                                @foreach($appliedCoupons as $coupon)
                                    <div>✓ {{ $coupon['code'] }} (Save {{ setting('currency', 'BDT') }} {{ number_format($coupon['amount'], 2) }})</div>
                                @endforeach
                            </div>
                        @endif
                        @endif

                        <div class="summary-divider"></div>

                        <!-- Total After Discount -->
                        <div class="summary-row" style="background: #eff6ff; padding: 8px 12px; border-radius: 8px; margin: 4px 0;">
                            <span class="label" style="font-weight: 600;">Total After Discount</span>
                            <span class="value" style="color: #0637A1; font-size: 16px;">{{ setting('currency', 'BDT') }} {{ number_format($totalAfterDiscount, 2) }}</span>
                        </div>

                        <div class="summary-divider-solid"></div>

                        <!-- Coupon Code -->
                        <div class="mt-3">
                            <div class="coupon-input-group">
                                <input type="text" id="couponCode" placeholder="Enter coupon code">
                                <button onclick="applyCoupon()">Apply</button>
                            </div>
                            <div id="couponMessage" class="mt-1 text-xs hidden"></div>
                            <div id="couponSuccessMessage" class="mt-1 text-xs hidden"></div>
                        </div>

                        <div class="summary-divider-solid"></div>

                        <!-- Delivery Charge -->
                        <div class="summary-row">
                            <span class="label">
                                Delivery Charge
                                <select id="deliveryType" onchange="updateDeliveryCharge()" class="delivery-select">
                                    <option value="inside">Inside City</option>
                                    <option value="outside">Outside City</option>
                                </select>
                            </span>
                            <span id="deliveryChargeDisplay" class="value">{{ setting('currency', 'BDT') }} {{ number_format($deliveryChargeInside, 2) }}</span>
                        </div>

                        <!-- VAT -->
                        <div class="summary-row">
                            <span class="label">VAT ({{ $vatRate }}%)</span>
                            <span id="vatAmount" class="value">{{ setting('currency', 'BDT') }} {{ number_format($vatAmount, 2) }}</span>
                        </div>

                        <div class="summary-divider-solid"></div>

                        <!-- Grand Total -->
                        <div class="summary-row" style="padding: 8px 0;">
                            <span class="label" style="font-size: 18px; font-weight: 700;">Grand Total</span>
                            <span id="grandTotal" class="value total">{{ setting('currency', 'BDT') }} {{ number_format($grandTotal, 2) }}</span>
                        </div>

                        <!-- Cart Actions -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                            <a href="{{ route('cart.clear') }}"
                               class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1"
                               onclick="return confirm('Are you sure you want to clear your cart?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Clear Cart
                            </a>
                            <span class="text-sm text-gray-500">
                                <span class="font-medium">{{ $cart->items->count() }}</span> items
                            </span>
                        </div>

                        <!-- Payment Methods Display -->
                        <div class="flex justify-center items-center gap-4 mt-4 pt-4 border-t border-gray-200">
                            <span class="text-xs text-gray-500">Secure Checkout</span>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Start Shopping
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // VARIABLES
    // ============================================
    let currentDeliveryCharge = {{ $deliveryChargeInside ?? 0 }};
    let currentVatRate = {{ $vatRate ?? 0 }};
    let currentSubtotal = {{ $subtotal ?? 0 }};
    let currentProductDiscount = {{ $totalDiscount ?? 0 }};
    let currentCouponDiscount = {{ $totalCouponDiscount ?? 0 }};

    // ============================================
    // PAYMENT METHOD SELECTION
    // ============================================
    function selectPayment(element, method) {
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('active');
        });
        element.classList.add('active');
        document.getElementById('payment_method').value = method;
    }

    // ============================================
    // UPDATE CART
    // ============================================
    function updateCart(itemId, quantity) {
        if (quantity < 1) return;

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
        .then(response => response.json())
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
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ============================================
    // UPDATE QUANTITY
    // ============================================
    function updateQuantity(itemId, change) {
        const input = document.getElementById('qty-' + itemId);
        if (!input) return;

        let newQty = parseInt(input.value) + change;
        if (newQty < 1) newQty = 1;
        if (newQty > parseInt(input.max)) newQty = parseInt(input.max);

        input.value = newQty;
        updateCart(itemId, newQty);
    }

    // ============================================
    // REMOVE FROM CART
    // ============================================
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            text: 'Item removed from cart.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }

    // ============================================
    // APPLY COUPON
    // ============================================
    function applyCoupon() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const messageDiv = document.getElementById('couponMessage');
        const successDiv = document.getElementById('couponSuccessMessage');

        messageDiv.classList.add('hidden');
        successDiv.classList.add('hidden');

        if (!couponCode) {
            messageDiv.className = 'mt-1 text-xs text-red-600';
            messageDiv.textContent = 'Please enter a coupon code';
            messageDiv.classList.remove('hidden');
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
                coupon_code: couponCode
            })
        })
        .then(response => response.json())
        .then(data => {
            applyBtn.textContent = originalText;
            applyBtn.disabled = false;

            if (data.success) {
                successDiv.className = 'mt-1 text-xs text-green-600';
                successDiv.innerHTML = `
                    <div class="bg-green-50 p-2 rounded">
                        <strong>${data.message}</strong>
                        ${data.applied_products ? '<br>Applied to: ' + data.applied_products.join(', ') : ''}
                        <br>Total savings: {{ setting('currency', 'BDT') }} ${parseFloat(data.total_discount).toFixed(2)}
                    </div>
                `;
                successDiv.classList.remove('hidden');

                currentCouponDiscount = parseFloat(data.total_discount);
                updateTotals();
                document.getElementById('couponCode').value = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Coupon Applied!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 2000);
            } else {
                messageDiv.className = 'mt-1 text-xs text-red-600';
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            applyBtn.textContent = originalText;
            applyBtn.disabled = false;
            messageDiv.className = 'mt-1 text-xs text-red-600';
            messageDiv.textContent = 'Error applying coupon. Please try again.';
            messageDiv.classList.remove('hidden');
            console.error('Error:', error);
        });
    }

    // ============================================
    // UPDATE DELIVERY CHARGE
    // ============================================
    function updateDeliveryCharge() {
        const deliveryType = document.getElementById('deliveryType').value;
        let charge = 0;

        if (deliveryType === 'inside') {
            charge = {{ $deliveryChargeInside ?? 0 }};
        } else {
            charge = {{ $deliveryChargeOutside ?? 0 }};
        }

        currentDeliveryCharge = charge;
        document.getElementById('deliveryChargeDisplay').textContent = '{{ setting('currency', 'BDT') }} ' + charge.toFixed(2);
        updateTotals();
    }

    // ============================================
    // UPDATE TOTALS
    // ============================================
    function updateTotals() {
        const subtotal = currentSubtotal;
        const productDiscount = currentProductDiscount;
        const couponDiscount = currentCouponDiscount;

        const totalAfterDiscount = subtotal - productDiscount - couponDiscount;
        const vat = totalAfterDiscount * (currentVatRate / 100);
        const total = totalAfterDiscount + vat + currentDeliveryCharge;

        document.getElementById('vatAmount').textContent = '{{ setting('currency', 'BDT') }} ' + vat.toFixed(2);
        document.getElementById('grandTotal').textContent = '{{ setting('currency', 'BDT') }} ' + total.toFixed(2);
    }

    // ============================================
    // ENTER KEY FOR COUPON
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('couponCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });

        updateTotals();
    });

    // ============================================
    // ORDER CONFIRMATION
    // ============================================
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('confirmOrderBtn');
        const originalText = btn.innerHTML;

        // Validate form
        const name = document.getElementById('customer_name').value.trim();
        const email = document.getElementById('customer_email').value.trim();
        const phone = document.getElementById('customer_phone').value.trim();
        const address = document.getElementById('shipping_address').value.trim();
        const city = document.getElementById('city').value.trim();

        if (!name || !email || !phone || !address || !city) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all required fields.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        // Show loading
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-sm"></span> Processing...';

        const formData = new FormData(this);
        // Add cart total and items
        formData.append('cart_total', currentSubtotal);
        formData.append('total_after_discount', currentSubtotal - currentProductDiscount - currentCouponDiscount);
        formData.append('vat_amount', (currentSubtotal - currentProductDiscount - currentCouponDiscount) * (currentVatRate / 100));
        formData.append('delivery_charge', currentDeliveryCharge);
        formData.append('grand_total', currentSubtotal - currentProductDiscount - currentCouponDiscount +
                         ((currentSubtotal - currentProductDiscount - currentCouponDiscount) * (currentVatRate / 100)) +
                         currentDeliveryCharge);

        fetch('{{ route('order.place') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Placed! 🎉',
                    text: 'Your order has been placed successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {

                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    text: data.message || 'Failed to place order. Please try again.',
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
</script>
@endpush
@endsection
