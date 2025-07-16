<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400"></i>
                </div>
                <x-text-input id="name"
                    name="name"
                    type="text"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email"
                    name="email"
                    type="email"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl shadow-sm input-focus focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 placeholder-gray-500"
                    :value="old('email', $user->email)"
                    required
                    autocomplete="username"
                    placeholder="Enter your email address" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-orange-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-800">
                                {{ __('Your email address is unverified.') }}
                            </p>
                            <button form="send-verification"
                                class="mt-2 inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-paper-plane mr-2"></i>
                                {{ __('Resend Verification Email') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-between pt-6">
            <x-primary-button class="px-8 py-3">
                <i class="fas fa-save mr-2"></i>
                {{ __('Update Profile') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <div class="flex items-center space-x-2 text-green-600"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm font-medium">{{ __('Profile updated successfully!') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
