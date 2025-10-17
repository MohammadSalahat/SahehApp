<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-2 mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="شعار صحيح" class="w-16 h-16 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-[#4a6b5a]">
                {{ __('login') }}
            </h2>
            <p class="mt-2 text-gray-600">
                {{ __('أدخل بريدك الإلكتروني وكلمة المرور للدخول') }}
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#4a6b5a] to-[#d4b896]"></div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" wire:submit="login" class="space-y-6">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-right text-gray-700 font-semibold mb-2">
                        {{ __('البريد الإلكتروني') }}
                    </label>
                    <input wire:model="email" id="email" type="email" required autofocus autocomplete="email"
                        placeholder="email@example.com"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#4a6b5a] focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-gray-700 font-semibold">
                            {{ __('كلمة المرور') }}
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                                class="text-sm text-[#4a6b5a] hover:text-[#d4b896] transition-colors duration-300">
                                {{ __('نسيت كلمة المرور؟') }}
                            </a>
                        @endif
                    </div>
                    <input wire:model="password" id="password" type="password" required autocomplete="current-password"
                        placeholder="{{ __('كلمة المرور') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#4a6b5a] focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox"
                        class="w-4 h-4 text-[#4a6b5a] border-gray-300 rounded focus:ring-[#4a6b5a] focus:ring-2" />
                    <label for="remember" class="mr-2 text-sm text-gray-700">
                        {{ __('تذكرني') }}
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" data-test="login-button"
                    class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{ __('تسجيل الدخول') }}
                </button>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 text-center">
                    <span class="text-gray-600">{{ __('ليس لديك حساب؟') }}</span>
                    <a href="{{ route('register') }}" wire:navigate
                        class="text-[#4a6b5a] hover:text-[#d4b896] font-semibold transition-colors duration-300 mr-1">
                        {{ __('إنشاء حساب') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="/"
                class="inline-flex items-center gap-2 text-[#4a6b5a] hover:text-[#d4b896] transition-colors duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('العودة للرئيسية') }}
            </a>
        </div>
    </div>
</div>