<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('locale') == 'bn' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', setting('site_name', 'SafeX Engineering'))</title>
    <meta name="description" content="@yield('meta_description', setting('meta_description', 'SafeX Engineering - Leading engineering solutions provider in Bangladesh'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('meta_keywords', 'engineering, industrial, equipment, safety, construction'))">
    <meta name="author" content="{{ setting('site_name', 'SafeX Engineering') }}">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', setting('site_name', 'SafeX Engineering'))">
    <meta property="og:description" content="@yield('og_description', setting('meta_description', 'SafeX Engineering - Leading engineering solutions provider in Bangladesh'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', setting('site_name', 'SafeX Engineering'))">
    <meta name="twitter:description" content="@yield('twitter_description', setting('meta_description', 'SafeX Engineering - Leading engineering solutions provider in Bangladesh'))">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/' . setting('site_favicon', 'favicon.ico')) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Toastr CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{asset('/frontend/master.css')}}">

    @stack('styles')

</head>
<body class="font-sans antialiased bg-safex-white">
    <div id="app" class="min-h-screen flex flex-col">

        <!-- Skip to content -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 focus:z-50 focus:bg-white focus:p-4 focus:shadow-lg">
            Skip to main content
        </a>

        <!-- Header -->
        @include('frontend.layouts.partials.header')

        <!-- Breadcrumb -->
        @hasSection('breadcrumb')
            @include('frontend.layouts.partials.breadcrumb')
        @endif

        <!-- Main Content -->
        <main id="main-content" class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('frontend.layouts.partials.footer')

        <!-- WhatsApp Chat Button -->
        @include('frontend.layouts.partials.whatsapp-chat')

        <!-- Back to Top Button -->
        <button id="back-to-top" class="fixed bottom-24 right-6 bg-safex-royal text-white p-3 rounded-full shadow-lg hover:bg-safex-dark-blue transition-all hidden z-40 hover:scale-110">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Swiper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Toastr JS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>



    <!-- Custom Scripts -->
    @stack('scripts')

    <script>
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Back to Top Button
        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });

            $('#back-to-top').click(function() {
                $('html, body').animate({scrollTop: 0}, 500);
                return false;
            });
        });

        // Initialize Swiper
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
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                });
            }

            // Product Slider
            const productSlider = document.querySelector('.product-slider');
            if (productSlider) {
                new Swiper(productSlider, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        },
                    },
                });
            }
        });

        // Add to Cart Function
        function addToCart(productId, quantity = 1) {
            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        document.querySelector('.cart-count').textContent = response.cart_count;
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        }

        // Remove from Cart
        function removeFromCart(itemId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to remove this item from cart?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#CC2717',
                cancelButtonColor: '#363C54',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("cart.remove") }}',
                        method: 'POST',
                        data: {
                            item_id: itemId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    });
                }
            });
        }

        // Language Switcher
        function switchLanguage(locale) {
            $.ajax({
                url: '{{ route("language.switch") }}',
                method: 'POST',
                data: {
                    locale: locale,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        toastr.error('Failed to switch language');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        }

        // Global error handler
        window.onerror = function(msg, url, line, col, error) {
            console.error('Error:', msg, url, line, col, error);
            return false;
        };
    </script>
</body>
</html>
