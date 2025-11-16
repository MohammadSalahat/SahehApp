<x-layouts.main :title="__('verification.page_title')">
    <div class="container px-4 py-12 mx-auto max-w-7xl" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <!-- Back Button -->
        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 px-4 py-2 mb-8 font-semibold transition-all border rounded-lg text-primary dark:text-primary-300 hover:bg-primary-100/50 dark:hover:bg-primary/10 border-primary-200 dark:border-primary-400/30 hover:border-primary dark:hover:border-primary-300">
            <svg class="w-5 h-5 {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('verification.back_to_home') }}
        </a>

        <!-- Page Header -->
        <div class="mb-12 text-center">
            <h1 class="mb-3 text-4xl font-bold md:text-5xl text-zinc-900 dark:text-zinc-50">
                {{ __('verification.main_title') }}
            </h1>
            <p class="text-lg text-zinc-600 dark:text-zinc-400">{{ __('verification.subtitle') }}</p>
        </div>

        <div class="flex flex-row items-start gap-12">
            <div id="main-content" class="basis-4/6">
                @if (isset($error) && $error)
                <!-- ERROR: Show Error Message -->
                <div class="mb-8 overflow-hidden border-t-4 shadow-xl rounded-2xl border-amber-500 bg-white dark:bg-zinc-900">
                    <div class="p-10 text-center text-white bg-gradient-to-br from-amber-500 to-amber-600">
                        <svg class="w-24 h-24 mx-auto mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="mb-2 text-4xl font-bold">{{ __('verification.error_title') }}</h2>
                        <p class="text-xl opacity-90">{{ $error_message }}</p>
                        @if($error_details)
                            <p class="mt-4 text-sm font-mono opacity-75">{{ $error_details }}</p>
                        @endif
                    </div>
                </div>
                @elseif ($is_potentially_fake ?? false)
                    <!-- POTENTIALLY FAKE: Show Warning with AI Analysis -->
                    <div class="mb-8 overflow-hidden border-t-4 shadow-xl rounded-2xl border-red-500 bg-white dark:bg-zinc-900">
                        <!-- Alert Header -->
                        <div class="p-10 text-center text-white bg-gradient-to-br from-red-500 via-red-600 to-red-700">
                            <div class="relative inline-block mb-4">
                                <svg class="w-28 h-28 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="absolute inset-0 rounded-full opacity-20 animate-ping bg-red-300"></div>
                            </div>
                            <h2 class="mb-3 text-4xl font-bold">{{ __('verification.potentially_fake_title') }}</h2>
                            <p class="text-xl opacity-95">{{ __('verification.potentially_fake_subtitle') }}</p>
                        </div>

                        <!-- AI Recommendation -->
                        <div class="p-8 border-b bg-red-50 dark:bg-red-950/20 border-red-100 dark:border-red-900/50">
                            <div class="max-w-4xl mx-auto text-center">
                                <div
                                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 font-semibold rounded-full bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    {{ __('verification.ai_recommendation_title') }}
                                </div>
                                <p class="text-lg leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $recommendation ?? __('verification.safe_default_message') }}</p>
                            </div>
                        </div>

                        <!-- ChatGPT Source Verification Results -->
                        @if(isset($used_chatgpt_fallback) && $used_chatgpt_fallback && isset($chatgpt_result))
                            <div class="p-8 border-b bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800">
                                <div class="max-w-4xl mx-auto">
                                    <!-- Section Header -->
                                    <div class="flex items-center justify-center gap-3 mb-6">
                                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">
                                            @if($detected_language === 'ar')
                                                التحقق من المصادر الموثوقة
                                            @else
                                                Trusted Sources Verification
                                            @endif
                                        </h3>
                                    </div>

                                    @php
                                        $sourceStatus = $chatgpt_result['source_verification_status'] ?? [];
                                        $foundInSources = $sourceStatus['found_in_sources'] ?? false;
                                        $sourcesSearched = $sourceStatus['sources_searched'] ?? 0;
                                        $matchingSources = $sourceStatus['matching_sources'] ?? [];
                                        $highestSimilarity = $sourceStatus['highest_similarity'] ?? 0;
                                        $confidenceScore = $chatgpt_result['confidence_score'] ?? 0;
                                        $analysis = $chatgpt_result['analysis'][$detected_language] ?? $chatgpt_result['analysis']['ar'] ?? '';
                                    @endphp

                                    @if($foundInSources)
                                        <!-- FOUND IN SOURCES: Positive Result -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-950/30 dark:to-green-950/30 border-emerald-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-emerald-800 dark:text-emerald-300">
                                                        @if($detected_language === 'ar')
                                                            تم العثور على محتوى مشابه في المصادر الموثوقة
                                                        @else
                                                            Similar Content Found in Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-emerald-700 dark:text-emerald-400">
                                                        @if($detected_language === 'ar')
                                                            تم التحقق من {{ $sourcesSearched }} مصدر موثوق ووجدنا تطابقاً دلالياً مع المصادر التالية:
                                                        @else
                                                            Verified against {{ $sourcesSearched }} trusted sources and found semantic match with:
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Matching Sources -->
                                            <div class="mb-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($matchingSources as $source)
                                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-800 bg-emerald-200 rounded-full dark:bg-emerald-900 dark:text-emerald-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            {{ $source['source_name'] ?? $source }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Confidence Score -->
                                            <div class="p-4 mb-4 rounded-lg bg-white/70 dark:bg-zinc-800/50">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                        @if($detected_language === 'ar')
                                                            درجة الثقة في التطابق
                                                        @else
                                                            Match Confidence Score
                                                        @endif
                                                    </span>
                                                    <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                                        {{ number_format($highestSimilarity * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="relative overflow-hidden rounded-full h-3 bg-zinc-200 dark:bg-zinc-700">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-600"
                                                         style="width: {{ $highestSimilarity * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- NOT FOUND IN SOURCES: Warning Result -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-950/30 dark:to-orange-950/30 border-red-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-red-100 dark:bg-red-900/50">
                                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-red-800 dark:text-red-300">
                                                        @if($detected_language === 'ar')
                                                            لم يتم العثور على هذا الخبر في المصادر الموثوقة
                                                        @else
                                                            News Not Found in Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-red-700 dark:text-red-400">
                                                        @if($detected_language === 'ar')
                                                            تم البحث في {{ $sourcesSearched }} مصدر موثوق ولم يتم العثور على هذا الخبر في أي منها.
                                                        @else
                                                            Searched {{ $sourcesSearched }} trusted sources - this news was not found in any of them.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Sources Checked -->
                                            @if(isset($chatgpt_result['sources_checked']) && count($chatgpt_result['sources_checked']) > 0)
                                                <div class="mb-4">
                                                    <p class="mb-2 text-sm font-bold text-red-800 dark:text-red-300">
                                                        @if($detected_language === 'ar')
                                                            المصادر التي تم فحصها:
                                                        @else
                                                            Sources Checked:
                                                        @endif
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($chatgpt_result['sources_checked'] as $source)
                                                            <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-red-800 bg-red-200 rounded-full dark:bg-red-900 dark:text-red-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                {{ $source }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Confidence Score -->
                                            <div class="p-4 mb-4 rounded-lg bg-white/70 dark:bg-zinc-800/50">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                        @if($detected_language === 'ar')
                                                            درجة الثقة (احتمالية كون الخبر مزيف)
                                                        @else
                                                            Confidence Score (Likelihood of Being Fake)
                                                        @endif
                                                    </span>
                                                    <span class="text-3xl font-bold text-red-600 dark:text-red-400">
                                                        {{ number_format($confidenceScore * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="relative overflow-hidden rounded-full h-3 bg-zinc-200 dark:bg-zinc-700">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-red-600"
                                                         style="width: {{ $confidenceScore * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- AI Analysis -->
                                    @if($analysis)
                                        <div class="p-6 border rounded-xl bg-purple-50 dark:bg-purple-950/20 border-purple-200 dark:border-purple-900/50">
                                            <h4 class="flex items-center gap-2 mb-3 text-lg font-bold text-purple-800 dark:text-purple-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                @if($detected_language === 'ar')
                                                    التحليل الذكي المفصل
                                                @else
                                                    Detailed AI Analysis
                                                @endif
                                            </h4>
                                            <p class="text-purple-900 dark:text-purple-200 leading-relaxed whitespace-pre-wrap">{{ $analysis }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Similarity Score -->
                        @if(isset($best_match))
                            <div class="p-8 border-b bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800">
                                <div class="max-w-4xl mx-auto">
                                    <h3 class="mb-6 text-2xl font-bold text-center text-zinc-900 dark:text-zinc-50">
                                        {{ __('verification.similarity_title') }}
                                    </h3>

                                    <div
                                        class="p-6 mb-6 border rounded-2xl bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-950/20 dark:to-orange-950/20 border-red-200 dark:border-red-900/50">
                                        <div class="flex items-center justify-between mb-4">
                                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                                {{ __('verification.similarity_low') }}
                                            </span>
                                            <div class="relative">
                                                <span class="text-5xl font-bold text-red-600 dark:text-red-400">
                                                    {{ number_format($best_match['similarity_score'] * 100, 1) }}%
                                                </span>
                                                <div class="absolute -inset-2 rounded-full opacity-20 animate-ping bg-red-400"></div>
                                            </div>
                                            <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                                {{ __('verification.similarity_high') }}
                                            </span>
                                        </div>

                                        <div
                                            class="relative overflow-hidden rounded-full h-4 bg-zinc-200 dark:bg-zinc-700 rtl:rotate-180">
                                            <div class="absolute inset-0 bg-gradient-to-r  from-green-500 via-yellow-500 to-red-500">
                                            </div>
                                            <div class="absolute inset-0 rounded-full bg-zinc-200 dark:bg-zinc-700"
                                                style="clip-path: inset(0 0 0 {{ $best_match['similarity_score'] * 100 }}%);">
                                            </div>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <span
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-red-800 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                {{ __('verification.similarity_level') }}: {{ $best_match['similarity_level_arabic'] ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Detailed Analysis Metrics -->
                                    @if(isset($best_match['detailed_metrics']))
                                        <div class="grid gap-4 mb-6 md:grid-cols-3">
                                            <div
                                                class="p-5 text-center transition-all border rounded-xl bg-primary-100/50 dark:bg-primary/20 border-primary-200 dark:border-primary/30 hover:shadow-lg">
                                                <h4 class="mb-2 text-sm font-bold text-primary dark:text-primary-300">
                                                    {{ __('verification.semantic_similarity') }}
                                                </h4>
                                                <p class="text-3xl font-bold text-primary dark:text-primary-200">
                                                    {{ number_format(($best_match['detailed_metrics']['semantic_similarity'] ?? 0) * 100, 1) }}%
                                                </p>
                                            </div>
                                            <div
                                                class="p-5 text-center transition-all border rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-900/50 hover:shadow-lg">
                                                <h4 class="mb-2 text-sm font-bold text-emerald-800 dark:text-emerald-300">
                                                    {{ __('verification.lexical_overlap') }}
                                                </h4>
                                                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                                    {{ number_format(($best_match['detailed_metrics']['lexical_overlap'] ?? 0) * 100, 1) }}%
                                                </p>
                                            </div>
                                            <div
                                                class="p-5 text-center transition-all border rounded-xl bg-secondary/10 dark:bg-secondary/20 border-secondary/30 dark:border-secondary/40 hover:shadow-lg">
                                                <h4 class="mb-2 text-sm font-bold text-secondary dark:text-secondary/90">
                                                    {{ __('verification.legal_terms_overlap') }}
                                                </h4>
                                                <p class="text-3xl font-bold text-secondary dark:text-secondary/80">
                                                    {{ number_format(($best_match['detailed_metrics']['legal_terms_overlap'] ?? 0) * 100, 1) }}%
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Common Legal Entities -->
                                    @if(isset($best_match['common_legal_entities']) && count($best_match['common_legal_entities']) > 0)
                                        <div
                                            class="p-5 mb-6 border rounded-xl bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-900/50">
                                            <h4 class="flex items-center gap-2 mb-3 font-bold text-amber-800 dark:text-amber-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                {{ __('verification.common_legal_entities') }}
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($best_match['common_legal_entities'] as $entityType => $entities)
                                                    @foreach($entities as $entity)
                                                        <span
                                                            class="px-3 py-1 text-sm font-medium text-amber-800 bg-amber-200 dark:bg-amber-900 dark:text-amber-200 rounded-full">
                                                            {{ $entity }}
                                                        </span>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Analysis Method -->
                                    @if(isset($best_match['analysis_method']))
                                        <div
                                            class="p-5 mb-6 border rounded-xl bg-secondary/10 dark:bg-secondary/20 border-secondary/30 dark:border-secondary/40">
                                            <h4 class="flex items-center gap-2 mb-2 font-bold text-secondary dark:text-secondary/90">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                                {{ __('verification.analysis_method') }}
                                            </h4>
                                            <p class="text-secondary/90 dark:text-secondary/80">
                                                @if($best_match['analysis_method'] === 'semantic_similarity')
                                                    {{ __('verification.analysis_method_semantic') }}
                                                @elseif($best_match['analysis_method'] === 'tfidf_cosine')
                                                    {{ __('verification.analysis_method_tfidf') }}
                                                @else
                                                    {{ $best_match['analysis_method'] }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Best Match Details -->
                                    <div class="p-6 border-s-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border-red-500">
                                        <div class="flex items-start gap-3 mb-4">
                                            <div class="p-2 rounded-lg bg-red-100 dark:bg-red-900/50">
                                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="mb-1 text-lg font-bold text-zinc-900 dark:text-zinc-50">
                                                    {{ __('verification.best_match_title') }}
                                                </h4>
                                            </div>
                                        </div>
                                        <p class="mb-3 text-xl font-bold text-zinc-900 dark:text-zinc-50">{{ $best_match['title'] ?? 'عنوان غير متوفر' }}</p>
                                        <p class="mb-4 leading-relaxed text-zinc-700 dark:text-zinc-300">
                                            {{ Str::limit($best_match['content'] ?? 'المحتوى غير متوفر', 300) }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                                {{ __('verification.source_label') }}: {{ $best_match['origin_dataset'] ?? 'مصدر غير محدد' }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium rounded-full bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ __('verification.confidence_label') }}:
                                                {{ number_format($best_match['confidence_score'] * 100, 1) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Additional Matches -->
                        @if(isset($similar_news) && is_array($similar_news) && count($similar_news) > 1)
                            <div class="p-8 bg-zinc-50 dark:bg-zinc-800/30">
                                <div class="max-w-4xl mx-auto">
                                    <h3 class="flex items-center gap-2 mb-6 text-xl font-bold text-zinc-900 dark:text-zinc-50">
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {{ __('verification.additional_matches_title') }}
                                        <span
                                            class="px-3 py-1 text-sm font-semibold text-orange-800 bg-orange-200 rounded-full dark:bg-orange-900 dark:text-orange-200">
                                            {{ (isset($similar_news) && is_array($similar_news) ? count($similar_news) : 1) - 1 }}
                                        </span>
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach(array_slice($similar_news ?? [], 1) as $news)
                                            <div
                                                class="p-5 transition-all border-s-4 rounded-xl bg-white dark:bg-zinc-900 border-orange-400 hover:shadow-lg">
                                                <div class="flex items-start justify-between gap-4 mb-2">
                                                    <p class="flex-1 text-lg font-bold text-zinc-900 dark:text-zinc-50">{{ $news['title'] }}
                                                    </p>
                                                    <span
                                                        class="inline-flex items-center gap-1 px-3 py-1 text-sm font-bold text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900/50 dark:text-orange-200 whitespace-nowrap">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg>
                                                        {{ number_format($news['similarity_score'] * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ Str::limit($news['content'], 150) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>


                @elseif($found)
                    <!-- FOUND BUT LOW SIMILARITY: Show Caution -->
                    <div class="mb-8 overflow-hidden border-t-4 shadow-xl rounded-2xl border-orange-500 bg-white dark:bg-zinc-900">
                        <div class="p-10 text-center text-white bg-gradient-to-br from-orange-500 to-orange-600">
                            <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="mb-3 text-4xl font-bold">{{ __('verification.caution_title') }}</h2>
                            <p class="text-xl opacity-95">{{ $recommendation ?? __('verification.safe_default_message') }}</p>
                        </div>

                        <!-- ChatGPT Source Verification Results -->
                        @if(isset($used_chatgpt_fallback) && $used_chatgpt_fallback && isset($chatgpt_result))
                            <div class="p-8 border-b bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800">
                                <div class="max-w-4xl mx-auto">
                                    <!-- Section Header -->
                                    <div class="flex items-center justify-center gap-3 mb-6">
                                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">
                                            @if($detected_language === 'ar')
                                                التحقق من المصادر الموثوقة
                                            @else
                                                Trusted Sources Verification
                                            @endif
                                        </h3>
                                    </div>

                                    @php
                                        $sourceStatus = $chatgpt_result['source_verification_status'] ?? [];
                                        $foundInSources = $sourceStatus['found_in_sources'] ?? false;
                                        $sourcesSearched = $sourceStatus['sources_searched'] ?? 0;
                                        $matchingSources = $sourceStatus['matching_sources'] ?? [];
                                        $highestSimilarity = $sourceStatus['highest_similarity'] ?? 0;
                                        $confidenceScore = $chatgpt_result['confidence_score'] ?? 0;
                                        $analysis = $chatgpt_result['analysis'][$detected_language] ?? $chatgpt_result['analysis']['ar'] ?? '';
                                    @endphp

                                    @if($foundInSources)
                                        <!-- FOUND IN SOURCES: Positive Result -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-950/30 dark:to-green-950/30 border-emerald-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-emerald-800 dark:text-emerald-300">
                                                        @if($detected_language === 'ar')
                                                            تم العثور على محتوى مشابه في المصادر الموثوقة
                                                        @else
                                                            Similar Content Found in Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-emerald-700 dark:text-emerald-400">
                                                        @if($detected_language === 'ar')
                                                            تم التحقق من {{ $sourcesSearched }} مصدر موثوق ووجدنا تطابقاً دلالياً مع المصادر التالية:
                                                        @else
                                                            Verified against {{ $sourcesSearched }} trusted sources and found semantic match with:
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Matching Sources -->
                                            <div class="mb-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($matchingSources as $source)
                                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-800 bg-emerald-200 rounded-full dark:bg-emerald-900 dark:text-emerald-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            {{ $source['source_name'] ?? $source }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Confidence Score -->
                                            <div class="p-4 mb-4 rounded-lg bg-white/70 dark:bg-zinc-800/50">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                        @if($detected_language === 'ar')
                                                            درجة الثقة في التطابق
                                                        @else
                                                            Match Confidence Score
                                                        @endif
                                                    </span>
                                                    <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                                        {{ number_format($highestSimilarity * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="relative overflow-hidden rounded-full h-3 bg-zinc-200 dark:bg-zinc-700">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-600"
                                                         style="width: {{ $highestSimilarity * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- NOT FOUND IN SOURCES: Warning Result -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-orange-50 to-yellow-50 dark:from-orange-950/30 dark:to-yellow-950/30 border-orange-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-orange-100 dark:bg-orange-900/50">
                                                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-orange-800 dark:text-orange-300">
                                                        @if($detected_language === 'ar')
                                                            لم يتم العثور على هذا الخبر في المصادر الموثوقة
                                                        @else
                                                            News Not Found in Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-orange-700 dark:text-orange-400">
                                                        @if($detected_language === 'ar')
                                                            تم البحث في {{ $sourcesSearched }} مصدر موثوق ولم يتم العثور على هذا الخبر في أي منها.
                                                        @else
                                                            Searched {{ $sourcesSearched }} trusted sources - this news was not found in any of them.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Sources Checked -->
                                            @if(isset($chatgpt_result['sources_checked']) && count($chatgpt_result['sources_checked']) > 0)
                                                <div class="mb-4">
                                                    <p class="mb-2 text-sm font-bold text-orange-800 dark:text-orange-300">
                                                        @if($detected_language === 'ar')
                                                            المصادر التي تم فحصها:
                                                        @else
                                                            Sources Checked:
                                                        @endif
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($chatgpt_result['sources_checked'] as $source)
                                                            <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-orange-800 bg-orange-200 rounded-full dark:bg-orange-900 dark:text-orange-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                {{ $source }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Confidence Score -->
                                            <div class="p-4 mb-4 rounded-lg bg-white/70 dark:bg-zinc-800/50">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                        @if($detected_language === 'ar')
                                                            درجة الثقة (احتمالية كون الخبر مشكوك فيه)
                                                        @else
                                                            Confidence Score (Likelihood of Being Questionable)
                                                        @endif
                                                    </span>
                                                    <span class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                                                        {{ number_format($confidenceScore * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="relative overflow-hidden rounded-full h-3 bg-zinc-200 dark:bg-zinc-700">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-orange-600"
                                                         style="width: {{ $confidenceScore * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- AI Analysis -->
                                    @if($analysis)
                                        <div class="p-6 border rounded-xl bg-purple-50 dark:bg-purple-950/20 border-purple-200 dark:border-purple-900/50">
                                            <h4 class="flex items-center gap-2 mb-3 text-lg font-bold text-purple-800 dark:text-purple-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                @if($detected_language === 'ar')
                                                    التحليل الذكي المفصل
                                                @else
                                                    Detailed AI Analysis
                                                @endif
                                            </h4>
                                            <p class="text-purple-900 dark:text-purple-200 leading-relaxed whitespace-pre-wrap">{{ $analysis }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(!empty($similar_news))
                            <div class="p-8 bg-white dark:bg-zinc-900">
                                <div class="max-w-4xl mx-auto">
                                    <h3 class="flex items-center gap-2 mb-6 text-xl font-bold text-zinc-900 dark:text-zinc-50">
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ __('verification.similar_news_found') }}
                                        <span
                                            class="px-3 py-1 text-sm font-semibold text-orange-800 bg-orange-200 rounded-full dark:bg-orange-900 dark:text-orange-200">
                                            {{ isset($similar_news) && is_array($similar_news) ? count($similar_news) : 0 }}
                                        </span>
                                    </h3>
                                    <div class="space-y-4">
                                        @foreach($similar_news ?? [] as $news)
                                            <div
                                                class="p-5 transition-all border-s-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 border-orange-400 hover:shadow-lg">
                                                <div class="flex items-start justify-between gap-4 mb-2">
                                                    <p class="flex-1 text-lg font-bold text-zinc-900 dark:text-zinc-50">{{ $news['title'] }}
                                                    </p>
                                                    <span
                                                        class="inline-flex items-center gap-1 px-3 py-1 text-sm font-bold text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900/50 dark:text-orange-200 whitespace-nowrap">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg>
                                                        {{ number_format($news['similarity_score'] * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $news['content'] }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                @else
                    <!-- NOT FOUND: Show Safe Message -->
                    <div class="mb-8 overflow-hidden border-t-4 shadow-xl rounded-2xl border-emerald-500 bg-white dark:bg-zinc-900">
                        <div class="p-10 text-center text-white bg-gradient-to-br from-primary via-primary-300 to-emerald-500">
                            <div class="relative inline-block mb-4">
                                <svg class="w-28 h-28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="absolute rounded-full opacity-30 inset-2 animate-ping bg-emerald-300"></div>
                            </div>
                            <h2 class="mb-3 text-4xl font-bold">{{ __('verification.safe_title') }}</h2>
                            <p class="text-xl opacity-95">{{ __('verification.safe_subtitle') }}</p>
                        </div>

                        <!-- ChatGPT Source Verification Results -->
                        @if(isset($used_chatgpt_fallback) && $used_chatgpt_fallback && isset($chatgpt_result))
                            <div class="p-8 border-b bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800">
                                <div class="max-w-4xl mx-auto">
                                    <!-- Section Header -->
                                    <div class="flex items-center justify-center gap-3 mb-6">
                                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">
                                            @if($detected_language === 'ar')
                                                التحقق من المصادر الموثوقة
                                            @else
                                                Trusted Sources Verification
                                            @endif
                                        </h3>
                                    </div>

                                    @php
                                        $sourceStatus = $chatgpt_result['source_verification_status'] ?? [];
                                        $foundInSources = $sourceStatus['found_in_sources'] ?? false;
                                        $sourcesSearched = $sourceStatus['sources_searched'] ?? 0;
                                        $matchingSources = $sourceStatus['matching_sources'] ?? [];
                                        $highestSimilarity = $sourceStatus['highest_similarity'] ?? 0;
                                        $confidenceScore = $chatgpt_result['confidence_score'] ?? 0;
                                        $analysis = $chatgpt_result['analysis'][$detected_language] ?? $chatgpt_result['analysis']['ar'] ?? '';
                                    @endphp

                                    @if($foundInSources)
                                        <!-- FOUND IN SOURCES: Positive Result -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-950/30 dark:to-green-950/30 border-emerald-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-emerald-800 dark:text-emerald-300">
                                                        @if($detected_language === 'ar')
                                                            تم العثور على محتوى مشابه في المصادر الموثوقة
                                                        @else
                                                            Similar Content Found in Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-emerald-700 dark:text-emerald-400">
                                                        @if($detected_language === 'ar')
                                                            تم التحقق من {{ $sourcesSearched }} مصدر موثوق ووجدنا تطابقاً دلالياً مع المصادر التالية:
                                                        @else
                                                            Verified against {{ $sourcesSearched }} trusted sources and found semantic match with:
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Matching Sources -->
                                            <div class="mb-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($matchingSources as $source)
                                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-800 bg-emerald-200 rounded-full dark:bg-emerald-900 dark:text-emerald-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            {{ $source['source_name'] ?? $source }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Confidence Score -->
                                            <div class="p-4 mb-4 rounded-lg bg-white/70 dark:bg-zinc-800/50">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                                                        @if($detected_language === 'ar')
                                                            درجة الثقة في التطابق
                                                        @else
                                                            Match Confidence Score
                                                        @endif
                                                    </span>
                                                    <span class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                                                        {{ number_format($highestSimilarity * 100, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="relative overflow-hidden rounded-full h-3 bg-zinc-200 dark:bg-zinc-700">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-600"
                                                         style="width: {{ $highestSimilarity * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- NOT FOUND IN SOURCES: Informational Note -->
                                        <div class="p-6 mb-6 border-s-4 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 border-blue-500">
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/50">
                                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="mb-2 text-xl font-bold text-blue-800 dark:text-blue-300">
                                                        @if($detected_language === 'ar')
                                                            لم يتم العثور على هذا الخبر في قاعدة البيانات أو المصادر الموثوقة
                                                        @else
                                                            News Not Found in Database or Trusted Sources
                                                        @endif
                                                    </h4>
                                                    <p class="text-blue-700 dark:text-blue-400">
                                                        @if($detected_language === 'ar')
                                                            تم البحث في {{ $sourcesSearched }} مصدر موثوق. عدم العثور على الخبر قد يعني أنه جديد أو لم يتم تغطيته بعد.
                                                        @else
                                                            Searched {{ $sourcesSearched }} trusted sources. Not finding the news may mean it's new or hasn't been covered yet.
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Sources Checked -->
                                            @if(isset($chatgpt_result['sources_checked']) && count($chatgpt_result['sources_checked']) > 0)
                                                <div class="mb-4">
                                                    <p class="mb-2 text-sm font-bold text-blue-800 dark:text-blue-300">
                                                        @if($detected_language === 'ar')
                                                            المصادر التي تم فحصها:
                                                        @else
                                                            Sources Checked:
                                                        @endif
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($chatgpt_result['sources_checked'] as $source)
                                                            <span class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-blue-800 bg-blue-200 rounded-full dark:bg-blue-900 dark:text-blue-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12l2 2 4-4" />
                                                                </svg>
                                                                {{ $source }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- AI Analysis -->
                                    @if($analysis)
                                        <div class="p-6 border rounded-xl bg-purple-50 dark:bg-purple-950/20 border-purple-200 dark:border-purple-900/50">
                                            <h4 class="flex items-center gap-2 mb-3 text-lg font-bold text-purple-800 dark:text-purple-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                @if($detected_language === 'ar')
                                                    التحليل الذكي المفصل
                                                @else
                                                    Detailed AI Analysis
                                                @endif
                                            </h4>
                                            <p class="text-purple-900 dark:text-purple-200 leading-relaxed whitespace-pre-wrap">{{ $analysis }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="p-8 bg-white dark:bg-zinc-900">
                            <div class="max-w-4xl mx-auto text-center">
                                <div
                                    class="inline-flex items-center gap-2 px-4 py-2 mb-4 font-semibold text-primary bg-primary-100 rounded-full dark:bg-primary/20 dark:text-primary-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ __('verification.safe_analysis_title') }}
                                </div>
                                <p class="mb-6 text-lg leading-relaxed text-zinc-700 dark:text-zinc-300">
                                    {{ $recommendation ?? __('verification.safe_default_message') }}
                                </p>
                                <div class="p-6 border-s-4 border-primary rounded-xl bg-primary-100/50 dark:bg-primary/10">
                                    <div class="flex justify-center items-start gap-3">
                                        <div class="p-2 rounded-lg bg-primary-100 dark:bg-primary/20">
                                            <svg class="w-6 h-6 text-primary dark:text-primary-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 text-start">
                                            <p class="mb-2 font-bold text-primary dark:text-primary-200">
                                                {{ __('verification.important_note_title') }}
                                            </p>
                                            <p class="mb-3 text-primary/90 dark:text-primary-300">
                                                {{ __('verification.important_note_text') }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="mb-2 border-primary-200 dark:border-primary/20" />
                                    <div>
                                        <ul class="space-y-1 text-sm text-primary/90 dark:text-primary-300">
                                            <li class="flex items-center gap-2">
                                                <span>{{ __('verification.source_spa') }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span>{{ __('verification.source_moj') }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span>{{ __('verification.source_gov') }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Your Submitted Text -->
                <div class="mb-8 overflow-hidden shadow-lg rounded-2xl bg-white dark:bg-zinc-900">
                    <div
                        class="p-6 text-white bg-gradient-to-r from-primary to-primary-300 dark:from-primary dark:to-primary-400">
                        <h3 class="flex items-center gap-2 text-2xl font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('verification.submitted_text_title') }}
                        </h3>
                    </div>
                    <div class="p-8">
                        <p class="mb-6 text-lg leading-relaxed text-zinc-800 dark:text-zinc-200">
                            {{ $search_content }}
                        </p>

                        @if(isset($preprocessed_text) && $preprocessed_text)
                            <div class="p-5 mt-6 border-s-4 border-secondary rounded-xl bg-secondary/10 dark:bg-secondary/20">
                                <p class="mb-2 text-sm font-bold text-secondary dark:text-secondary/90">
                                    {{ __('verification.preprocessed_text_label') }}
                                </p>
                                <p class="text-sm font-mono text-secondary/90 dark:text-secondary/80">{{ $preprocessed_text }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Side Section --}}
            <aside id="side-content"  class="basis-2/6">
                            <!-- Text Quality Analysis -->
                @if(isset($query_quality))
                    <div
                        class="p-8 mb-8 overflow-hidden border rounded-2xl bg-white/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-700 backdrop-blur-sm">
                        <h3 class="mb-6 text-2xl font-bold text-center text-zinc-900 dark:text-zinc-50">
                            {{ __('verification.quality_analysis_title') }}
                        </h3>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div
                                class="p-6 text-center transition-all border rounded-xl bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 hover:shadow-lg hover:border-primary dark:hover:border-primary-300">
                                <div class="mb-2 text-4xl font-bold text-primary dark:text-primary-300">
                                    {{ $query_quality['word_count'] ?? 0 }}
                                </div>
                                <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                    {{ __('verification.word_count') }}
                                </div>
                            </div>
                            <div
                                class="p-6 text-center transition-all border rounded-xl bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 hover:shadow-lg hover:border-emerald-400 dark:hover:border-emerald-600">
                                <div class="mb-2 text-4xl font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ number_format(($query_quality['arabic_ratio'] ?? 0) * 100, 1) }}%
                                </div>
                                <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                    {{ __('verification.arabic_ratio') }}
                                </div>
                            </div>
                            <div
                                class="p-6 text-center transition-all border rounded-xl bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 hover:shadow-lg hover:border-secondary dark:hover:border-secondary/70">
                                <div class="mb-2 text-4xl font-bold text-secondary dark:text-secondary/90">
                                    {{ $query_quality['legal_terms_count'] ?? 0 }}
                                </div>
                                <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                    {{ __('verification.legal_terms_count') }}
                                </div>
                            </div>
                            <div
                                class="p-6 text-center transition-all border rounded-xl bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 hover:shadow-lg hover:border-amber-400 dark:hover:border-amber-600">
                                <div class="mb-2 text-4xl font-bold text-amber-600 dark:text-amber-400">
                                    {{ number_format(($query_quality['quality_score'] ?? 0) * 100, 1) }}%
                                </div>
                                <div class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                    {{ __('verification.quality_score') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Feedback Section -->
                <livewire:forms.verification-feedback-form />
            </aside>
        </div>

    </div>

    @if(isset($safe_navigation) && $safe_navigation)
    <!-- Safe Navigation Script: Prevent form resubmission issues -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Replace browser history to prevent back button form resubmission
            if (window.history.replaceState) {
                window.history.replaceState(
                    { page: 'verification-result', preventResubmission: true },
                    document.title,
                    window.location.href
                );
            }
            
            // Handle back button navigation
            window.addEventListener('popstate', function(event) {
                if (event.state && event.state.preventResubmission) {
                    // Redirect to home instead of going back to form
                    window.location.href = '{{ $home_url ?? route("home") }}';
                }
            });
            
            // Add safe navigation to all internal links
            document.querySelectorAll('a[href]').forEach(function(link) {
                if (link.href.includes(window.location.hostname)) {
                    link.addEventListener('click', function() {
                        // Clear any remaining session data
                        if (window.history.pushState) {
                            window.history.pushState(
                                { page: 'navigating', safe: true },
                                document.title,
                                link.href
                            );
                        }
                    });
                }
            });
            
            // Show user-friendly message if they try to refresh
            window.addEventListener('beforeunload', function(event) {
                if (performance.navigation && performance.navigation.type === 1) {
                    event.preventDefault();
                    event.returnValue = '{{ __("verification.refresh_warning") ?? "Results will be lost if you refresh. Navigate using the buttons instead." }}';
                }
            });
        });
    </script>
    @endif
</x-layouts.main>