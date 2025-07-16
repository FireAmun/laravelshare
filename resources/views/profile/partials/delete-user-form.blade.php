<section class="space-y-6">
    <!-- Warning Notice -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ __('Danger Zone') }}
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Button -->
    <div class="flex justify-center">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 active:from-red-800 active:to-pink-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-trash-alt mr-2"></i>
            {{ __('Delete Account') }}
        </button>
    </div>

    <!-- Confirmation Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-8">
            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-r from-red-500 to-pink-600 flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ __('Delete Account?') }}
                </h2>
                <p class="text-gray-600">
                    {{ __('This action cannot be undone') }}
                </p>
            </div>

            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    {{ __('Once your account is deleted, all of your files, data, and resources will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <!-- Password Confirmation -->
                <div class="mb-6">
                    <x-input-label for="password" value="{{ __('Current Password') }}" class="text-gray-700 font-medium" />
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-red-500 focus:border-red-500 text-gray-900 placeholder-gray-500"
                            placeholder="{{ __('Enter your password to confirm') }}"
                            required />
                    </div>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="button"
                        x-on:click="$dispatch('close')"
                        class="inline-flex items-center px-6 py-3 bg-gray-300 border border-transparent rounded-xl font-semibold text-sm text-gray-700 tracking-wide hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 active:from-red-800 active:to-pink-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash-alt mr-2"></i>
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
