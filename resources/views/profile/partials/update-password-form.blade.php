<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <x-text-input id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 placeholder-gray-500"
                    autocomplete="current-password"
                    placeholder="Enter your current password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-gray-400"></i>
                </div>
                <x-text-input id="update_password_password"
                    name="password"
                    type="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 placeholder-gray-500"
                    autocomplete="new-password"
                    placeholder="Enter your new password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-gray-400"></i>
                </div>
                <x-text-input id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 placeholder-gray-500"
                    autocomplete="new-password"
                    placeholder="Confirm your new password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Password Requirements -->
        <div class="bg-green-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-green-900 mb-2">Password Requirements:</h4>
            <ul class="text-xs text-green-700 space-y-1">
                <li class="flex items-center space-x-2">
                    <i class="fas fa-check text-green-600"></i>
                    <span>At least 8 characters long</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-check text-green-600"></i>
                    <span>Mix of uppercase and lowercase letters</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-check text-green-600"></i>
                    <span>Include numbers and special characters</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-check text-green-600"></i>
                    <span>Avoid common passwords</span>
                </li>
            </ul>
        </div>

        <div class="flex items-center justify-between pt-6">
            <x-primary-button class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:ring-green-500">
                <i class="fas fa-shield-alt mr-2"></i>
                {{ __('Update Password') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <div class="flex items-center space-x-2 text-green-600"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm font-medium">{{ __('Password updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
