<!-- Navigation -->
<nav
    class="fixed top-0 left-0 right-0 z-[1000] bg-[#4a6b5a] backdrop-blur-sm transition-all duration-300 ease-out shadow">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('navigation.logo_alt') }}"
                    class="w-20 h-20 object-contain float-animation">
            </div>

            <div class="hidden lg:flex items-center gap-8">
                <a href="#"
                    class="text-white/90 hover:text-white transition-all duration-300 hover:scale-105">{{ __('navigation.home') }}</a>
                <a href="#verify"
                    class="text-white/90 hover:text-white transition-all duration-300 hover:scale-105">{{ __('navigation.verify_news') }}</a>
                <a href="#sources"
                    class="text-white/90 hover:text-white transition-all duration-300 hover:scale-105">{{ __('navigation.legal_sources') }}</a>
                <a href="#about" class="text-white/90 hover:text-white transition-all duration-300 hover:scale-105">{{ __('navigation.about_project') }}</a>
                <a href="#contact"
                    class="text-white/90 hover:text-white transition-all duration-300 hover:scale-105">{{ __('navigation.contact') }}</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <!-- Locale Switcher -->
                @livewire('locale-switcher')
                @auth
                    <!-- Authenticated User -->
                    <div class="flex items-center gap-4">
                        <div class="text-white/90 text-sm">
                            {{ __('navigation.hello', ['name' => Auth::user()->name]) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('dashboard') }}" wire:navigate
                                class="bg-transparent border-2 border-white text-white px-4 py-2 rounded-lg font-medium hover:bg-white hover:text-[#4a6b5a] transition-all duration-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7zm0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10M7 17h4" />
                                </svg>
                                <span>{{ __('navigation.dashboard') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('navigation.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest User -->
                    <a href="{{ route('login') }}" wire:navigate
                        class="bg-transparent border-2 border-white text-white px-6 py-2.5 rounded-lg font-medium hover:bg-white hover:text-[#4a6b5a] transition-all duration-300 relative overflow-hidden">
                        <span>{{ __('navigation.login') }}</span>
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-all duration-300">
                        {{ __('navigation.register') }}
                    </a>
                @endauth
            </div>

            <button id="mobileMenuBtn" class="lg:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu"
        class="lg:hidden fixed top-20 right-0 w-80 h-screen bg-[#4a6b5a] shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="p-6 space-y-6">
            <a href="#" class="block text-white/90 hover:text-white text-lg transition">{{ __('navigation.home') }}</a>
            <a href="#verify" class="block text-white/90 hover:text-white text-lg transition">{{ __('navigation.verify_news') }}</a>
            <a href="#sources" class="block text-white/90 hover:text-white text-lg transition">{{ __('navigation.legal_sources') }}</a>
            <a href="#about" class="block text-white/90 hover:text-white text-lg transition">{{ __('navigation.about_project') }}</a>
            <a href="#contact" class="block text-white/90 hover:text-white text-lg transition">{{ __('navigation.contact') }}</a>

            <!-- Locale Switcher Mobile -->
            <div class="pt-4 border-t border-white/20">
                @livewire('locale-switcher')
            </div>

            <div class="pt-6 space-y-3">
                @auth
                    <!-- Authenticated User Mobile -->
                    <div class="text-white/90 text-center pb-4">
                        {{ __('navigation.hello', ['name' => Auth::user()->name]) }}
                    </div>
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="w-full bg-transparent border-2 border-white text-white py-3 rounded-lg hover:bg-white hover:text-[#4a6b5a] transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7zm0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10M7 17h4" />
                        </svg>
                        <span>{{ __('navigation.dashboard') }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>{{ __('navigation.logout') }}</span>
                        </button>
                    </form>
                @else
                    <!-- Guest User Mobile -->
                    <a href="{{ route('login') }}" wire:navigate
                        class="w-full bg-transparent border-2 border-white text-white py-3 rounded-lg hover:bg-white hover:text-[#4a6b5a] transition-all duration-300 block text-center">
                        <span>{{ __('navigation.login') }}</span>
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="w-full bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white py-3 rounded-lg hover:shadow-lg transition-all duration-300 block text-center">
                        {{ __('navigation.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>