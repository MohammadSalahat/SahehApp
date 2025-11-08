<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة التحقق بالذكاء الاصطناعي - منصة صحيح</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans">

    <x-home.navigation />

    <div class="container mx-auto px-4 py-20 max-w-6xl">
        <!-- Back Button -->
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 text-[#4a6b5a] hover:text-[#3a5a4a] font-bold mb-8 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            العودة للصفحة الرئيسية
        </a>

        <h1 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-4">
            نتيجة التحقق بالذكاء الاصطناعي
        </h1>
        <p class="text-center text-gray-600 mb-8">تحليل ذكي للنص باستخدام التشابه الدلالي والمعالجة اللغوية</p>

        <!-- Text Quality Analysis -->
        @if(isset($query_quality))
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">تحليل جودة النص المدخل</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $query_quality['word_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">عدد الكلمات</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ number_format(($query_quality['arabic_ratio'] ?? 0) * 100, 1) }}%</div>
                        <div class="text-sm text-gray-600">نسبة النص العربي</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $query_quality['legal_terms_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">المصطلحات القانونية</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">
                            {{ number_format(($query_quality['quality_score'] ?? 0) * 100, 1) }}%</div>
                        <div class="text-sm text-gray-600">درجة الجودة</div>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($error) && $error)
            <!-- ERROR: Show Error Message -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-yellow-500 mb-8">
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-8 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-3xl font-bold">خطأ في النظام</h2>
                    <p class="text-xl mt-2 opacity-90">{{ $error_message }}</p>
                    @if($error_details)
                        <p class="text-sm mt-4 opacity-75 font-mono">{{ $error_details }}</p>
                    @endif
                </div>
            </div>
        @elseif ($is_potentially_fake ?? false)
            <!-- POTENTIALLY FAKE: Show Warning with AI Analysis -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-red-500 mb-8">
                <!-- Alert Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-8 text-center">
                    <svg class="w-24 h-24 mx-auto mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h2 class="text-3xl font-bold">⚠️ تحذير: خبر محتمل الكذب</h2>
                    <p class="text-xl mt-2 opacity-90">اكتشف الذكاء الاصطناعي تشابهاً مع أخبار مزيفة معروفة</p>
                </div>

                <!-- AI Recommendation -->
                <div class="p-8 bg-red-50 border-b border-red-100">
                    <div class="max-w-3xl mx-auto text-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">توصية النظام الذكي</h3>
                        <p class="text-lg text-gray-700 leading-relaxed">{{ $recommendation }}</p>
                    </div>
                </div>

                <!-- Similarity Score -->
                @if(isset($best_match))
                    <div class="p-8 bg-white border-b border-gray-100">
                        <div class="max-w-3xl mx-auto">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">درجة التشابه الدلالي</h3>

                            <div class="relative mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">منخفض</span>
                                    <span class="text-3xl font-bold text-red-600">
                                        {{ number_format($best_match['similarity_score'] * 100, 1) }}%
                                    </span>
                                    <span class="text-sm text-gray-600">عالي</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 via-yellow-500 to-red-500 h-6 rounded-full transition-all duration-1000 ease-out"
                                        style="width: {{ $best_match['similarity_score'] * 100 }}%">
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold">
                                        مستوى التشابه: {{ $best_match['similarity_level_arabic'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detailed Analysis Metrics -->
                            @if(isset($best_match['detailed_metrics']))
                                <div class="grid md:grid-cols-3 gap-4 mb-6">
                                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                                        <h4 class="font-bold text-blue-800 mb-2">التشابه الدلالي</h4>
                                        <p class="text-2xl font-bold text-blue-600">
                                            {{ number_format(($best_match['detailed_metrics']['semantic_similarity'] ?? 0) * 100, 1) }}%
                                        </p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg text-center">
                                        <h4 class="font-bold text-green-800 mb-2">تداخل المصطلحات</h4>
                                        <p class="text-2xl font-bold text-green-600">
                                            {{ number_format(($best_match['detailed_metrics']['lexical_overlap'] ?? 0) * 100, 1) }}%
                                        </p>
                                    </div>
                                    <div class="bg-purple-50 p-4 rounded-lg text-center">
                                        <h4 class="font-bold text-purple-800 mb-2">المصطلحات القانونية</h4>
                                        <p class="text-2xl font-bold text-purple-600">
                                            {{ number_format(($best_match['detailed_metrics']['legal_terms_overlap'] ?? 0) * 100, 1) }}%
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Common Legal Entities -->
                            @if(isset($best_match['common_legal_entities']) && count($best_match['common_legal_entities']) > 0)
                                <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                                    <h4 class="font-bold text-yellow-800 mb-3">المصطلحات القانونية المشتركة:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($best_match['common_legal_entities'] as $entityType => $entities)
                                            @foreach($entities as $entity)
                                                <span
                                                    class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm">{{ $entity }}</span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Analysis Method -->
                            @if(isset($best_match['analysis_method']))
                                <div class="bg-indigo-50 p-4 rounded-lg mb-6">
                                    <h4 class="font-bold text-indigo-800 mb-2">طريقة التحليل:</h4>
                                    <p class="text-indigo-700">
                                        @if($best_match['analysis_method'] === 'semantic_similarity')
                                            التشابه الدلالي باستخدام نماذج Sentence Transformers
                                        @elseif($best_match['analysis_method'] === 'tfidf_cosine')
                                            تحليل TF-IDF والتشابه التجميعي
                                        @else
                                            {{ $best_match['analysis_method'] }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            <!-- Best Match Details -->
                            <div class="bg-gray-50 rounded-2xl p-6 border-r-4 border-red-500">
                                <h4 class="text-lg font-bold text-gray-800 mb-3">الخبر المزيف المشابه:</h4>
                                <p class="text-xl font-bold text-gray-900 mb-3">{{ $best_match['title'] }}</p>
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    {{ Str::limit($best_match['content'], 300) }}
                                </p>
                                <div class="flex flex-wrap gap-3 items-center">
                                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
                                        المصدر: {{ $best_match['origin_dataset'] }}
                                    </span>
                                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
                                        ثقة الكشف: {{ number_format($best_match['confidence_score'] * 100, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Matches -->
                @if(count($similar_news) > 1)
                    <div class="p-8 bg-gray-50">
                        <div class="max-w-3xl mx-auto">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">أخبار مزيفة مشابهة أخرى
                                ({{ count($similar_news) - 1 }})</h3>
                            <div class="space-y-4">
                                @foreach(array_slice($similar_news, 1) as $news)
                                    <div class="bg-white rounded-xl p-5 border-r-4 border-orange-400">
                                        <div class="flex justify-between items-start mb-2">
                                            <p class="font-bold text-gray-900 flex-1">{{ $news['title'] }}</p>
                                            <span
                                                class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-bold mr-3 whitespace-nowrap">
                                                {{ number_format($news['similarity_score'] * 100, 1) }}%
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($news['content'], 150) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        @elseif($found)
            <!-- FOUND BUT LOW SIMILARITY: Show Caution -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-orange-500 mb-8">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-8 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-3xl font-bold">تنبيه: تشابه متوسط</h2>
                    <p class="text-xl mt-2 opacity-90">{{ $recommendation }}</p>
                </div>

                @if(!empty($similar_news))
                    <div class="p-8">
                        <div class="max-w-3xl mx-auto">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">أخبار مشابهة تم العثور عليها
                                ({{ count($similar_news) }})</h3>
                            <div class="space-y-4">
                                @foreach($similar_news as $news)
                                    <div class="bg-gray-50 rounded-xl p-5 border-r-4 border-orange-300">
                                        <div class="flex justify-between items-start mb-2">
                                            <p class="font-bold text-gray-900 flex-1">{{ $news['title'] }}</p>
                                            <span
                                                class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-bold mr-3">
                                                {{ number_format($news['similarity_score'] * 100, 1) }}%
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($news['content'], 150) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        @else
            <!-- NOT FOUND: Show Safe Message -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-t-8 border-green-500 mb-8">
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-8 text-center">
                    <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-3xl font-bold">✅ لم يتم العثور على تشابه</h2>
                    <p class="text-xl mt-2 opacity-90">النص لا يشبه الأخبار المزيفة المعروفة</p>
                </div>

                <div class="p-8">
                    <div class="max-w-3xl mx-auto text-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">نتيجة التحليل الذكي</h3>
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            {{ $recommendation ?? 'النص الذي أدخلته لا يشبه الأخبار المزيفة الموجودة في قاعدة بياناتنا.' }}
                        </p>
                        <div class="bg-blue-50 border-r-4 border-blue-500 p-6 rounded-xl text-right">
                            <p class="text-blue-900 font-bold mb-2">💡 ملاحظة هامة:</p>
                            <p class="text-blue-800">
                                عدم وجود تشابه لا يعني بالضرورة أن الخبر صحيح. يُنصح دائماً بالتحقق من المصادر الرسمية
                                الموثوقة مثل:
                            </p>
                            <ul class="mt-3 space-y-1 text-blue-800">
                                <li>• وكالة الأنباء السعودية (واس)</li>
                                <li>• الموقع الرسمي لوزارة العدل</li>
                                <li>• المواقع الحكومية الرسمية</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Your Submitted Text -->
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-[#4a6b5a] to-[#3a5a4a] text-white p-6">
                <h3 class="text-2xl font-bold">النص الذي تم تحليله</h3>
            </div>
            <div class="p-8">
                <p class="text-gray-800 leading-relaxed text-lg whitespace-pre-wrap">{{ $search_content }}</p>

                @if(isset($preprocessed_text) && $preprocessed_text)
                    <div class="mt-6 bg-gray-50 rounded-xl p-5 border-r-4 border-purple-400">
                        <p class="text-sm font-bold text-gray-700 mb-2">النص بعد المعالجة اللغوية (NLP):</p>
                        <p class="text-gray-600 font-mono text-sm">{{ $preprocessed_text }}</p>
                    </div>
                @endif

                @if(isset($query_quality))
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(isset($query_quality['length']))
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-[#4a6b5a]">{{ $query_quality['length'] }}</p>
                                <p class="text-sm text-gray-600">عدد الأحرف</p>
                            </div>
                        @endif
                        @if(isset($query_quality['word_count']))
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-[#4a6b5a]">{{ $query_quality['word_count'] }}</p>
                                <p class="text-sm text-gray-600">عدد الكلمات</p>
                            </div>
                        @endif
                        @if(isset($query_quality['legal_keyword_count']))
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-[#4a6b5a]">{{ $query_quality['legal_keyword_count'] }}</p>
                                <p class="text-sm text-gray-600">مصطلحات قانونية</p>
                            </div>
                        @endif
                        @if(isset($query_quality['is_legal_related']))
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-2xl font-bold text-[#4a6b5a]">
                                    {{ $query_quality['is_legal_related'] ? '✓' : '✗' }}
                                </p>
                                <p class="text-sm text-gray-600">نص قانوني</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-center text-[#4a6b5a] mb-8">قيّم دقة النتيجة</h2>
            <livewire:forms.verification-feedback-form />
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-4 mt-12">
            <a href="{{ route('home') }}"
                class="inline-block bg-gradient-to-r from-[#4a6b5a] to-[#3a5a4a] text-white px-10 py-4 rounded-full font-bold text-lg hover:shadow-xl transform hover:scale-105 transition-all">
                تحقق من خبر آخر
            </a>

            <p class="text-gray-500 text-sm">
                تم التحليل باستخدام الذكاء الاصطناعي والمعالجة اللغوية الطبيعية (NLP) للغة العربية
            </p>
        </div>
    </div>

</body>

</html>