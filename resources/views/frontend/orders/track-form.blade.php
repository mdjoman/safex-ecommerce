@extends('frontend.layouts.master')

@section('title', 'Track Your Order - SafeX Engineering')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm text-gray-500">
        <a href="/" class="hover:text-blue-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Products</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Track Order</span>
    </nav>

    <!-- Track Form Section -->
    <div id="trackFormSection" class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Track Your Order</h2>
                        <p class="text-blue-100 text-sm">Enter your order ID to track your shipment</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                <form id="trackForm" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Order ID
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="order_id"
                                name="order_id"
                                placeholder="Enter your order ID (e.g., ORD-000001)"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                required
                                autocomplete="off"
                            >
                        </div>
                        <div id="inputError" class="mt-2 text-sm text-red-600 hidden"></div>
                    </div>

                    <button
                        type="submit"
                        id="trackBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02] flex items-center justify-center space-x-2"
                    >
                        <span id="btnSpinner" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span id="btnText">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Track Order
                        </span>
                    </button>
                </form>

                <!-- Help Text -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Need help finding your order ID?</span>
                        <a href="{{ route('contact.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Contact Support →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900">Real-time Updates</h4>
                <p class="text-sm text-gray-600 mt-1">Track your order status instantly</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900">Secure</h4>
                <p class="text-sm text-gray-600 mt-1">Your information is protected</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900">24/7 Support</h4>
                <p class="text-sm text-gray-600 mt-1">We're here to help anytime</p>
            </div>
        </div>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="hidden max-w-5xl mx-auto"></div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('trackForm');
    const submitBtn = document.getElementById('trackBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const orderInput = document.getElementById('order_id');
    const errorDiv = document.getElementById('inputError');
    const resultSection = document.getElementById('resultSection');
    const trackFormSection = document.getElementById('trackFormSection');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        errorDiv.classList.add('hidden');
        orderInput.classList.remove('border-red-500');

        const orderId = orderInput.value.trim();
        if (!orderId) {
            errorDiv.textContent = 'Please enter your order ID';
            errorDiv.classList.remove('hidden');
            orderInput.classList.add('border-red-500');
            return;
        }

        // Show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("order.track") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    order_id: orderId
                })
            });

            const data = await response.json();

            if (data.success) {
                renderOrderResult(data.order);
                trackFormSection.classList.add('hidden');
                resultSection.classList.remove('hidden');
                resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Order Not Found',
                    text: data.message || 'Please check your order ID and try again.',
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'Try Again'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Please check your connection and try again.',
                confirmButtonColor: '#2563eb'
            });
        } finally {
            submitBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnSpinner.classList.add('hidden');
        }
    });

    function renderOrderResult(order) {
        const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'processing': 'bg-blue-100 text-blue-800',
            'shipped': 'bg-indigo-100 text-indigo-800',
            'delivered': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };

        const statusSteps = ['pending', 'processing', 'shipped', 'delivered'];
        const currentStep = statusSteps.indexOf(order.order_status);

        let stepsHtml = '';
        statusSteps.forEach((step, index) => {
            const isCompleted = index < currentStep;
            const isActive = index === currentStep && order.order_status !== 'cancelled';
            const isCancelled = order.order_status === 'cancelled';

            let circleClass = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold ';
            let lineClass = 'flex-1 h-0.5 mx-2 ';

            if (isCancelled) {
                if (index === 0) {
                    circleClass += 'bg-green-500 text-white';
                } else {
                    circleClass += 'bg-gray-200 text-gray-400';
                }
                lineClass += 'bg-gray-200';
            } else if (isCompleted) {
                circleClass += 'bg-green-500 text-white';
                lineClass += 'bg-green-500';
            } else if (isActive) {
                circleClass += 'bg-blue-600 text-white ring-4 ring-blue-200';
                lineClass += 'bg-gray-200';
            } else {
                circleClass += 'bg-gray-200 text-gray-400';
                lineClass += 'bg-gray-200';
            }

            stepsHtml += `
                <div class="flex items-center flex-1">
                    ${index > 0 ? `<div class="${lineClass}"></div>` : ''}
                    <div class="flex flex-col items-center">
                        <div class="${circleClass}">
                            ${isCompleted ?
                                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
                                (isActive ? (index + 1) : (index + 1))
                            }
                        </div>
                        <span class="text-xs mt-1 font-medium ${isActive ? 'text-blue-600' : isCompleted ? 'text-green-600' : 'text-gray-400'}">
                            ${step.charAt(0).toUpperCase() + step.slice(1)}
                        </span>
                    </div>
                </div>
            `;
        });

        // Build items HTML
        let itemsHtml = '';
        if (order.items && order.items.length > 0) {
            order.items.forEach(item => {
                const imageUrl = item.product_image || '{{ asset("storage/default.jpg") }}';
                itemsHtml += `
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="${imageUrl}" alt="${item.product_name}" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">${item.product_name}</p>
                                    <p class="text-sm text-gray-500">SKU: ${item.sku || 'N/A'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center">${item.quantity}</td>
                        <td class="py-4 px-4 text-right">$${parseFloat(item.price).toFixed(2)}</td>
                        <td class="py-4 px-4 text-right font-medium">$${(item.price * item.quantity).toFixed(2)}</td>
                    </tr>
                `;
            });
        }

        // Format shipping address
        const shippingAddress = order.shipping_address || 'N/A';

        resultSection.innerHTML = `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Order Details</h2>
                            <p class="text-blue-100 text-sm">Order #${order.order_id}</p>
                        </div>
                        <div class="mt-3 md:mt-0">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold ${statusColors[order.order_status] || 'bg-gray-100 text-gray-800'}">
                                <span class="w-2 h-2 rounded-full mr-2
                                    ${order.order_status === 'delivered' ? 'bg-green-500' :
                                      order.order_status === 'shipped' ? 'bg-indigo-500' :
                                      order.order_status === 'processing' ? 'bg-blue-500' :
                                      order.order_status === 'cancelled' ? 'bg-red-500' :
                                      'bg-yellow-500'}">
                                </span>
                                ${order.order_status ? order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1) : 'Pending'}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8">
                    <!-- Status Steps -->
                    <div class="mb-8">
                        <div class="flex items-center">
                            ${stepsHtml}
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Customer Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Customer:</span>
                                    <span class="font-medium text-gray-900">${order.customer_name || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium text-gray-900">${order.customer_email || 'N/A'}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="font-medium text-gray-900">${order.customer_phone || 'N/A'}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Order Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Order Date:</span>
                                    <span class="font-medium text-gray-900">${new Date(order.created_at).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="font-medium text-gray-900">${order.payment_method ? order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1) : 'N/A'}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Payment Status:</span>
                                    <span class="font-medium text-gray-900">${order.payment_status ? order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1) : 'N/A'}</span>
                                </div>
                                ${order.shipping_tracking ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tracking:</span>
                                    <span class="font-medium text-gray-900">${order.shipping_tracking}</span>
                                </div>
                                ` : ''}
                                ${order.delivered_at ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Delivered:</span>
                                    <span class="font-medium text-gray-900">${new Date(order.delivered_at).toLocaleDateString()}</span>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Shipping Address</h4>
                            <div class="text-sm text-gray-700">
                                <p>${shippingAddress}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml || '<tr><td colspan="4" class="text-center py-8 text-gray-500">No items found</td></tr>'}
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Subtotal:</td>
                                        <td class="px-4 py-3 text-right font-medium">$${parseFloat(order.subtotal).toFixed(2)}</td>
                                    </tr>
                                    ${order.discount_amount > 0 ? `
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Discount (${order.discount_code || 'N/A'}):</td>
                                        <td class="px-4 py-3 text-right font-medium text-green-600">-$${parseFloat(order.discount_amount).toFixed(2)}</td>
                                    </tr>
                                    ` : ''}
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Tax:</td>
                                        <td class="px-4 py-3 text-right font-medium">$${parseFloat(order.tax).toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium text-gray-700">Shipping:</td>
                                        <td class="px-4 py-3 text-right font-medium">$${parseFloat(order.shipping_cost).toFixed(2)}</td>
                                    </tr>
                                    <tr class="text-lg">
                                        <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900">Total:</td>
                                        <td class="px-4 py-3 text-right font-bold text-blue-600">$${parseFloat(order.total).toFixed(2)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    ${order.notes ? `
                    <div class="mb-8">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">${order.notes}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    ${order.cancellation_reason ? `
                    <div class="mb-8">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700"><strong>Cancellation Reason:</strong> ${order.cancellation_reason}</p>
                                    ${order.cancelled_at ? `<p class="text-sm text-red-600 mt-1">Cancelled on: ${new Date(order.cancelled_at).toLocaleDateString()}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="trackAnotherOrder()" class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-lg font-medium text-gray-700 hover:border-blue-600 hover:text-blue-600 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Track Another Order
                        </button>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                            </svg>
                            Continue Shopping
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    window.trackAnotherOrder = function() {
        resultSection.classList.add('hidden');
        trackFormSection.classList.remove('hidden');
        document.getElementById('order_id').value = '';
        trackFormSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };
});
</script>
@endpush
@endsection
