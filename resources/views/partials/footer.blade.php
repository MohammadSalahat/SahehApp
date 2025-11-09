<footer class="bg-gradient-to-r from-[#4a6b5a] to-[#3a5a4a] text-white py-16">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <img src="{{ asset('images/logo.png') }}" class="w-20 h-20 object-contain">
                    <div class="text-2xl font-bold">
                        <span class="text-white">منصة صحيح</span>
                    </div>
                </div>
                <p class="text-white/80 leading-relaxed text-lg">
                    {{ __('home.footer_desc') }}
                </p>
            </div>

            <div>
                <h3 class="text-2xl font-bold mb-6">{{ __('home.quick_links') }}</h3>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('navigation.home') }}</a>
                    </li>
                    <li><a href="#verify"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('navigation.verify_news') }}</a>
                    </li>
                    <li><a href="#sources"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('navigation.legal_sources') }}</a>
                    </li>
                    <li><a href="#about"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('navigation.about_project') }}</a>
                    </li>
                    <li><a href="#contact"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('navigation.contact') }}</a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-2xl font-bold mb-6">{{ __('home.legal_sources_section') }}</h3>
                <ul class="space-y-3">
                    <li><a href="#"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('home.source_1_title') }}</a>
                    </li>
                    <li><a href="#"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('home.source_2_title') }}</a>
                    </li>
                    <li><a href="#"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('home.source_3_title') }}</a>
                    </li>
                    <li><a href="#"
                            class="text-white/80 hover:text-white transition-all duration-300 hover:translate-x-2 inline-block text-lg">{{ __('home.source_5_title') }}</a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-2xl font-bold mb-6">{{ __('home.contact_us') }}</h3>
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
                {{ __('home.copyright') }}
                <span class="text-green-400 text-2xl">💚</span>
            </p>
        </div>
    </div>
</footer>