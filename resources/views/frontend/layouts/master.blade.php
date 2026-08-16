<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('locale') == 'bn' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'SafeX Engineering'))</title>
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
    @stack('styles')

    <style>
        /* ============================================
           SAFEX ENGINEERING COLOR PALETTE
           ============================================ */
        :root {
            /* Primary Colors */
            --safex-primary-navy: #021447;
            --safex-dark-blue: #03246E;
            --safex-royal-blue: #0637A1;
            --safex-bright-blue: #0658DC;

            /* Neutral Colors */
            --safex-white: #FAFAFA;
            --safex-light-gray: #E5E6E9;
            --safex-medium-gray: #C2C6D0;
            --safex-dark-gray: #363C54;

            /* Accent Colors */
            --safex-red: #CC2717;

            /* Utility Colors */
            --primary: #0637A1;
            --primary-dark: #03246E;
            --primary-light: #0658DC;
            --secondary: #363C54;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #CC2717;
            --info: #0658DC;

            /* Background Colors */
            --bg-primary: #FAFAFA;
            --bg-secondary: #E5E6E9;
            --bg-dark: #021447;

            /* Text Colors */
            --text-primary: #021447;
            --text-secondary: #363C54;
            --text-muted: #C2C6D0;
            --text-white: #FAFAFA;
        }
        /* SafeX Color Palette Classes */
        .bg-safex-primary-navy { background-color: #021447; }
        .bg-safex-dark-blue { background-color: #03246E; }
        .bg-safex-royal { background-color: #0637A1; }
        .bg-safex-bright { background-color: #0658DC; }
        .bg-safex-red { background-color: #CC2717; }
        .bg-safex-light { background-color: #E5E6E9; }
        .bg-safex-white { background-color: #FAFAFA; }

        .text-safex-white { color: #FAFAFA; }
        .text-safex-navy { color: #021447; }
        .text-safex-dark-blue { color: #03246E; }
        .text-safex-royal { color: #0637A1; }
        .text-safex-bright { color: #0658DC; }
        .text-safex-red { color: #CC2717; }
        .text-safex-light-gray { color: #E5E6E9; }
        .text-safex-medium-gray { color: #C2C6D0; }
        .text-safex-dark-gray { color: #363C54; }

        .border-safex-dark-blue { border-color: #03246E; }
        .border-safex-royal { border-color: #0637A1; }
        .border-safex-light-gray { border-color: #E5E6E9; }

        .hover\:bg-safex-bright:hover { background-color: #0658DC; }
        .hover\:text-safex-bright:hover { color: #0658DC; }
        .hover\:text-safex-red:hover { color: #CC2717; }
        .hover\:bg-safex-royal:hover { background-color: #0637A1; }

        /* ============================================
           BASE STYLES
           ============================================ */
        * {
            font-family: 'Inter', sans-serif;
        }

        [dir="rtl"] * {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        body {
            background-color: var(--safex-white);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Selection Color */
        ::selection {
            background: var(--safex-bright-blue);
            color: white;
        }

        /* ============================================
           SCROLLBAR
           ============================================ */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--safex-light-gray);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--safex-royal-blue);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--safex-dark-blue);
        }

        /* ============================================
           LOADING SPINNER
           ============================================ */
        .loader {
            border: 4px solid var(--safex-light-gray);
            border-top: 4px solid var(--safex-bright-blue);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ============================================
           TOAST CUSTOMIZATION
           ============================================ */
        .toast-success {
            background-color: var(--success) !important;
        }
        .toast-error {
            background-color: var(--danger) !important;
        }
        .toast-warning {
            background-color: var(--warning) !important;
        }
        .toast-info {
            background-color: var(--info) !important;
        }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn-primary {
            background-color: var(--safex-royal-blue);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--safex-dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 55, 161, 0.3);
        }

        .btn-secondary {
            background-color: var(--safex-dark-gray);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: var(--safex-primary-navy);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--safex-royal-blue);
            border: 2px solid var(--safex-royal-blue);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background-color: var(--safex-royal-blue);
            color: white;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--safex-red);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #b32012;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(204, 39, 23, 0.3);
        }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(2, 20, 71, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 30px rgba(2, 20, 71, 0.12);
            transform: translateY(-4px);
        }

        /* ============================================
           PRODUCT CARD
           ============================================ */
        .product-card {
            transition: all 0.3s ease;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(2, 20, 71, 0.06);
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(2, 20, 71, 0.12);
        }
        .product-card .product-image {
            position: relative;
            overflow: hidden;
        }
        .product-card .product-image img {
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        .product-card .product-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--safex-red);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .product-card .product-price {
            color: var(--safex-royal-blue);
            font-weight: 700;
            font-size: 1.25rem;
        }
        .product-card .product-price .original {
            color: var(--safex-medium-gray);
            text-decoration: line-through;
            font-size: 0.9rem;
            font-weight: 400;
            margin-left: 8px;
        }

        /* ============================================
           CART BADGE
           ============================================ */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--safex-red);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* ============================================
           WHATSAPP FLOAT BUTTON
           ============================================ */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            animation: whatsapp-pulse 2s infinite;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
        }

        @keyframes whatsapp-pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }

        /* ============================================
           NAVIGATION
           ============================================ */
        .nav-link {
            position: relative;
            color: var(--text-secondary);
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .nav-link:hover {
            color: var(--safex-royal-blue);
            background-color: rgba(6, 55, 161, 0.06);
        }
        .nav-link.active {
            color: var(--safex-royal-blue);
            background-color: rgba(6, 55, 161, 0.1);
            font-weight: 600;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--safex-bright-blue);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 60%;
        }

        /* ============================================
           SECTION HEADERS
           ============================================ */
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--safex-primary-navy);
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            color: var(--safex-dark-gray);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* ============================================
           BADGES
           ============================================ */
        .badge-primary {
            background: var(--safex-royal-blue);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: var(--success);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-danger {
            background: var(--safex-red);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-warning {
            background: var(--warning);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ============================================
           FORMS
           ============================================ */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--safex-light-gray);
            border-radius: 8px;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-primary);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--safex-bright-blue);
            box-shadow: 0 0 0 4px rgba(6, 88, 220, 0.1);
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            background: var(--safex-primary-navy);
            color: var(--safex-white);
        }
        .footer a {
            color: var(--safex-medium-gray);
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: white;
        }
        .footer .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        .footer .social-link:hover {
            background: var(--safex-bright-blue);
            transform: translateY(-3px);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 640px) {
            .section-title {
                font-size: 1.5rem;
            }
            .whatsapp-float {
                width: 50px;
                height: 50px;
                bottom: 20px;
                right: 20px;
            }
            .whatsapp-float i {
                font-size: 24px;
            }
        }

        /* ============================================
           LIVEVIEW OVERRIDES
           ============================================ */
        [x-cloak] {
            display: none !important;
        }

        .livewire-modal {
            z-index: 9999 !important;
        }

        .livewire-pagination .page-item {
            display: inline-block;
            margin: 0 2px;
        }

        .livewire-pagination .page-link {
            padding: 6px 12px;
            border: 1px solid var(--safex-light-gray);
            border-radius: 6px;
            color: var(--text-secondary);
            background: white;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .livewire-pagination .page-link:hover {
            background: var(--safex-light-gray);
        }

        .livewire-pagination .page-item.active .page-link {
            background-color: var(--safex-royal-blue);
            color: white;
            border-color: var(--safex-royal-blue);
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .text-safex-navy { color: var(--safex-primary-navy); }
        .text-safex-dark-blue { color: var(--safex-dark-blue); }
        .text-safex-royal { color: var(--safex-royal-blue); }
        .text-safex-bright { color: var(--safex-bright-blue); }
        .text-safex-red { color: var(--safex-red); }

        .bg-safex-navy { background-color: var(--safex-primary-navy); }
        .bg-safex-dark-blue { background-color: var(--safex-dark-blue); }
        .bg-safex-royal { background-color: var(--safex-royal-blue); }
        .bg-safex-bright { background-color: var(--safex-bright-blue); }
        .bg-safex-red { background-color: var(--safex-red); }
        .bg-safex-light { background-color: var(--safex-light-gray); }
        .bg-safex-white { background-color: var(--safex-white); }

        .border-safex-navy { border-color: var(--safex-primary-navy); }
        .border-safex-royal { border-color: var(--safex-royal-blue); }
        .border-safex-red { border-color: var(--safex-red); }

        .hover\:bg-safex-navy:hover { background-color: var(--safex-primary-navy); }
        .hover\:bg-safex-royal:hover { background-color: var(--safex-royal-blue); }
        .hover\:text-safex-royal:hover { color: var(--safex-royal-blue); }
        .hover\:text-safex-red:hover { color: var(--safex-red); }

        .shadow-safex {
            box-shadow: 0 4px 20px rgba(2, 20, 71, 0.08);
        }
        .shadow-safex-lg {
            box-shadow: 0 8px 40px rgba(2, 20, 71, 0.12);
        }
    </style>
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

    <!-- Livewire Scripts -->
    @livewireScripts

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
                        $('.cart-count').text(response.cart_count);
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
