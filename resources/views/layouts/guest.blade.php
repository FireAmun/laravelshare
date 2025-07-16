<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FileShare') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>

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
                background-color: rgba(255, 255, 255, 0.85);
                border: 1px solid rgba(209, 213, 219, 0.3);
            }
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-focus {
                transition: all 0.3s ease;
            }
            .input-focus:focus {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
            <!-- Logo and Brand -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 text-2xl font-bold text-gray-900 hover:text-indigo-600 transition-all duration-200 group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-200">
                        <i class="fas fa-cloud-upload-alt text-xl"></i>
                    </div>
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">FileShare</span>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="w-full max-w-md">
                <div class="auth-card rounded-2xl border border-gray-200/50 p-8 shadow-2xl">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} FileShare. Built with ❤️ by Amar Hassan Elshaer
                </p>
            </div>
        </div>
    </body>
</html>
