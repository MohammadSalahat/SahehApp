<!-- Interactive AI Verification Loader -->
<div id="verification-loader" class="fixed inset-0 bg-gradient-to-br from-[#f8f6f0] to-[#faf9f5] z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="max-w-2xl w-full">

            <!-- Logo Animation -->
            <div class="text-center mb-12">
                <div class="inline-block relative">
                    <!-- Animated rings -->
                    <div class="absolute inset-0 -m-8">
                        <div class="w-32 h-32 border-4 border-[#4a6b5a] opacity-20 rounded-full animate-ping"></div>
                    </div>
                    <div class="absolute inset-0 -m-4">
                        <div class="w-24 h-24 border-4 border-[#d4b896] opacity-30 rounded-full animate-pulse"></div>
                    </div>

                    <!-- Logo -->
                    <div
                        class="relative w-20 h-20 mx-auto bg-gradient-to-br from-[#4a6b5a] to-[#5a7a6a] rounded-2xl flex items-center justify-center shadow-xl">
                        <svg class="w-12 h-12 text-white animate-pulse" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#4a6b5a] mb-8">
                جارٍ التحليل الذكي للنص
            </h2>

            <!-- Progress Steps -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="space-y-6">
                    <!-- Step 1 -->
                    <div class="flex items-start gap-4 verification-step opacity-0" data-step="1">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-[#4a6b5a] to-[#5a7a6a] rounded-full flex items-center justify-center text-white font-bold step-icon">
                            <span class="step-number">1</span>
                            <svg class="w-6 h-6 hidden step-check" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-[#4a6b5a] mb-1">معالجة النص العربي</h3>
                            <p class="text-gray-600 text-sm">تنظيف وتجهيز النص للتحليل اللغوي...</p>
                            <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r bg-primary rounded-full step-progress w-0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start gap-4 verification-step opacity-0" data-step="2">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold step-icon">
                            <span class="step-number">2</span>
                            <svg class="w-6 h-6 hidden step-check" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-400 mb-1">استخراج المصطلحات القانونية</h3>
                            <p class="text-gray-400 text-sm">تحديد المصطلحات القانونية والكلمات المفتاحية...</p>
                            <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r bg-primary rounded-full step-progress w-0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start gap-4 verification-step opacity-0" data-step="3">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold step-icon">
                            <span class="step-number">3</span>
                            <svg class="w-6 h-6 hidden step-check" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-400 mb-1">تحليل التشابه الدلالي بالذكاء الاصطناعي
                            </h3>
                            <p class="text-gray-400 text-sm">مقارنة النص مع قاعدة بيانات الأخبار المزيفة...</p>
                            <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r bg-primary rounded-full step-progress w-0"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-start gap-4 verification-step opacity-0" data-step="4">
                        <div
                            class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold step-icon">
                            <span class="step-number">4</span>
                            <svg class="w-6 h-6 hidden step-check" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-400 mb-1">حساب درجة الثقة والتوصيات</h3>
                            <p class="text-gray-400 text-sm">تحديد مستوى التشابه وإعداد التقرير النهائي...</p>
                            <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r bg-primary rounded-full step-progress w-0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rotating Messages -->
            <div class="text-center">
                <div
                    class="bg-gradient-to-r from-[#4a6b5a]/10 to-[#d4b896]/10 rounded-xl p-6 border-r-4 border-[#4a6b5a]">
                    <p id="loading-message" class="text-[#4a6b5a] text-lg font-medium leading-relaxed">
                        يستخدم النظام خوارزميات الذكاء الاصطناعي لفهم المعنى الدلالي للنص...
                    </p>
                </div>

                <!-- Fun Facts Counter -->
                <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                    <svg class="w-5 h-5 text-[#d4b896]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>الوقت المتوقع: <span id="estimated-time">3-5</span> ثوانٍ</span>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes progressBar {
        from {
            width: 0%;
        }

        to {
            width: 100%;
        }
    }

    .verification-step {
        animation: slideInUp 0.5s ease-out forwards;
    }

    .step-progress {
        transition: width 1.5s ease-in-out;
    }

    .pulse-soft {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>

<script>
    // Verification Loader Controller
    const VerificationLoader = {
        messages: [
            'يستخدم النظام خوارزميات الذكاء الاصطناعي لفهم المعنى الدلالي للنص...',
            'جارٍ تحليل النص باستخدام معالجة اللغة الطبيعية المتقدمة...',
            'المقارنة مع أكثر من 170+ خبر مزيف في قاعدة البيانات...',
            'استخدام نماذج الذكاء الاصطناعي المدربة على الأخبار القانونية السعودية...',
            'حساب درجة التشابه الدلالي بدقة عالية...',
            'تحليل المصطلحات القانونية الخاصة بالمملكة...',
            'التحقق من المصادر الرسمية والموثوقة...'
        ],
        currentStep: 0,
        messageInterval: null,
        startTime: null,

        show() {
            const loader = document.getElementById('verification-loader');
            if (loader) {
                loader.classList.remove('hidden');
                this.startTime = Date.now();
                this.startAnimation();
                this.rotateMessages();
            }
        },

        hide() {
            const loader = document.getElementById('verification-loader');
            if (loader) {
                loader.classList.add('hidden');
                clearInterval(this.messageInterval);
                this.currentStep = 0;
            }
        },

        startAnimation() {
            const steps = document.querySelectorAll('.verification-step');

            steps.forEach((step, index) => {
                setTimeout(() => {
                    // Show step
                    step.style.animationDelay = `${index * 0.2}s`;

                    // Activate step
                    setTimeout(() => {
                        const icon = step.querySelector('.step-icon');
                        const title = step.querySelector('h3');
                        const description = step.querySelector('p');
                        const progress = step.querySelector('.step-progress');
                        const stepNumber = step.querySelector('.step-number');
                        const stepCheck = step.querySelector('.step-check');

                        // Activate colors
                        icon.classList.remove('bg-gray-200');
                        icon.classList.add('bg-gradient-to-br', 'from-[#4a6b5a]', 'to-[#5a7a6a]');
                        icon.classList.remove('text-gray-400');
                        icon.classList.add('text-white');

                        title.classList.remove('text-gray-400');
                        title.classList.add('text-[#4a6b5a]');

                        description.classList.remove('text-gray-400');
                        description.classList.add('text-gray-600');

                        // Animate progress bar
                        setTimeout(() => {
                            progress.style.width = '100%';
                        }, 100);

                        // Mark as complete after progress
                        setTimeout(() => {
                            if (stepNumber && stepCheck) {
                                stepNumber.classList.add('hidden');
                                stepCheck.classList.remove('hidden');
                            }
                        }, 1600);

                    }, 300);
                }, index * 1800); // Stagger each step by 1.8 seconds
            });
        },

        rotateMessages() {
            let messageIndex = 0;
            const messageElement = document.getElementById('loading-message');

            this.messageInterval = setInterval(() => {
                messageElement.style.opacity = '0';

                setTimeout(() => {
                    messageIndex = (messageIndex + 1) % this.messages.length;
                    messageElement.textContent = this.messages[messageIndex];
                    messageElement.style.opacity = '1';
                }, 300);

            }, 3000); // Change message every 3 seconds
        }
    };

    // Make it globally available
    window.VerificationLoader = VerificationLoader;
</script>