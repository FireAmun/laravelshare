<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
        <p class="text-gray-600">Join FileShare and start sharing files securely</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <x-text-input id="name"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                    autocomplete="new-password"
                    placeholder="Create a strong password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
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
                    placeholder="Confirm your password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms Agreement -->
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs text-gray-600">
                By creating an account, you agree to our terms of service and privacy policy.
                Your files are encrypted and protected with enterprise-grade security.
            </p>
        </div>

        <!-- Register Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    Sign in here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
