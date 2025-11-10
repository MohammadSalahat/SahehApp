<nav class="sticky top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a class="flex items-center cursor-pointer " href="{{ route('home') }}" wire:navigate>
                <x-app-logo-icon class="h-10 object-contain" />
                <span class="ml-2 text-xl font-semibold text-primary">{{ __('navigation.name') }}</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-start  gap-4">
                <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 text-sm font-medium transition-colors">
                    {{ __('navigation.home') }}
                </a>
                <a href="#about"
                    class="text-gray-700 hover:text-primary px-3 py-2 text-sm font-medium transition-colors">
                    {{ __('navigation.about_project') }}
                </a>
                <a href="#verify"
                    class="text-gray-700 hover:text-primary px-3 py-2 text-sm font-medium transition-colors">
                    {{ __('navigation.verify_news') }}
                </a>
                <a href="#latest-news"
                    class="text-gray-700 hover:text-primary px-3 py-2 text-sm font-medium transition-colors">
                    {{ __('navigation.latest_news') }}
                </a>
                <a href="#contact"
                    class="text-gray-700 hover:text-primary px-3 py-2 text-sm font-medium transition-colors">
                    {{ __('navigation.contact') }}
                </a>
            </div>

            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Locale Switcher -->
                @livewire('locale-switcher')

                @auth
                    <!-- Authenticated User -->
                    <x-layouts.auth.desktop-user-menu />
                @else
                    <!-- Guest User -->
                    <a href="{{ route('login') }}" wire:navigate
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium rounded-md transition-colors">
                        {{ __('navigation.login') }}
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="inline-flex items-center px-4 py-2 border border-transparent text-white bg-primary hover:bg-primary-300 text-sm font-medium rounded-md transition-colors">
                        {{ __('navigation.register') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobileMenuBtn" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-100 transition-colors"
                    aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu icon -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#"
                class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-md text-base font-medium transition-colors">
                {{ __('navigation.home') }}
            </a>
            <a href="#verify"
                class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-md text-base font-medium transition-colors">
                {{ __('navigation.verify_news') }}
            </a>
            <a href="#sources"
                class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-md text-base font-medium transition-colors">
                {{ __('navigation.legal_sources') }}
            </a>
            <a href="#about"
                class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-md text-base font-medium transition-colors">
                {{ __('navigation.about_project') }}
            </a>
            <a href="#contact"
                class="block px-3 py-2 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-md text-base font-medium transition-colors">
                {{ __('navigation.contact') }}
            </a>
        </div>

        <!-- Mobile Actions -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <!-- Locale Switcher Mobile -->
            <div class="px-3 mb-4">
                @livewire('locale-switcher')
            </div>

            @auth
                <div class="px-3 space-y-3">
                    <div class="text-sm text-gray-600 text-center py-2">
                        {{ __('navigation.hello', ['name' => Auth::user()->name]) }}
                    </div>
                    <a href="{{ route('settings.profile') }}" wire:navigate
                        class="w-full flex items-center justify-center px-3 py-2 border border-primary text-primary bg-white hover:bg-primary hover:text-white rounded-md text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7zm0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10M7 17h4" />
                        </svg>
                        {{ __('navigation.dashboard') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-3 py-2 border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-md text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('navigation.logout') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="px-3 space-y-3">
                    <a href="{{ route('login') }}" wire:navigate
                        class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-md text-sm font-medium transition-colors">
                        {{ __('navigation.login') }}
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="w-full flex items-center justify-center px-3 py-2 border border-transparent text-white bg-primary hover:bg-primary-300 rounded-md text-sm font-medium transition-colors">
                        {{ __('navigation.register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = mobileMenuBtn.querySelector('svg:first-child');
        const closeIcon = mobileMenuBtn.querySelector('svg:last-child');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function () {
                const isOpen = !mobileMenu.classList.contains('hidden');

                if (isOpen) {
                    // Close menu
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                } else {
                    // Open menu
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'true');
                }
            });

            // Close menu when clicking on a link
            const menuLinks = mobileMenu.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function (event) {
                if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
</script>