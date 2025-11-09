<div class="relative inline-block text-left" x-data="{ open: false }">
    <div>
        <button @click="open = !open" type="button"
            class="inline-flex items-center justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            aria-expanded="true" aria-haspopup="true">
            <!-- Current locale flag/icon -->
            <svg class="w-5 h-5 {{ $currentLocale === 'ar' ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
            </svg>

            <!-- Current locale name -->
            <span>{{ $availableLocales[$currentLocale]['name'] }}</span>

            <!-- Dropdown arrow -->
            <svg class="{{ $currentLocale === 'ar' ? 'mr-2' : 'ml-2' }} -mr-1 h-5 w-5"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!-- Dropdown menu -->
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-{{ $currentLocale === 'ar' ? 'left' : 'right' }} absolute {{ $currentLocale === 'ar' ? 'left' : 'right' }}-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="display: none;">
        <div class="py-1" role="none">
            @foreach ($availableLocales as $locale => $localeData)
                <a href="{{ route('changeLanguage', $locale) }}" @click="open = false"
                    class="group flex items-center px-4 py-2 text-sm w-full text-{{ $currentLocale === 'ar' ? 'right' : 'left' }}
                               {{ $locale === $currentLocale ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold' : 'text-gray-700 dark:text-gray-300' }}
                               hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors" role="menuitem" tabindex="-1">

                    <!-- Locale flag/icon -->
                    <svg class="w-5 h-5 {{ $currentLocale === 'ar' ? 'ml-3' : 'mr-3' }} text-gray-400 group-hover:text-gray-500"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>

                    <!-- Locale name -->
                    <span>{{ $localeData['name'] }}</span>

                    <!-- Checkmark for active locale -->
                    @if ($locale === $currentLocale)
                        <svg class="w-5 h-5 {{ $currentLocale === 'ar' ? 'mr-auto' : 'ml-auto' }} text-green-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>