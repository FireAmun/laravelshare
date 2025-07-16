<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mb-4">
            <i class="fas fa-shield-alt text-2xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Set New Password</h2>
        <p class="text-gray-600">Choose a strong, secure password</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm bg-gray-50 text-gray-500"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    autocomplete="username"
                    readonly />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your new password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-gray-400"></i>
                </div>
                <x-text-input id="password_confirmation"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your new password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Password Requirements -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Password Requirements:</h4>
            <ul class="text-xs text-gray-600 space-y-1">
                <li>• At least 8 characters long</li>
                <li>• Mix of uppercase and lowercase letters</li>
                <li>• Include numbers and special characters</li>
                <li>• Avoid common passwords</li>
            </ul>
        </div>

        <!-- Reset Password Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-shield-alt mr-2"></i>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>

        <!-- Back to Login -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to sign in
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
