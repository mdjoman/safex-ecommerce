@extends('admin.layouts.app')

@section('title', 'Checkout - SafeX Engineering')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="customer_name" id="customer_name" required
                                   value="{{ auth()->user()->name ?? '' }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="customer_email" id="customer_email" required
                                   value="{{ auth()->user()->email ?? '' }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                        <input type="text" name="customer_phone" id="customer_phone" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @error('customer_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h2>
                    <div class="mb-6">
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" required
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                        @error('shipping_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Billing Address (Optional)</label>
                        <textarea name="billing_address" id="billing_address" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                        <p class="text-sm text-gray-500 mt-1">Leave empty to use shipping address</p>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>
                    <div class="mb-6">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="cod" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-gray-700">Cash on Delivery</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="bkash"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-gray-700">bKash</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="nagad"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-gray-700">Nagad</span>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                        Place Order
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($cart->items as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . $item->product->featured_image) }}" alt="{{ $item->product->name }}"
                                 class="h-12 w-12 object-cover rounded-lg">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">BDT {{ number_format($item->product->price * $item->quantity, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">BDT {{ number_format($cart->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">VAT (15%)</span>
                        <span class="font-medium">BDT {{ number_format($cart->total * 0.15, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">Free</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-blue-600">BDT {{ number_format($cart->total * 1.15, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p>By placing your order, you agree to our <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Terms & Conditions</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
