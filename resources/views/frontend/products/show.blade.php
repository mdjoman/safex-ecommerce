@extends('frontend.layouts.master')

@section('title', $product->name . ' - SafeX Engineering')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex mb-6 text-sm text-gray-500">
        <a href="/" class="hover:text-blue-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
            </div>
            @if($product->gallery)
                <div class="grid grid-cols-4 gap-2 mt-4">
                    @foreach($product->gallery as $image)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer" onclick="changeMainImage('{{ asset('storage/' . $image) }}')">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="w-full h-24 object-cover">
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            <div class="text-sm text-gray-500 mb-4">SKU: {{ $product->sku }}</div>

            <div class="flex items-center mb-4">
                <span class="text-3xl font-bold text-blue-600">BDT {{ number_format($product->price, 2) }}</span>
                @if($product->discount_price)
                    <span class="text-lg text-gray-400 line-through ml-4">BDT {{ number_format($product->selling_price, 2) }}</span>
                    <span class="ml-4 bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                        Save {{ number_format((($product->selling_price - $product->discount_price) / $product->selling_price) * 100, 0) }}%
                    </span>
                @endif
            </div>

            <div class="mb-4">
                <span class="text-sm font-medium text-gray-700">Availability: </span>
                @if($product->stock_qty > 0)
                    <span class="text-green-600 font-medium">In Stock ({{ $product->stock_qty }} units)</span>
                @else
                    <span class="text-red-600 font-medium">Out of Stock</span>
                @endif
            </div>

            <div class="mb-4">
                <div class="text-sm text-gray-700 mb-2">
                    <span class="font-medium">Brand:</span> {{ $product->brand ?? 'N/A' }}
                </div>
                <div class="text-sm text-gray-700 mb-2">
                    <span class="font-medium">Model:</span> {{ $product->model ?? 'N/A' }}
                </div>
                <div class="text-sm text-gray-700">
                    <span class="font-medium">Category:</span> {{ $product->category->name }}
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <div>
                        <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_qty }}"
                               class="w-20 ml-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>
                    <button onclick="addToCart({{ $product->id }})"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition {{ $product->stock_qty <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $product->stock_qty <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                </div>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                <div class="text-gray-700">{{ $product->long_description }}</div>
            </div>

            @if($product->specification)
                <div class="border-t pt-4 mt-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Specifications</h3>
                    <div class="text-gray-700">{!! $product->specification !!}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count())
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <a href="{{ route('product.show', $relatedProduct->slug) }}">
                    <img src="{{ asset('storage/' . $relatedProduct->featured_image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                        <a href="{{ route('product.show', $relatedProduct->slug) }}" class="hover:text-blue-600">
                            {{ $relatedProduct->name }}
                        </a>
                    </h3>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-blue-600">BDT {{ number_format($relatedProduct->price, 2) }}</span>
                        <button onclick="addToCart({{ $relatedProduct->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function changeMainImage(src) {
        document.querySelector('.product-main-image img').src = src;
    }

    function addToCart(productId) {
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
                document.querySelector('.cart-count').textContent = data.cart_count;
                alert('Product added to cart!');
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
@endsection
