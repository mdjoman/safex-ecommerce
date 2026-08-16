@extends('frontend.layouts.master')


@section('title', 'Products - SafeX Engineering')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Categories</h3>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                           class="text-gray-600 hover:text-blue-600 block {{ request('category') == $category->id ? 'text-blue-600 font-medium' : '' }}">
                            {{ $category->name }}
                            <span class="text-sm text-gray-400">({{ $category->products->where('status', 'active')->count() }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sub Categories</h3>
                <ul class="space-y-2">
                    @foreach($subCategories as $subCategory)
                    <li>
                        <a href="{{ route('products.index', ['subcategory' => $subCategory->id]) }}"
                           class="text-gray-600 hover:text-blue-600 block {{ request('subcategory') == $subCategory->id ? 'text-blue-600 font-medium' : '' }}">
                            {{ $subCategory->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">All Products</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $products->total() }} products</span>
                    <select onchange="window.location.href=this.value" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            Newest
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                            Price: Low to High
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                            Price: High to Low
                        </option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="{{ route('product.show', $product->slug) }}">
                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <div class="text-sm text-gray-500 mb-1">{{ $product->category->name }}</div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">{{ Str::limit($product->short_description, 50) }}</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xl font-bold text-blue-600">BDT {{ number_format($product->price, 2) }}</span>
                                @if($product->discount_price)
                                    <span class="text-sm text-gray-400 line-through ml-2">BDT {{ number_format($product->selling_price, 2) }}</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $product->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">No products found</p>
                </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addToCart(productId) {
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
