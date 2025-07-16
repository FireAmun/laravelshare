<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LaravelShare') }} - Admin Dashboard</title>    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js with comprehensive fallback system -->
    <script>
        // Global flag to track Chart.js loading status
        window.chartJsAttempts = 0;
        window.chartJsMaxAttempts = 3;
        window.chartJsLoaded = false;

        function tryLoadChartJs(url, isLocal = false) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = url;
                script.async = true;

                script.onload = function() {
                    if (typeof Chart !== 'undefined') {
                        window.chartJsLoaded = true;
                        console.log('Chart.js loaded successfully from:', url);
                        resolve(true);
                    } else {
                        reject(new Error('Chart.js script loaded but Chart object not available'));
                    }
                };

                script.onerror = function() {
                    console.error('Failed to load Chart.js from:', url);
                    reject(new Error('Script loading failed'));
                };

                // Set timeout for loading
                setTimeout(() => {
                    if (!window.chartJsLoaded) {
                        reject(new Error('Chart.js loading timeout'));
                    }
                }, isLocal ? 5000 : 10000);

                document.head.appendChild(script);
            });
        }

        async function loadChartJsWithFallbacks() {
            const cdnUrls = [
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js',
                'https://unpkg.com/chart.js@4.4.0/dist/chart.min.js'
            ];

            for (let i = 0; i < cdnUrls.length; i++) {
                try {
                    window.chartJsAttempts++;
                    console.log(`Attempting to load Chart.js from CDN ${i + 1}:`, cdnUrls[i]);
                    await tryLoadChartJs(cdnUrls[i]);
                    return; // Success, exit the loop
                } catch (error) {
                    console.warn(`CDN ${i + 1} failed:`, error.message);
                    if (i === cdnUrls.length - 1) {
                        // All CDNs failed, try to create a minimal Chart.js mock
                        createChartJsMock();
                    }
                }
            }
        }

        function createChartJsMock() {
            console.warn('Creating Chart.js mock for graceful degradation');
            window.Chart = {
                version: 'mock-1.0.0',
                Chart: function() {
                    console.warn('Chart.js mock: Chart creation attempted but not functional');
                },
                register: function() {},
                defaults: {
                    plugins: {},
                    scales: {},
                    elements: {}
                }
            };

            // Dispatch custom event to notify dashboard
            window.dispatchEvent(new CustomEvent('chartjs-mock-loaded'));
        }

        // Start loading immediately
        loadChartJsWithFallbacks();
    </script>

    <!-- Backup: Traditional script tag as additional fallback -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"
            async
            onload="if (!window.chartJsLoaded && typeof Chart !== 'undefined') { window.chartJsLoaded = true; console.log('Chart.js loaded via backup script tag'); }"
            onerror="console.warn('Backup script tag also failed')">
    </script>

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced dropdown animations */
        .dropdown-enter {
            transition: all 200ms ease-out;
        }
        .dropdown-leave {
            transition: all 75ms ease-in;
        }

        /* Mobile menu animations */
        .animate-in {
            animation: slideIn 0.2s ease-out;
        }
        .animate-out {
            animation: slideOut 0.15s ease-in;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
        }

        /* Enhanced hover effects */
        .nav-item:hover {
            transform: translateY(-1px);
        }

        /* Smooth transitions for all interactive elements */
        .transition-enhanced {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Glassmorphism effect */
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Better ring effects */
        .ring-enhanced:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Simple Dropdown JavaScript (same as user layout) -->
    <script>
        // Simple dropdown toggle function
        function toggleAdminDropdown() {
            const menu = document.getElementById('admin-dropdown-menu');
            const button = document.getElementById('admin-menu-button');
            const arrow = document.getElementById('admin-dropdown-arrow');

            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                button.setAttribute('aria-expanded', 'true');
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('admin-dropdown-menu');
            const button = document.getElementById('admin-menu-button');
            const arrow = document.getElementById('admin-dropdown-arrow');

            if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');

            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                button.setAttribute('aria-expanded', 'true');
                // Add animation classes
                menu.classList.add('animate-in');
                menu.classList.remove('animate-out');
            } else {
                menu.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
                // Add animation classes
                menu.classList.add('animate-out');
                menu.classList.remove('animate-in');
            }
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');

            if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-200/50 sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-18 justify-between items-center">
                    <div class="flex items-center">
                        <!-- Brand/Logo Section -->
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
                                <!-- Logo -->
                                <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 flex items-center justify-center mr-4 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300 ring-2 ring-blue-100">
                                    <i class="fas fa-shield-halved text-white text-xl"></i>
                                </div>
                                <!-- Brand Text -->
                                <div class="flex flex-col">
                                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 bg-clip-text text-transparent">
                                        {{ config('app.name', 'LaravelShare') }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-500 -mt-0.5">Administration Panel</span>
                                </div>
                                <!-- Admin Badge -->
                                <div class="ml-4 flex items-center">
                                    <span class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-gradient-to-r from-blue-100 to-blue-50 rounded-full border border-blue-200 shadow-sm">
                                        <i class="fas fa-crown mr-1 text-blue-600"></i>Admin Panel
                                    </span>
                                </div>
                            </a>
                        </div>

                        <!-- Main Navigation -->
                        <div class="hidden sm:ml-8 sm:flex sm:items-center sm:space-x-1">
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50 border border-transparent hover:border-blue-100' }}">
                                <i class="fas fa-chart-line mr-2 text-sm"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}"
                               class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50 border border-transparent hover:border-blue-100' }}">
                                <i class="fas fa-users mr-2 text-sm"></i>
                                Users
                            </a>
                            <a href="{{ route('admin.files') }}"
                               class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.files') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50 border border-transparent hover:border-blue-100' }}">
                                <i class="fas fa-file-alt mr-2 text-sm"></i>
                                Files
                            </a>
                            <a href="{{ route('admin.settings') }}"
                               class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50 border border-transparent hover:border-blue-100' }}">
                                <i class="fas fa-cog mr-2 text-sm"></i>
                                Settings
                            </a>
                        </div>
                    </div>

                    <!-- Header Right Section -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-6">
                        <!-- System Status & Quick Stats -->
                        <div class="flex items-center space-x-6">
                            <!-- System Status Indicator -->
                            <div class="flex items-center space-x-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
                                <div class="relative">
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500"></div>
                                    <div class="absolute top-0 left-0 h-2.5 w-2.5 rounded-full bg-green-400 animate-ping"></div>
                                </div>
                                <span class="text-xs font-semibold text-green-700">System Online</span>
                            </div>

                            <!-- Quick Stats Dashboard -->
                            <div class="hidden xl:flex items-center space-x-4">
                                <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200">
                                    <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-users text-blue-600 text-xs"></i>
                                    </div>
                                    <div class="text-xs">
                                        <div class="font-bold text-blue-700">{{ \App\Models\User::count() }}</div>
                                        <div class="text-blue-500 -mt-0.5">Users</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200">
                                    <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-green-600 text-xs"></i>
                                    </div>
                                    <div class="text-xs">
                                        <div class="font-bold text-green-700">{{ \App\Models\File::count() }}</div>
                                        <div class="text-green-500 -mt-0.5">Files</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-200">
                                    <div class="h-6 w-6 rounded-full bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-clock text-orange-600 text-xs"></i>
                                    </div>
                                    <div class="text-xs">
                                        <div class="font-bold text-orange-700">{{ now()->format('H:i') }}</div>
                                        <div class="text-orange-500 -mt-0.5">Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative ml-4">
                            <div>
                                <button onclick="toggleAdminDropdown()"
                                        type="button"
                                        id="admin-menu-button"
                                        class="flex items-center space-x-3 rounded-xl bg-white/90 backdrop-blur-sm px-4 py-2.5 text-sm font-medium text-gray-700 shadow-md ring-1 ring-gray-200 hover:bg-white hover:shadow-lg hover:ring-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 border border-gray-100"
                                        aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <!-- User Avatar -->
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center shadow-md ring-2 ring-blue-100">
                                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <!-- User Info -->
                                    <div class="hidden lg:block text-left">
                                        <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    </div>
                                    <!-- Dropdown Arrow -->
                                    <svg id="admin-dropdown-arrow" class="h-4 w-4 text-gray-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown Menu -->
                            <div id="admin-dropdown-menu"
                                 style="display: none;"
                                 class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black/10 border border-gray-200 overflow-hidden"
                                 role="menu" aria-orientation="vertical" aria-labelledby="admin-menu-button" tabindex="-1">

                                <!-- User Info Header -->
                                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center shadow-lg ring-2 ring-blue-100">
                                            <span class="text-lg font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                                            <div class="flex items-center mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-crown mr-1"></i>Administrator
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group" role="menuitem">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors duration-200 mr-3">
                                            <i class="fas fa-home text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Back to Website</div>
                                            <div class="text-xs text-gray-500">Visit main site</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200 group" role="menuitem">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors duration-200 mr-3">
                                            <i class="fas fa-user text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Profile Settings</div>
                                            <div class="text-xs text-gray-500">Manage your account</div>
                                        </div>
                                    </a>
                                </div>

                                <div class="border-t border-gray-100"></div>
                                <div class="py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 group" role="menuitem">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-500 group-hover:bg-red-200 transition-colors duration-200 mr-3">
                                                <i class="fas fa-sign-out-alt text-sm"></i>
                                            </div>
                                            <div class="text-left">
                                                <div class="font-medium text-red-600">Sign Out</div>
                                                <div class="text-xs text-red-500">End your session</div>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button onclick="toggleMobileMenu()" type="button"
                                id="mobile-menu-button"
                                class="inline-flex items-center justify-center rounded-xl bg-white/90 backdrop-blur-sm p-2.5 text-gray-500 hover:bg-white hover:text-gray-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 border border-gray-200 shadow-md"
                                aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <!-- Mobile Menu Dropdown -->
                        <div id="mobile-menu"
                             style="display: none;"
                             class="absolute top-20 right-0 z-50 w-80 bg-white/95 backdrop-blur-md shadow-2xl rounded-xl border border-gray-200/50 overflow-hidden">
                            <div class="px-4 pt-4 pb-3 space-y-3">
                                <!-- Mobile Header with System Status -->
                                <div class="flex items-center justify-between mb-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center space-x-2">
                                        <div class="relative">
                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500"></div>
                                            <div class="absolute top-0 left-0 h-2.5 w-2.5 rounded-full bg-green-400 animate-ping"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-green-700">System Online</span>
                                    </div>
                                    <div class="text-xs font-medium text-gray-600">{{ now()->format('H:i') }}</div>
                                </div>

                                <!-- Mobile Navigation Links -->
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-500' }} mr-3">
                                        <i class="fas fa-chart-line text-sm"></i>
                                    </div>
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.users') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ request()->routeIs('admin.users') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-500' }} mr-3">
                                        <i class="fas fa-users text-sm"></i>
                                    </div>
                                    Users
                                </a>
                                <a href="{{ route('admin.files') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('admin.files') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ request()->routeIs('admin.files') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-500' }} mr-3">
                                        <i class="fas fa-file-alt text-sm"></i>
                                    </div>
                                    Files
                                </a>
                                <a href="{{ route('admin.settings') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-500' }} mr-3">
                                        <i class="fas fa-cog text-sm"></i>
                                    </div>
                                    Settings
                                </a>

                                <!-- Divider -->
                                <hr class="my-4 border-gray-200">

                                <!-- Mobile Quick Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="text-center p-3 rounded-lg bg-blue-50 border border-blue-200">
                                        <div class="text-xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                                        <div class="text-xs text-blue-500 font-medium">Users</div>
                                    </div>
                                    <div class="text-center p-3 rounded-lg bg-green-50 border border-green-200">
                                        <div class="text-xl font-bold text-green-600">{{ \App\Models\File::count() }}</div>
                                        <div class="text-xs text-green-500 font-medium">Files</div>
                                    </div>
                                </div>

                                <!-- Mobile User Profile -->
                                <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center shadow-lg ring-2 ring-blue-100">
                                            <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-600 truncate">{{ auth()->user()->email }}</p>
                                            <div class="flex items-center mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-crown mr-1"></i>Administrator
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Menu Actions -->
                                <a href="{{ route('home') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200 mr-3">
                                        <i class="fas fa-home text-sm"></i>
                                    </div>
                                    Back to Website
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center px-4 py-3 rounded-xl text-base font-medium text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200 mr-3">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                    Profile Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full px-4 py-3 rounded-xl text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50 transition-all duration-200">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-500 hover:bg-red-200 transition-colors duration-200 mr-3">
                                            <i class="fas fa-sign-out-alt text-sm"></i>
                                        </div>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="rounded-lg bg-green-50 p-4 border border-green-200 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="inline-flex rounded-md bg-green-50 p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-4">
                    <div class="rounded-lg bg-red-50 p-4 border border-red-200 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button type="button" class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
