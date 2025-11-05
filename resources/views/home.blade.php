<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شعار صحيح - التحقق من الأخبار القانونية</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans pt-20 scroll-smooth">

    <x-home.navigation />

    <!-- Include Interactive Verification Loader -->
    <x-verification-loader />

    <!-- Hero Section -->
    <section class="bg-[#f8f6f0] py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#f8f6f0] to-[#faf9f5]"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-bold text-[#4a6b5a] mb-6 leading-tight">
                {{ __('hero-section.title') }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-700 mb-4 max-w-4xl mx-auto leading-relaxed">
                لا تدع الشائعة تسبقك! اكتشف الحقيقة مع صحيح - منصة التحقق
            </p>
            <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-4xl mx-auto leading-relaxed">
                من الأخبار القانونية المدعومة بالذكاء الاصطناعي
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <button
                    class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white px-10 py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                    <svg class="w-6 h-6 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    ابدأ التحقق الآن
                </button>
                <button
                    class="bg-white border-2 border-[#4a6b5a] text-[#4a6b5a] px-10 py-4 rounded-xl text-lg font-bold hover:bg-[#4a6b5a] hover:text-white hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    تعرف على المشروع
                </button>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-6">
                أحدث الأخبار للتحقق
            </h2>
            <p class="text-center text-gray-600 mb-16 text-lg">
                عينات من الأخبار القانونية التي يمكن التحقق من صحتها
            </p>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div
                    class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                    <div
                        class="w-full h-48 bg-gradient-to-r from-[#4a6b5a] to-[#d4b896] rounded-xl mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                        التحول الرقمي في القضاء السعودي
                    </h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        وزارة العدل تطلق منصات رقمية متقدمة ضمن أهداف رؤية السعودية 2030
                    </p>
                    <button
                        class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        تحقق من الخبر
                    </button>
                </div>

                <div
                    class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                    <div
                        class="w-full h-48 bg-gradient-to-r from-[#d4b896] to-[#4a6b5a] rounded-xl mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                        قانون حماية البيانات الشخصية
                    </h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        تطبيق القانون الجديد لحماية البيانات الشخصية في القطاعين العام والخاص
                    </p>
                    <button
                        class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        تحقق من الخبر
                    </button>
                </div>

                <div
                    class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                    <div
                        class="w-full h-48 bg-gradient-to-r from-[#4a6b5a] to-[#d4b896] rounded-xl mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                        نظام الإجراءات الجزائية المحدث
                    </h3>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        التعديلات الجديدة على نظام الإجراءات الجزائية وأثرها على النظام القضائي
                    </p>
                    <button
                        class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        تحقق من الخبر
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Verify Section -->
    <section id="verify" class="py-20 bg-[#f8f6f0]">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-6">
                تحقق من خبر
            </h2>
            <p class="text-center text-gray-600 mb-16 text-lg">
                أدخل نص الخبر الذي تريد التحقق من صحته وسنقوم بتحليله باستخدام الذكاء الاصطناعي
            </p>

            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#4a6b5a] to-[#d4b896]"></div>

                <form id="verification-form" action="{{ route('verify') }}" method="POST"
                    onsubmit="handleFormSubmit(event)">
                    @csrf
                    <div class="mb-6 flex items-center gap-3 text-[#4a6b5a]">
                        <svg class="w-8 h-8 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-bold text-xl">نص الخبر المراد التحقق منه</span>
                    </div>

                    @error('content')
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            {{ $message }}
                        </div>
                    @enderror

                    <textarea id="news-content" name="content" required minlength="20"
                        class="w-full h-48 p-6 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none resize-none text-lg leading-relaxed transition-all duration-300 focus:shadow-lg focus:ring-2 focus:ring-[#4a6b5a]/20"
                        placeholder="اكتب أو الصق نص الخبر هنا... مثال: أعلنت وزارة العدل السعودية عن إطلاق نظام جديد للمحاكمات الإلكترونية...">{{ old('content') }}</textarea>

                    <button type="submit" id="submit-btn"
                        class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white w-full mt-8 py-5 rounded-xl text-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        تحقق من الخبر
                    </button>
                </form>

                <script>
                    function handleFormSubmit(event) {
                        event.preventDefault();

                        const content = document.getElementById('news-content').value.trim();

                        // Validate content length
                        if (content.length < 20) {
                            alert('الرجاء إدخال نص لا يقل عن 20 حرفاً');
                            return false;
                        }

                        // Show the loader
                        if (window.VerificationLoader) {
                            window.VerificationLoader.show();
                        }

                        // Submit the form after showing loader
                        setTimeout(() => {
                            event.target.submit();
                        }, 300);
                    }
                </script>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-20 bg-white hidden">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-6">
                كيف <span class="text-[#4a6b5a]">يعمل</span> <span class="text-[#d4b896]">صحيح؟</span>
            </h2>
            <p class="text-center text-gray-600 mb-20 text-lg">
                عملية بسيطة وسريعة للتحقق من صحة الأخبار القانونية
            </p>

            <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div
                    class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-[#4a6b5a] shadow-lg">
                        <span class="text-[#4a6b5a] font-bold text-2xl">1</span>
                    </div>
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-[#4a6b5a] to-[#d4b896] rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">أدخل نص الخبر</h3>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        اكتب أو الصق نص الخبر القانوني الذي تريد التحقق من صحته في المربع المخصص
                    </p>
                </div>

                <div
                    class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-[#4a6b5a] shadow-lg">
                        <span class="text-[#4a6b5a] font-bold text-2xl">2</span>
                    </div>
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-[#4a6b5a] to-[#d4b896] rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">التحليل بالذكاء الاصطناعي</h3>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        يقوم نموذج AraBERT المتطور بتحليل النص ومقارنته بالمصادر القانونية الرسمية
                    </p>
                </div>

                <div
                    class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-[#4a6b5a] shadow-lg">
                        <span class="text-[#4a6b5a] font-bold text-2xl">3</span>
                    </div>
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-[#4a6b5a] to-[#d4b896] rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">النتيجة والمصادر</h3>
                    <p class="text-gray-700 leading-relaxed text-lg">
                        تحصل على النتيجة مع درجة الثقة والمصادر القانونية الرسمية التي تم الاستناد إليها
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Sources Section -->
    <section class="py-20 bg-[#f8f6f0] hidden">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-6">
                لماذا تثق بـ <span class="text-[#4a6b5a]">صحيح؟</span>
            </h2>
            <p class="text-center text-gray-600 mb-20 text-lg">
                نعتمد على أوثق المصادر القانونية الرسمية في المملكة العربية السعودية
            </p>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div
                    class="bg-white rounded-3xl p-8 shadow-lg text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="w-20 h-20 bg-[#4a6b5a]/10 rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-10 h-10 text-[#4a6b5a]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">وزارة العدل</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نستند إلى النصوص النظامية والقرارات الرسمية المنشورة عبر موقع وزارة العدل السعودية الرسمي
                    </p>
                </div>

                <div
                    class="bg-white rounded-3xl p-8 shadow-lg text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="w-20 h-20 bg-[#4a6b5a]/10 rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-10 h-10 text-[#4a6b5a]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">بوابة الأنظمة السعودية</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نربط نتائج التحقق مباشرة بالمواد النظامية الرسمية من بوابة الأنظمة الحكومية
                    </p>
                </div>

                <div
                    class="bg-white rounded-3xl p-8 shadow-lg text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="w-20 h-20 bg-[#4a6b5a]/10 rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-10 h-10 text-[#4a6b5a]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">وكالة الأنباء السعودية</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نحلل الأخبار من مصدرها الرسمي الموثوق (SPA) قبل إصدار أي حكم على صحتها
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div
                    class="bg-white rounded-3xl p-8 shadow-lg text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="w-20 h-20 bg-[#4a6b5a]/10 rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-10 h-10 text-[#4a6b5a]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM8 20H4v-4h4v4zm0-6H4v-4h4v4zm0-6H4V4h4v4zm6 12h-4v-4h4v4zm0-6h-4v-4h4v4zm0-6h-4V4h4v4zm6 12h-4v-4h4v4zm0-6h-4v-4h4v4zm0-6h-4V4h4v4z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">ذكاء اصطناعي عربي</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نستخدم نموذج AraBERT المتقدم المدرب على النصوص العربية القانونية بدقة عالية
                    </p>
                </div>

                <div
                    class="bg-white rounded-3xl p-8 shadow-lg text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                    <div
                        class="w-20 h-20 bg-[#4a6b5a]/10 rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                        <svg class="w-10 h-10 text-[#4a6b5a]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#4a6b5a] mb-4">ديوان المظالم</h3>
                    <p class="text-gray-700 leading-relaxed">
                        نستفيد من السوابق القضائية والأحكام الصادرة لتقييم الأخبار ذات الطابع القضائي
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-4 max-w-5xl text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-[#4a6b5a] mb-12">عن منصة صحيح</h2>

            <div class="bg-[#f8f6f0] rounded-3xl p-10 shadow-lg mb-8">
                <p class="text-xl text-gray-700 leading-relaxed mb-8">
                    "صحيح" هو مشروع جامعي رائد يهدف إلى تمكين المواطنين والمقيمين من التحقق من صحة الأخبار القانونية
                    باستخدام أحدث تقنيات الذكاء الاصطناعي ومعالجة اللغة الطبيعية العربية.
                </p>

                <p class="text-xl text-gray-700 leading-relaxed">
                    طُوِّر هذا المشروع بشغف وإتقان ضمن متطلبات التخرج لتقديم حل تقني مبتكر يعزز الشفافية والموثوقية
                    القانونية، ويساهم في مكافحة انتشار المعلومات المضللة في المجال القانوني، مما يخدم أهداف رؤية المملكة
                    2030 في التحول الرقمي وبناء مجتمع معرفي واع.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-[#f8f6f0]">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-6">تواصل معنا</h2>
            <p class="text-center text-gray-600 mb-16 text-lg">
                لديك سؤال أو اقتراح؟ نحن هنا للمساعدة
            </p>

            <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl p-10">
                <livewire:forms.contact-us-form />
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-24 bg-gradient-to-br from-[#4a6b5a] via-[#4a6b5a] to-[#d4b896] relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle, white 2px, transparent 2px); background-size: 40px 40px;">
            </div>
        </div>

        <div class="absolute top-20 left-10 w-72 h-72 bg-[#d4b896]/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-bold text-white mb-6 tracking-tight">إحصائيات المنصة</h2>
                <div class="flex items-center justify-center gap-2 mb-6">
                    <div class="w-12 h-1 bg-[#d4b896] rounded-full"></div>
                    <div class="w-3 h-3 bg-[#d4b896] rounded-full"></div>
                    <div class="w-12 h-1 bg-[#d4b896] rounded-full"></div>
                </div>
                <p class="text-white/80 text-xl max-w-3xl mx-auto leading-relaxed">أرقام حقيقية تعكس ثقة المستخدمين ودقة
                    التحليل في منصة صحيح</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6 lg:gap-8 max-w-7xl mx-auto">
                @livewire('statistics-card', ['value' => '620', 'label' => 'خبر تم التحقق منه'])
                @livewire('statistics-card', ['value' => '89', 'label' => 'دقة التحليل', 'isPercentage' => true])
                @livewire('statistics-card', ['value' => '1250', 'label' => 'مستخدم نشط'])
                @livewire('statistics-card', ['value' => '5', 'label' => 'مصادر قانونية رسمية'])
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-[#4a6b5a] to-[#3a5a4a] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('images/logo.png') }}" class="w-20 h-20 object-contain">
                        <div class="text-2xl font-bold">
                            <span class="text-white">منصة</span>
                            <span class="text-[#d4b896]"> صحيح</span>
                        </div>
                    </div>
                    <p class="text-white/80 leading-relaxed text-lg">
                        منصة التحقق من الأخبار القانونية المدعومة بالذكاء الاصطناعي. مشروع جامعي لخدمة المجتمع السعودي.
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-6">روابط سريعة</h3>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">الرئيسية</a>
                        </li>
                        <li><a href="#verify"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">تحقق
                                من خبر</a></li>
                        <li><a href="#sources"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">المصادر
                                القانونية</a></li>
                        <li><a href="#about"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">عن
                                المشروع</a></li>
                        <li><a href="#contact"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">تواصل
                                معنا</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-6">المصادر القانونية</h3>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">وزارة
                                العدل</a></li>
                        <li><a href="#"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">بوابة
                                الأنظمة</a></li>
                        <li><a href="#"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">وكالة
                                الأنباء السعودية</a></li>
                        <li><a href="#"
                                class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">ديوان
                                المظالم</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-bold mb-6">تواصل معنا</h3>
                    <ul class="space-y-4">
                        <li
                            class="flex items-center gap-3 text-white/80 hover:text-white transition-all duration-300 text-lg">
                            <svg class="w-6 h-6 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@saheh.sa
                        </li>
                        <li
                            class="flex items-center gap-3 text-white/80 hover:text-white transition-all duration-300 text-lg">
                            <svg class="w-6 h-6 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +966123456789
                        </li>
                        <li
                            class="flex items-center gap-3 text-white/80 hover:text-white transition-all duration-300 text-lg">
                            <svg class="w-6 h-6 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            الرياض، المملكة العربية السعودية
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/20 pt-8 text-center">
                <p class="text-white/80 flex items-center justify-center gap-3 text-lg">
                    <svg class="w-6 h-6 text-[#d4b896] pulse-soft" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                    © 2025 جميع الحقوق محفوظة لمنصة صحيح - مشروع جامعي بأيدٍ سعودية
                    <span class="text-green-400 text-2xl">💚</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop"
        class="fixed bottom-8 left-8 bg-[#4a6b5a] text-white p-4 rounded-full shadow-xl hover:bg-[#4a6b5a]/90 transition-all duration-300 opacity-0 pointer-events-none hover:scale-110 hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

</body>

</html>