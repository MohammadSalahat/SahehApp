<section class="py-20 bg-white hidden">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-6">
            {{ __('home.how_it_works') }}
        </h2>
        <p class="text-center text-gray-600 mb-20 text-lg">
            {{ __('home.how_it_works_desc') }}
        </p>

        <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
            <div
                class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                <div
                    class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-primary shadow-lg">
                    <span class="text-primary font-bold text-2xl">1</span>
                </div>
                <div
                    class="w-24 h-24 bg-gradient-to-br bg-primary rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-primary mb-4">{{ __('home.step_1_title') }}</h3>
                <p class="text-gray-700 leading-relaxed text-lg">
                    {{ __('home.step_1_desc') }}
                </p>
            </div>

            <div
                class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                <div
                    class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-primary shadow-lg">
                    <span class="text-primary font-bold text-2xl">2</span>
                </div>
                <div
                    class="w-24 h-24 bg-gradient-to-br bg-primary rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-primary mb-4">{{ __('home.step_2_title') }}</h3>
                <p class="text-gray-700 leading-relaxed text-lg">
                    {{ __('home.step_2_desc') }}
                </p>
            </div>

            <div
                class="bg-[#f8f6f0] rounded-3xl p-10 text-center relative hover:-translate-y-2 hover:shadow-xl transition-all duration-400">
                <div
                    class="absolute -top-6 right-10 bg-white rounded-full w-16 h-16 flex items-center justify-center border-4 border-primary shadow-lg">
                    <span class="text-primary font-bold text-2xl">3</span>
                </div>
                <div
                    class="w-24 h-24 bg-gradient-to-br bg-primary rounded-full flex items-center justify-center mx-auto mb-6 pulse-soft">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-primary mb-4">{{ __('home.step_3_title') }}</h3>
                <p class="text-gray-700 leading-relaxed text-lg">
                    {{ __('home.step_3_desc') }}
                </p>
            </div>
        </div>
    </div>
</section>