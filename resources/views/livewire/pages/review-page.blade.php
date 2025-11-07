<div class="min-h-screen bg-gradient-to-br from-[#f8f6f0] to-[#faf9f5] py-12">
    <div class="container mx-auto px-4 max-w-4xl">

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-[#4a6b5a] mb-4">
                تقييم نتيجة التحقق
            </h1>
            <p class="text-xl text-gray-600">
                ساعدنا في تحسين دقة النظام من خلال تقييمك
            </p>
        </div>

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
            <div class="bg-white rounded-3xl shadow-2xl p-10 border-t-4 border-[#4a6b5a]">

                <form wire:submit.prevent="submit" class="space-y-8">
                    <!-- Rating Section -->
                    <div class="text-center">
                        <label class="block text-gray-700 font-bold mb-6 text-xl">
                            كيف تقيم دقة نتيجة التحقق؟
                        </label>
                        <div class="flex justify-center items-center gap-3 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                    class="text-5xl transition-all duration-200 hover:scale-125 focus:outline-none transform
                                                       {{ $rating >= $i ? 'text-yellow-400 drop-shadow-lg' : 'text-gray-300' }}">
                                    ⭐
                                </button>
                            @endfor
                        </div>
                        <div class="text-center">
                            <span class="inline-block bg-[#4a6b5a] text-white px-6 py-2 rounded-full text-lg font-medium">
                                {{ $rating == 1 ? 'ضعيف جداً' : ($rating == 2 ? 'ضعيف' : ($rating == 3 ? 'متوسط' : ($rating == 4 ? 'جيد' : 'ممتاز'))) }}
                            </span>
                        </div>
                        @error('rating')
                            <p class="mt-3 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message Section -->
                    <div>
                        <label for="message" class="block text-right text-gray-700 font-bold mb-4 text-lg">
                            ملاحظات إضافية (اختياري)
                        </label>
                        <textarea wire:model="message" id="message" rows="5"
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none resize-none text-lg transition-all duration-300 focus:ring-2 focus:ring-[#4a6b5a]/20 @error('message') border-red-500 @enderror"
                            placeholder="شاركنا ملاحظاتك حول دقة النتيجة أو اقتراحات للتحسين..."></textarea>
                        @error('message')
                            <p class="mt-2 text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center space-y-4">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                            class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white px-12 py-4 rounded-xl text-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span wire:loading.remove wire:target="submit">إرسال التقييم</span>
                            <span wire:loading wire:target="submit">جارٍ الإرسال...</span>
                        </button>

                        <div class="text-sm text-gray-500">
                            تقييمك يساعدنا في تطوير وتحسين دقة نظام التحقق
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Success State -->
            <div class="text-center" x-data x-init="setTimeout(() => $el.style.opacity = '1', 100)"
                style="opacity: 0; transition: opacity 0.5s;">
                <div class="bg-white rounded-3xl shadow-2xl p-12 border-t-4 border-green-500">
                    <svg class="w-24 h-24 text-green-500 mx-auto mb-8" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-4xl font-bold text-green-700 mb-6">شكراً لك!</h2>
                    <p class="text-green-600 text-xl mb-8 leading-relaxed">
                        تم إرسال تقييمك بنجاح. ملاحظاتك تساعدنا في تحسين دقة نظام التحقق
                    </p>

                    <div class="space-y-4">
                        <a href="{{ route('home') }}"
                            class="bg-[#4a6b5a] text-white px-8 py-3 rounded-lg font-medium hover:bg-[#3a5a4a] transition-colors inline-block">
                            العودة للصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>