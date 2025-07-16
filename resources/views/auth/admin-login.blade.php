<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center mb-4">
            <i class="fas fa-shield-alt text-2xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Admin Login</h2>
        <p class="text-gray-600">Secure access for system administrators</p>
    </div>

    <!-- Security Notice -->
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-amber-800">
                    <strong>Administrator Access Only</strong><br>
                    This area is restricted to authorized system administrators.
                </p>
            </div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Administrator Email')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user-shield text-gray-400"></i>
                </div>
                <x-text-input id="email"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-gray-900 placeholder-gray-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter admin email address" />
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
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-gray-900 placeholder-gray-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter admin password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500 focus:ring-offset-0"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-amber-600 hover:text-amber-500 font-medium transition-colors duration-200"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Admin Sign In Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-shield-alt mr-2"></i>
                {{ __('Admin Sign In') }}
            </x-primary-button>
        </div>

        <!-- Back to Regular Login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Regular user?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to user login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
