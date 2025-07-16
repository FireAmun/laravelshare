<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Security Check</h2>
        <p class="text-gray-600">Confirm your identity to continue</p>
    </div>

    <!-- Security Notice -->
    <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-orange-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-orange-800">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Current Password')" class="text-gray-700 font-medium" />
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
                    placeholder="Enter your current password"
                    autofocus />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Button -->
        <div class="space-y-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-check-circle mr-2"></i>
                {{ __('Confirm Password') }}
            </x-primary-button>
        </div>

        <!-- Back Link -->
        <div class="text-center pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                <a href="{{ url()->previous() }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Go back
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
