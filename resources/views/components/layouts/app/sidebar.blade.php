<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('settings.profile')" icon="user"
                    :current="request()->routeIs('settings.profile')" wire:navigate>{{ __('profile') }}
                </flux:navlist.item>
                <flux:navlist.item icon="home" :href="route('settings.password')" icon="key"
                    :current="request()->routeIs('settings.password')" wire:navigate>{{ __('password') }}
                </flux:navlist.item>
                <flux:navlist.item icon="home" :href="route('two-factor.show')" icon="cog"
                    :current="request()->routeIs('two-factor.show')" wire:navigate>{{ __('two-factor') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

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