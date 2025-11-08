<x-layouts.auth>
    <div
        class="min-h-screen bg-gradient-to-br from-[#f8f6f0] via-[#faf9f5] to-[#f8f6f0] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Decorations -->
        <div
            class="absolute top-20 left-1/2 transform -translate-x-1/2 w-72 h-72 bg-primborder-primary/10 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-20 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-[#d4b896]/10 rounded-full blur-3xl">
        </div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <!-- Logo Section -->
            <div class="text-center">
                <div class="flex items-center justify-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="شعار صحيح"
                        class="w-20 h-20 object-contain float-animation">
                </div>
                <h2 class="text-4xl font-bold text-primborder-primary mb-2">تأكيد كلمة المرور</h2>
                <p class="text-lg text-gray-600">هذه منطقة آمنة، يرجى تأكيد كلمة المرور للمتابعة</p>
            </div>

            <!-- Confirm Password Form Card -->
            <div
                class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-white/20 hover:shadow-primborder-primary/10 transition-all duration-500">
                <div
                    class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primborder-primary via-[#d4b896] to-primborder-primary rounded-t-3xl">
                </div>

                <div class="flex flex-col gap-6">
                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
                        @csrf

                        <!-- Password Field -->
                        <div class="group relative">
                            <div class="flex items-center gap-3 mb-4 text-primborder-primary">
                                <svg class="w-8 h-8 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span class="font-bold text-xl">أدخل كلمة المرور</span>
                            </div>

                            <flux:input name="password" :label="__('كلمة المرور')" type="password" required
                                autocomplete="current-password" :placeholder="__('كلمة المرور')" viewable
                                class="w-full rounded-xl focus:border-primary focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-primborder-primary/20 group-hover:border-primary/50" />
                        </div>

                        <!-- Confirm Button -->
                        <flux:button variant="primary" type="submit"
                            class="w-full bg-gradient-to-r from-primary to-primary/50 text-white py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center justify-center gap-3"
                            data-test="confirm-password-button">
                            <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('تأكيد') }}</span>
                        </flux:button>
                    </form>
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
</x-layouts.auth>