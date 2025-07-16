<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center mb-4">
            <i class="fas fa-envelope-open text-2xl text-white"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h2>
        <p class="text-gray-600">Check your inbox to get started</p>
    </div>

    <!-- Verification Notice -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn\'t receive the email, we\'ll gladly send you another.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 font-medium">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <!-- Logout Option -->
        <div class="text-center pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="font-medium text-gray-600 hover:text-gray-500 transition-colors duration-200 text-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
        <h4 class="text-sm font-medium text-gray-900 mb-2">Need help?</h4>
        <ul class="text-xs text-gray-600 space-y-1">
            <li>• Check your spam/junk folder</li>
            <li>• Make sure you entered the correct email address</li>
            <li>• Wait a few minutes for the email to arrive</li>
            <li>• Contact support if you continue having issues</li>
        </ul>
    </div>
</x-guest-layout>
