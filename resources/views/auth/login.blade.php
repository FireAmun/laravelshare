<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
        <p class="text-gray-600">Sign in to your FileShare account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-500 font-medium transition-colors duration-200"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Sign In Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <!-- Admin Login Button -->
        <div class="text-center mt-4">
            <a href="{{ route('admin.login') }}"
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:from-amber-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-shield-alt mr-2"></i>
                {{ __('Admin Login') }}
            </a>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    Create one now
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
