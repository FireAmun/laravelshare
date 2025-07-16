<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel File Share'))</title>    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Dropdown functionality -->
    <script>
        // Ensure Alpine.js loads properly
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js loaded successfully');
        });

        // Fallback dropdown functionality if Alpine.js fails
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                // Check if Alpine.js is loaded
                if (typeof Alpine === 'undefined') {
                    console.warn('Alpine.js not loaded, using fallback');
                    initFallbackDropdown();
                }
            }, 1000);
        });

        function initFallbackDropdown() {
            const dropdownButton = document.querySelector('[data-dropdown-button]');
            const dropdownMenu = document.querySelector('[data-dropdown-menu]');

            if (dropdownButton && dropdownMenu) {
                let isOpen = false;

                dropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isOpen = !isOpen;
                    dropdownMenu.style.display = isOpen ? 'block' : 'none';
                    dropdownButton.setAttribute('aria-expanded', isOpen);
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        isOpen = false;
                        dropdownMenu.style.display = 'none';
                        dropdownButton.setAttribute('aria-expanded', false);
                    }
                });
            }
        }

        // Simple dropdown toggle function with improved mobile support
        function toggleDropdown(event) {
            if (event) {
                event.stopPropagation();
            }

            const menu = document.getElementById('user-dropdown-menu');
            const button = document.getElementById('user-menu-button');
            const arrow = document.getElementById('dropdown-arrow');
            const isMobile = window.innerWidth <= 768;

            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
                button.setAttribute('aria-expanded', 'true');
                arrow.style.transform = 'rotate(180deg)';

                // Add mobile-specific behavior
                if (isMobile) {
                    document.body.classList.add('dropdown-open');
                    // Create backdrop for mobile
                    const backdrop = document.createElement('div');
                    backdrop.className = 'dropdown-backdrop';
                    backdrop.id = 'dropdown-backdrop';
                    backdrop.onclick = closeDropdown;
                    document.body.appendChild(backdrop);
                }
            } else {
                closeDropdown();
            }
        }

        // Close dropdown function
        function closeDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            const button = document.getElementById('user-menu-button');
            const arrow = document.getElementById('dropdown-arrow');
            const backdrop = document.getElementById('dropdown-backdrop');

            if (menu) {
                menu.style.display = 'none';
                button.setAttribute('aria-expanded', 'false');
                arrow.style.transform = 'rotate(0deg)';

                // Remove mobile-specific elements
                document.body.classList.remove('dropdown-open');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('user-dropdown-menu');
            const button = document.getElementById('user-menu-button');

            if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close dropdown on touch events for mobile
        document.addEventListener('touchstart', function(e) {
            const menu = document.getElementById('user-dropdown-menu');
            const button = document.getElementById('user-menu-button');

            if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            closeDropdown();
        });
    </script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }        [x-cloak] { display: none !important; }

        /* Dropdown animation improvements */
        .dropdown-enter {
            transition: all 200ms ease-out;
        }
        .dropdown-enter-start {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        .dropdown-enter-end {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        /* Mobile touch improvements */
        @media (max-width: 768px) {
            #user-dropdown-menu {
                position: fixed !important;
                top: 60px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                max-width: none !important;
                margin: 0 !important;
                z-index: 9999 !important;
            }

            /* Prevent scrolling when dropdown is open */
            body.dropdown-open {
                overflow: hidden;
            }

            /* Add backdrop for mobile */
            .dropdown-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.3);
                z-index: 9998;
            }
        }
    </style>

    <!-- Alpine.js initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized successfully');
        });
    </script>

    <!-- Debug Script (remove in production) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');

            // Check if Alpine.js loads
            setTimeout(() => {
                if (typeof Alpine !== 'undefined') {
                    console.log('✅ Alpine.js is working');
                } else {
                    console.log('❌ Alpine.js failed to load');
                }

                // Test dropdown elements
                const dropdownButton = document.querySelector('[data-dropdown-button]');
                const dropdownMenu = document.querySelector('[data-dropdown-menu]');

                if (dropdownButton && dropdownMenu) {
                    console.log('✅ Dropdown elements found');
                } else {
                    console.log('❌ Dropdown elements missing');
                }
            }, 2000);
        });
    </script>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="glass-effect border-b border-gray-200/20 sticky top-0 z-50 backdrop-blur-md" x-data="{ mobileOpen: false }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ route('home') }}" class="flex items-center space-x-3 text-xl font-bold text-gray-900 hover:text-indigo-600 transition-all duration-200 group">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                </div>
                                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">FileShare</span>
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-8 sm:flex sm:space-x-8">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-300' }}">
                                <i class="fas fa-home mr-2"></i>
                                Home
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-300' }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Dashboard
                                </a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('security.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-all duration-200 {{ request()->routeIs('security.dashboard') ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-600 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-300' }}">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Security
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-white/80 backdrop-blur-sm px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-white hover:shadow-md hover:text-indigo-600 transition-all duration-200">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-indigo-600 hover:to-purple-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-user-plus mr-2"></i>
                                Sign Up
                            </a>
                        @else
                            <!-- Profile dropdown - Simplified and reliable -->
                            <div class="relative ml-3">
                                <div>
                                    <button onclick="toggleDropdown(event)"
                                            type="button"
                                            id="user-menu-button"
                                            class="flex items-center space-x-3 rounded-full bg-white/80 backdrop-blur-sm px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-white hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200"
                                            aria-expanded="false"
                                            aria-haspopup="true">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold shadow-md">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="hidden md:block text-left">
                                            <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                        <svg id="dropdown-arrow" class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Dropdown menu -->
                                <div id="user-dropdown-menu"
                                     style="display: none;"
                                     class="absolute right-0 z-50 mt-2 w-72 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black/10 border border-gray-200"
                                     role="menu"
                                     aria-orientation="vertical"
                                     aria-labelledby="user-menu-button">

                                    <!-- User info header -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                                @if(auth()->user()->is_admin)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                                        <i class="fas fa-crown mr-1"></i>
                                                        Admin
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Menu items -->
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}"
                                           onclick="closeDropdown()"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150"
                                           role="menuitem">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                                <i class="fas fa-user-edit text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium">Your Profile</div>
                                                <div class="text-xs text-gray-500">Manage your account</div>
                                            </div>
                                        </a>

                                        <a href="{{ route('dashboard') }}"
                                           onclick="closeDropdown()"
                                           class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150"
                                           role="menuitem">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 mr-3">
                                                <i class="fas fa-tachometer-alt text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium">Dashboard</div>
                                                <div class="text-xs text-gray-500">View your files</div>
                                            </div>
                                        </a>

                                        @if(auth()->user()->is_admin)
                                            <a href="{{ route('security.dashboard') }}"
                                               onclick="closeDropdown()"
                                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150"
                                               role="menuitem">
                                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 text-purple-600 mr-3">
                                                    <i class="fas fa-shield-alt text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Security</div>
                                                    <div class="text-xs text-gray-500">System monitoring</div>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Logout section -->
                                    <div class="border-t border-gray-100 py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    onclick="closeDropdown()"
                                                    class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-150"
                                                    role="menuitem">
                                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 mr-3">
                                                    <i class="fas fa-sign-out-alt text-sm"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Sign out</div>
                                                    <div class="text-xs text-red-500">End your session</div>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileOpen = !mobileOpen"
                                type="button"
                                class="inline-flex items-center justify-center rounded-xl bg-white/80 backdrop-blur-sm p-3 text-gray-400 hover:bg-white hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                                aria-controls="mobile-menu"
                                :aria-expanded="mobileOpen">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-5 w-5 transition-transform duration-200"
                                 :class="{ 'rotate-90': mobileOpen }"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="sm:hidden bg-white/95 backdrop-blur-md border-t border-gray-200/50"
                 id="mobile-menu"
                 @click.outside="mobileOpen = false">
                <div class="space-y-1 px-4 py-4">
                    <a href="{{ route('home') }}"
                       class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'bg-indigo-100 text-indigo-700 border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                        <i class="fas fa-home mr-3 w-5"></i>
                        Home
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700 border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                            <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                            Dashboard
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('security.dashboard') }}"
                               class="flex items-center px-4 py-3 rounded-xl text-base font-medium transition-all duration-200 {{ request()->routeIs('security.dashboard') ? 'bg-indigo-100 text-indigo-700 border-l-4 border-indigo-500' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }}">
                                <i class="fas fa-shield-alt mr-3 w-5"></i>
                                Security
                            </a>
                        @endif
                    @endauth
                </div>

                @guest
                    <div class="border-t border-gray-200/50 px-4 py-4">
                        <div class="space-y-3">
                            <a href="{{ route('login') }}"
                               class="flex w-full items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                               class="flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-3 text-sm font-semibold text-white shadow-lg hover:shadow-xl transition-all duration-200">
                                <i class="fas fa-user-plus mr-2"></i>
                                Sign Up
                            </a>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-200/50 px-4 py-4">
                        <div class="flex items-center space-x-3 px-4 py-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-base font-medium text-gray-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center px-4 py-3 rounded-xl text-base font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-all duration-200">
                                <i class="fas fa-user-edit mr-3 w-5"></i>
                                Your Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center px-4 py-3 rounded-xl text-base font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                                    <i class="fas fa-sign-out-alt mr-3 w-5"></i>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            @if (session('status'))
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="glass-effect border-t border-gray-200/20 mt-16 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Brand -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg">
                                <i class="fas fa-cloud-upload-alt text-sm"></i>
                            </div>
                            <span class="text-lg font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">FileShare</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Secure file sharing made simple. Upload, share, and manage your files with enterprise-grade security.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                <span>Enterprise Security</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-lock text-blue-500"></i>
                                <span>Encrypted</span>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Navigation</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                    <i class="fas fa-home mr-2"></i>Home
                                </a>
                            </li>
                            @auth
                                <li>
                                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                        <i class="fas fa-user-edit mr-2"></i>Profile
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                        <i class="fas fa-user-plus mr-2"></i>Sign Up
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Information -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Information</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('about') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                                    <i class="fas fa-info-circle mr-2"></i>About
                                </a>
                            </li>
                            <li>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-upload mr-2"></i>Max File Size: 5MB
                                </span>
                            </li>
                            <li>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-2"></i>Free Hosting Optimized
                                </span>
                            </li>
                        </ul>
                    </div>

                    <!-- Developer -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Developer</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    A
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Amar Hassan Elshaer</p>
                                    <p class="text-xs text-gray-500">Full-Stack Developer</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600">
                                Passionate about creating secure, user-friendly web applications with modern technologies.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="mt-8 pt-8 border-t border-gray-200/50">
                    <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
                        <p class="text-sm text-gray-500">
                            © {{ date('Y') }} FileShare. Built with ❤️ using Laravel & Tailwind CSS.
                        </p>
                        <div class="flex items-center space-x-4 text-xs text-gray-400">
                            <span>Version 1.0</span>
                            <span>•</span>
                            <span>Powered by advanced security features</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
