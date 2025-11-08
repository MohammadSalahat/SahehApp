<div class="min-h-screen bg-gradient-to-br from-white-1 to-[#faf9f5] flex items-center justify-center ">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-1/2 p-8 md:p-8">
                <!-- Logo -->
                <div class="mb-8 flex  flex-col items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="شعار صحيح" class=" h-14 mb-6 ">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('auth.register_title') }}</h2>
                    <p class="text-gray-600">{{ __('auth.register_subtitle') }}</p>
                </div>

                <!-- Register Form -->
                <form method="POST" wire:submit="register" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <flux:input wire:model="name" type="text" required autofocus autocomplete="name"
                            placeholder="{{ __('auth.full_name_placeholder') }}" />
                    </div>

                    <!-- Email -->
                    <div>
                        <flux:input wire:model="email" type="email" required autocomplete="email"
                            placeholder="{{ __('auth.email_placeholder') }}" />
                    </div>

                    <!-- Password -->
                    <div>
                        <flux:input wire:model="password" type="password" required autocomplete="new-password"
                            placeholder="{{ __('auth.password_placeholder') }}" viewable />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <flux:input wire:model="password_confirmation" type="password" required
                            autocomplete="new-password" placeholder="{{ __('auth.password_confirmation_placeholder') }}"
                            viewable />
                    </div>

                    <!-- Register Button -->
                    <flux:button type="submit" variant="primary"
                        class="w-full bg-[#4a6b5a] hover:bg-[#3d5a4a] text-white py-3 rounded-lg font-semibold transition-colors">
                        {{ __('auth.register_button') }}
                    </flux:button>
                </form>

                <!-- Login Link -->
                <div class="text-center mt-6">
                    <span class="text-gray-600 text-sm">{{ __('auth.already_have_account') }}</span>
                    <flux:link :href="route('login')" wire:navigate
                        class="text-[#4a6b5a] hover:text-[#d4b896] font-semibold text-sm mr-1">
                        {{ __('auth.login') }}
                    </flux:link>
                </div>
            </div>

            <!-- Right Side - Image Section -->
            <div class="hidden md:block w-full md:w-1/2 bg-gradient-to-br from-[#d4b896] to-[#4a6b5a] relative">
                <div class="absolute inset-0 flex items-center justify-center p-12">
                    <div class="text-center text-white">
                        <h3 class="text-4xl font-bold mb-4">{{ __('auth.register_welcome_title') }}</h3>
                        <p class="text-xl opacity-90">{{ __('auth.register_welcome_subtitle') }}</p>

                        <!-- Decorative Elements -->
                        <div class="mt-12 flex justify-center gap-4">
                            <div class="w-3 h-3 bg-white/30 rounded-full"></div>
                            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
                            <div class="w-3 h-3 bg-white/30 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Background Pattern -->
                <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100">
            <p class="text-center text-gray-500 text-xs">
                {{ __('auth.footer_text') }}
            </p>
        </div>
    </div>
</div>