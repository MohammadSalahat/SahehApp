<section class="bg-[#f8f6f0] py-24 relative overflow-hidde">
    <div class="absolute inset-0 bg-gradient-to-br from-[#f8f6f0] to-[#faf9f5]"></div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h1 class="text-5xl md:text-7xl font-bold text-[#4a6b5a] mb-6 leading-tight">
            {{ __('home.welcome') }}
        </h1>
        <p class="text-xl md:text-2xl text-gray-700 mb-4 max-w-4xl mx-auto leading-relaxed">
            {{ __('home.hero_title') }}
        </p>
        <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-4xl mx-auto leading-relaxed">
            {{ __('home.hero_subtitle') }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <button
                class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white px-10 py-4 rounded-xl text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                <svg class="w-6 h-6 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                {{ __('home.start_verification') }}
            </button>
            <button
                class="bg-white border-2 border-[#4a6b5a] text-[#4a6b5a] px-10 py-4 rounded-xl text-lg font-bold hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('home.learn_more') }}
            </button>
        </div>
    </div>
</section>