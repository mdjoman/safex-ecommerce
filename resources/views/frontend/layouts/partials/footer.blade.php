<footer class="bg-gray-900 text-white">
    <!-- Newsletter Section -->
    <div class="border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <h3 class="text-xl font-bold"> {{ setting('subscribe_title', 'SafeX Engineering') }}</h3>
                    <p class="text-gray-400 text-sm mt-1">{{ setting('subscribe_description', 'Get the latest updates on new products and offers') }}</p>
                </div>
                <div>
                    <form action="{{ route('subscribe') }}" id="subscribeForm" method="POST" class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email" required
                               class="flex-1 px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div>
                <h3 class="text-lg font-bold mb-4">{{ setting('site_name', 'SafeX Engineering') }}</h3>
                <p class="text-gray-400 text-sm mb-4">
                    {{ setting('footer_description', 'Leading engineering solutions provider in Bangladesh. Quality products and services.') }}


                <div class="flex space-x-4">
                    <a href="{{ setting('facebook_page', '#') }}" class="text-gray-400 hover:text-blue-500 transition">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="{{ setting('twitter_handle', '#') }}" class="text-gray-400 hover:text-blue-400 transition">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="{{ setting('linkedin_page', '#') }}" class="text-gray-400 hover:text-blue-600 transition">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    <a href="{{ setting('youtube_channel', '#') }}" class="text-gray-400 hover:text-red-600 transition">
                        <i class="fab fa-youtube text-lg"></i>
                    </a>
                    <a href="{{ setting('instagram_page', '#') }}" class="text-gray-400 hover:text-pink-600 transition">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white transition">Products</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-gray-400 hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Customer Service</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('order.track.form') }}" class="text-gray-400 hover:text-white transition">Track Order</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-white transition">Terms & Conditions</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-gray-400 hover:text-white transition">Support</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start space-x-3 text-gray-400">
                        <i class="fas fa-map-marker-alt text-blue-500 mt-1"></i>
                        <span>{{ setting('address', 'House #123, Road #45, Gulshan, Dhaka') }}</span>
                    </li>
                    <li class="flex items-center space-x-3 text-gray-400">
                        <i class="fas fa-phone text-blue-500"></i>
                        <span>{{ setting('phone', '+880-2-1234567') }}</span>
                    </li>
                    <li class="flex items-center space-x-3 text-gray-400">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <span>{{ setting('email', 'info@safex.com') }}</span>
                    </li>
                    <li class="flex items-center space-x-3 text-gray-400">
                        <i class="fas fa-clock text-blue-500"></i>
                        <span>{{ setting('working_hours', 'Sun-Thu: 9AM - 6PM') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} {{ setting('site_name', 'SafeX Engineering')}}. All rights reserved.</p>
            {{-- <p class="mt-1">Developed with <i class="fas fa-heart text-red-500"></i> by SafeX Team</p> --}}
        </div>
    </div>
</footer>
@push('scripts')
<script>
    document.getElementById('subscribeForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const emailInput = form.querySelector('input[name="email"]');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Subscribing...';

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Show success with custom style
                Swal.fire({
                    icon: 'success',
                    title: '🎉 Subscribed!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#1a1a2e',
                    color: '#fff',
                    iconColor: '#10b981'
                });
                emailInput.value = '';
            } else {
                // Show error with custom style
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message || 'Something went wrong',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#2563eb',
                    background: '#1a1a2e',
                    color: '#fff'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Please check your internet connection.',
                confirmButtonColor: '#2563eb'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Subscribe';
        }
    });
</script>
@endpush
