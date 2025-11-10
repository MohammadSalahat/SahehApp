<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        {{-- Logo Section --}}
        <a href="{{ route('home') }}"
            class="flex items-center p-3 mb-4 space-x-2 transition-all border rounded-lg rtl:space-x-reverse hover:bg-primary-50 dark:hover:bg-primary-950/30 border-zinc-200 dark:border-zinc-700 hover:border-primary-300 dark:hover:border-primary-800"
            wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            {{-- User Settings Section --}}
            <flux:navlist.group expandable class="grid">
                <div slot="heading"
                    class="flex items-center gap-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    <flux:icon.user-circle variant="solid" class="size-4 text-primary-500" />
                    {{ __('User Settings') }}
                </div>

                <flux:navlist.item :href="route('settings.profile')" icon="user" icon:variant="solid"
                    :current="request()->routeIs('settings.profile')" wire:navigate class="group">
                    <span class="group-data-[current]:font-semibold">{{ __('Profile') }}</span>
                </flux:navlist.item>

                <flux:navlist.item :href="route('settings.password')" icon="key" icon:variant="solid"
                    :current="request()->routeIs('settings.password')" wire:navigate class="group">
                    <span class="group-data-[current]:font-semibold">{{ __('Password') }}</span>
                </flux:navlist.item>

                <flux:navlist.item :href="route('two-factor.show')" icon="shield-check" icon:variant="solid"
                    :current="request()->routeIs('two-factor.show')" wire:navigate class="group">
                    <span class="group-data-[current]:font-semibold">{{ __('Two-Factor Auth') }}</span>
                </flux:navlist.item>
            </flux:navlist.group>

            {{-- Divider --}}
            <flux:separator class="my-4" />

            {{-- Platform Section --}}
            <flux:navlist.group expandable class="grid">
                <div slot="heading"
                    class="flex items-center gap-2 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    <flux:icon.squares-2x2 variant="solid" class="size-4 text-primary-500" />
                    {{ __('Platform') }}
                </div>

                <flux:navlist.item :href="route('home')" icon="home" icon:variant="solid"
                    :current="request()->routeIs('home')" wire:navigate class="group">
                    <span class="group-data-[current]:font-semibold">{{ __('Dashboard') }}</span>
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        {{-- Quick Stats Card (Optional Enhancement) --}}
        <div
            class="p-4 mb-4 space-y-2 border rounded-lg bg-primary-50/50 dark:bg-primary-950/20 border-primary-200 dark:border-primary-900/50">
            <div class="flex items-center gap-2 mb-2">
                <flux:icon.shield-check variant="solid" class="size-4 text-primary-600 dark:text-primary-400" />
                <flux:text class="text-xs font-semibold text-primary-700 dark:text-primary-300">
                    {{ __('Account Status') }}
                </flux:text>
            </div>

            @if(auth()->user()->hasVerifiedEmail())
                <div class="flex items-center gap-2 px-2 py-1 rounded bg-primary-100 dark:bg-primary-900/50">
                    <flux:icon.check-badge variant="solid" class="size-3 text-primary-600 dark:text-primary-400" />
                    <flux:text class="text-xs text-primary-700 dark:text-primary-300">
                        {{ __('Email Verified') }}
                    </flux:text>
                </div>
            @else
                <div class="flex items-center gap-2 px-2 py-1 bg-amber-100 rounded dark:bg-amber-900/50">
                    <flux:icon.exclamation-triangle variant="solid" class="size-3 text-amber-600 dark:text-amber-400" />
                    <flux:text class="text-xs text-amber-700 dark:text-amber-300">
                        {{ __('Email Unverified') }}
                    </flux:text>
                </div>
            @endif

            @if(auth()->user()->two_factor_secret)
                <div class="flex items-center gap-2 px-2 py-1 rounded bg-primary-100 dark:bg-primary-900/50">
                    <flux:icon.shield-check variant="solid" class="size-3 text-primary-600 dark:text-primary-400" />
                    <flux:text class="text-xs text-primary-700 dark:text-primary-300">
                        {{ __('2FA Enabled') }}
                    </flux:text>
                </div>
            @endif
        </div>

        <!-- Desktop User Menu -->
        <x-layouts.auth.desktop-user-menu />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <x-layouts.auth.mobile-user-menu />
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>