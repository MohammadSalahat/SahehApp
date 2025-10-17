<div
    class="min-h-screen bg-gradient-to-br from-[#f8f6f0] via-[#faf9f5] to-[#f8f6f0] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-20 left-16 w-72 h-72 bg-[#d4b896]/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-16 w-96 h-96 bg-[#4a6b5a]/10 rounded-full blur-3xl"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo Section -->
        <div class="text-center">
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="شعار صحيح"
                    class="w-20 h-20 object-contain float-animation">
            </div>
            <h2 class="text-4xl font-bold text-[#4a6b5a] mb-2">نسيت كلمة المرور؟</h2>
            <p class="text-lg text-gray-600">لا تقلق، سنرسل لك رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني</p>
        </div>

        <!-- Forgot Password Form Card -->
        <div
            class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-white/20 hover:shadow-[#4a6b5a]/10 transition-all duration-500">
            <div
                class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#4a6b5a] via-[#d4b896] to-[#4a6b5a] rounded-t-3xl">
            </div>

            <div class="flex flex-col gap-6">
                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
                    <!-- Email Address -->
                    <div class="group">
                        <div class="flex items-center gap-3 mb-4 text-[#4a6b5a]">
                            <svg class="w-8 h-8 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-bold text-xl">أدخل بريدك الإلكتروني</span>
                        </div>

                        <flux:input wire:model="email" :label="__('البريد الإلكتروني')" type="email" required autofocus
                            placeholder="your.email@example.com"
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20 group-hover:border-[#4a6b5a]/50" />

                        <p class="text-sm text-gray-500 mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#d4b896]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            سنرسل لك رابط إعادة التعيين خلال دقائق
                        </p>
                    </div>

                    <!-- Send Reset Link Button -->
                    <flux:button variant="primary" type="submit"
                        class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#d4b896] text-white py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        {{ __('إرسال رابط إعادة التعيين') }}
                    </flux:button>
                </form>

                <div class="text-center pt-6 border-t border-gray-200">
                    <span class="text-gray-600">{{ __('أو، العودة إلى') }}</span>
                    <flux:link :href="route('login')" wire:navigate
                        class="text-[#4a6b5a] hover:text-[#d4b896] font-semibold transition-colors duration-300 mr-2">
                        {{ __('تسجيل الدخول') }}
                    </flux:link>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center">
            <p class="text-gray-500 text-sm">
                © 2025 منصة صحيح - مشروع جامعي لخدمة المجتمع السعودي
            </p>
        </div>
    </div>
</div>