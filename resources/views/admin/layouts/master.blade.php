<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - SafeX Engineering')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        /* Base Styles */
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f3f4f6;
        }

        /* Sidebar */
        .sidebar {
            height: calc(100vh - 64px);
            overflow-y: auto;
            background: #ffffff;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .main-content {
            height: calc(100vh - 64px);
            overflow-y: auto;
            background: #f9fafb;
        }

        .main-content::-webkit-scrollbar {
            width: 6px;
        }
        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .main-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        /* Sidebar Links */
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 8px;
            position: relative;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }

        .sidebar-link.active svg {
            color: white;
        }

        .sidebar-link:not(.active):hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .sidebar-link svg {
            transition: all 0.2s ease;
        }

        .sidebar-link:not(.active):hover svg {
            color: #3b82f6;
        }

        /* Navbar */
        .navbar {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Cards */
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }

        /* Toast Messages */
        .alert {
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 16px;
            animation: slideDown 0.3s ease;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 64px;
                width: 280px;
                height: calc(100vh - 64px);
                z-index: 40;
                transition: left 0.3s ease;
                box-shadow: 2px 0 12px rgba(0,0,0,0.1);
            }
            .sidebar.open {
                left: 0;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.3);
                z-index: 39;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .main-content {
                height: calc(100vh - 64px);
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="min-h-screen bg-gray-50">
        <!-- Admin Navbar -->
        <nav class="navbar bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle" class="md:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                            <span class="text-2xl font-bold text-blue-600">SafeX</span>
                            <span class="text-sm text-gray-500 hidden sm:inline">Admin</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <button class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </button>

                        <!-- User Info -->
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        </div>

                        <!-- Logout -->
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="text-gray-500 hover:text-red-600 transition">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        <div class="flex">
            <!-- Sidebar -->
            <div id="sidebar" class="sidebar w-64 bg-white shadow-lg flex-shrink-0">
                <nav class="mt-4 px-3 space-y-0.5">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-th-large w-5 h-5 mr-3 text-center"></i>
                        Dashboard
                    </a>

                    <!-- Catalog Section -->
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Catalog</div>

                    <a href="{{ route('admin.categories.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-tags w-5 h-5 mr-3 text-center"></i>
                        Categories
                    </a>

                    <a href="{{ route('admin.subcategories.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.subcategories*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-layer-group w-5 h-5 mr-3 text-center"></i>
                        Sub Categories
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.products*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-box w-5 h-5 mr-3 text-center"></i>
                        Products
                    </a>

                    <a href="{{ route('admin.landing-pages.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.landing-pages*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-file-alt w-5 h-5 mr-3 text-center"></i>
                        Landing Pages
                    </a>

                    <!-- Marketing Section -->
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketing</div>

                    <a href="{{ route('admin.banners.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.banners*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-image w-5 h-5 mr-3 text-center"></i>
                        Banners
                    </a>

                    <a href="{{ route('admin.campaigns.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.campaigns*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope w-5 h-5 mr-3 text-center"></i>
                        Campaigns
                    </a>

                    <!-- Sales Section -->
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales</div>

                    <a href="{{ route('admin.orders.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.orders*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3 text-center"></i>
                        Orders
                    </a>

                    <a href="{{ route('admin.leads.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.leads*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-users w-5 h-5 mr-3 text-center"></i>
                        Leads
                    </a>

                    <!-- Settings Section -->
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</div>

                    <a href="{{ route('admin.contact.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.contact*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-address-card w-5 h-5 mr-3 text-center"></i>
                        Contact
                    </a>

                    <a href="{{ route('admin.settings.index') }}"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.settings*') ? 'active' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-cog w-5 h-5 mr-3 text-center"></i>
                        Settings
                    </a>

                    <!-- Frontend Link -->
                    <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Website</div>

                    <a href="{{ route('home') }}" target="_blank"
                       class="sidebar-link group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-globe w-5 h-5 mr-3 text-center"></i>
                        Visit Website
                    </a>
                </nav>

                <!-- Sidebar Footer -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                    <div class="text-xs text-gray-500 text-center">
                        <span class="block font-medium">SafeX Engineering</span>
                        <span>v{{ config('app.version', '1.0') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 main-content">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success flex items-center justify-between">
                            <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
                            <button onclick="this.parentElement.remove()" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger flex items-center justify-between">
                            <span><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</span>
                            <button onclick="this.parentElement.remove()" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger flex items-center justify-between">
                            <div>
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Custom Scripts -->
    @stack('scripts')

    <script>
        $(document).ready(function() {
            // Mobile Menu Toggle
            const menuToggle = $('#mobile-menu-toggle');
            const sidebar = $('#sidebar');
            const overlay = $('#sidebar-overlay');

            menuToggle.on('click', function() {
                sidebar.toggleClass('open');
                overlay.toggleClass('show');
                const icon = $(this).find('i');
                icon.toggleClass('fa-bars fa-times');
            });

            overlay.on('click', function() {
                sidebar.removeClass('open');
                overlay.removeClass('show');
                menuToggle.find('i').removeClass('fa-times').addClass('fa-bars');
            });

            // Auto close alert after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);

            // Confirm delete
            $('.delete-confirm').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Status toggle with AJAX
            $('.status-toggle').on('change', function() {
                const id = $(this).data('id');
                const status = $(this).is(':checked') ? 'active' : 'inactive';
                const url = $(this).data('url');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
