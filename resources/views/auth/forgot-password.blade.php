<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center mb-4">
            <i class="fas fa-key text-2xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
        <p class="text-gray-600">We'll send you a reset link</p>
    </div>

    <!-- Description -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-800">
            {{ __('Forgot your password? No problem. Just enter your email address and we\'ll send you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                    placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Send Reset Link Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('Send Password Reset Link') }}
            </x-primary-button>
        </div>

        <!-- Back to Login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to sign in
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
