<div class="bg-white rounded-3xl shadow-xl p-8 border-t-4 border-[#4a6b5a]">
    @if (session()->has('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if (!$submitted)
        <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">
                <svg class="w-8 h-8 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                قيّم تجربتك مع نظام التحقق
            </h3>
            <p class="text-gray-600">ساعدنا في تحسين دقة النظام من خلال تقييمك</p>
        </div>

        @if(!Auth::check())
            <div class="text-center bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <svg class="w-16 h-16 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h4 class="text-xl font-bold text-blue-700 mb-3">تسجيل الدخول مطلوب</h4>
                <p class="text-blue-600 mb-6">لتتمكن من إرسال تقييمك وملاحظاتك، يرجى تسجيل الدخول أولاً</p>
                <div class="space-x-4">
                    <button wire:click="redirectToLogin" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        تسجيل الدخول
                    </button>
                    <button wire:click="redirectToRegister" 
                            class="bg-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        إنشاء حساب جديد
                    </button>
                </div>
            </div>
        @else
            <div class="text-center">
                <p class="text-[#4a6b5a] text-lg mb-6 font-medium">
                    أنت مسجل دخول! يمكنك الآن تقييم نتيجة التحقق
                </p>
                
                <a href="{{ route('review') }}" 
                   class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white px-10 py-4 rounded-xl text-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    ابدأ التقييم
                </a>
            </div>
        @endif
    @else
        <div class="text-center" x-data x-init="setTimeout(() => $el.style.opacity = '1', 100)" style="opacity: 0; transition: opacity 0.5s;">
            <div class="bg-green-50 border border-green-200 rounded-2xl p-8">
                <svg class="w-20 h-20 text-green-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-3xl font-bold text-green-700 mb-4">شكراً لك!</h3>
                <p class="text-green-600 text-lg mb-6">تم إرسال تقييمك بنجاح. ملاحظاتك تساعدنا في تحسين دقة النظام</p>
                
                <div class="space-y-4">
                    <button wire:click="submitAnother" 
                            class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                        إرسال تقييم آخر
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
