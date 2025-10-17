<div class="min-h-screen bg-gradient-to-br from-[#f8f6f0] via-[#faf9f5] to-[#f8f6f0] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-[#4a6b5a]/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-96 h-96 bg-[#d4b896]/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo Section -->
        <div class="text-center">
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" 
                    alt="شعار صحيح" 
                    class="w-20 h-20 object-contain float-animation">
            </div>
            <h2 class="text-4xl font-bold text-[#4a6b5a] mb-2">إعادة تعيين كلمة المرور</h2>
            <p class="text-lg text-gray-600">أدخل كلمة المرور الجديدة لحسابك في منصة صحيح</p>
        </div>

        <!-- Reset Password Form Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-10 border border-white/20 hover:shadow-[#4a6b5a]/10 transition-all duration-500">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#d4b896] via-[#4a6b5a] to-[#d4b896] rounded-t-3xl"></div>
            
            <div class="flex flex-col gap-6">
                <!-- Session Status -->
                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
                    <!-- Email Address -->
                    <div class="group">
                        <div class="flex items-center gap-3 mb-4 text-[#4a6b5a]">
                            <svg class="w-8 h-8 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-bold text-xl">البيانات الجديدة</span>
                        </div>
                        
                        <flux:input
                            wire:model="email"
                            :label="__('البريد الإلكتروني')"
                            type="email"
                            required
                            autocomplete="email"
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20 group-hover:border-[#4a6b5a]/50"
                        />
                    </div>

                    <!-- Password -->
                    <div class="group">
                        <flux:input
                            wire:model="password"
                            :label="__('كلمة المرور الجديدة')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('كلمة المرور')"
                            viewable
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20 group-hover:border-[#4a6b5a]/50"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div class="group">
                        <flux:input
                            wire:model="password_confirmation"
                            :label="__('تأكيد كلمة المرور')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('تأكيد كلمة المرور')"
                            viewable
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20 group-hover:border-[#4a6b5a]/50"
                        />
                    </div>

                    <!-- Reset Button -->
                    <div class="flex items-center justify-end">
                        <flux:button 
                            type="submit" 
                            variant="primary" 
                            class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#d4b896] text-white py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            {{ __('إعادة تعيين كلمة المرور') }}
                        </flux:button>
                    </div>
                </form>

                <div class="text-center pt-6 border-t border-gray-200">
                    <span class="text-gray-600">تذكرت كلمة المرور؟</span>
                    <flux:link 
                        :href="route('login')" 
                        wire:navigate 
                        class="text-[#4a6b5a] hover:text-[#d4b896] font-semibold transition-colors duration-300 mr-2"
                    >
                        العودة لتسجيل الدخول
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