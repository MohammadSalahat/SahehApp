<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-6">
            {{ __('home.latest_news') }}
        </h2>
        <p class="text-center text-gray-600 mb-16 text-lg">
            {{ __('home.latest_news_desc') }}
        </p>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <div
                class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                <div class="w-full h-48 bg-primary rounded-xl mb-6 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                    {{ __('home.news_1_title') }}
                </h3>
                <p class="text-gray-700 mb-6 leading-relaxed">
                    {{ __('home.news_1_desc') }}
                </p>
                <button
                    class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('home.verify_news') }}
                </button>
            </div>

            <div
                class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                <div class="w-full h-48 bg-primary rounded-xl mb-6 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                    {{ __('home.news_2_title') }}
                </h3>
                <p class="text-gray-700 mb-6 leading-relaxed">
                    {{ __('home.news_2_desc') }}
                </p>
                <button
                    class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('home.verify_news') }}
                </button>
            </div>

            <div
                class="bg-gradient-to-b from-white to-gray-50 rounded-2xl p-6 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-400">
                <div class="w-full h-48 bg-primary rounded-xl mb-6 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#4a6b5a] mb-4">
                    {{ __('home.news_3_title') }}
                </h3>
                <p class="text-gray-700 mb-6 leading-relaxed">
                    {{ __('home.news_3_desc') }}
                </p>
                <button
                    class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg font-medium shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('home.verify_news') }}
                </button>
            </div>
        </div>
    </div>
</section>