<div class="relative w-full mb-8">
    {{-- Decorative Background Element --}}
    <div
        class="absolute inset-0 h-32 rounded-xl bg-gradient-to-br from-primary-50 to-primary-100/50 dark:from-primary-950/20 dark:to-primary-900/10 -z-10">
    </div>

    <div class="relative p-6 space-y-4">
        {{-- Header with Icon --}}
        <div class="flex items-center gap-3">
            <div
                class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/50 ring-4 ring-primary-50 dark:ring-primary-950/30">
                <flux:icon.cog-6-tooth variant="solid" class="size-6 text-primary-600 dark:text-primary-400" />
            </div>
            <div class="flex-1">
                <flux:heading size="xl" level="1" class="text-zinc-900 dark:text-zinc-50">
                    {{ __('Settings') }}
                </flux:heading>
                <flux:subheading size="lg" class="text-zinc-600 dark:text-zinc-400">
                    {{ __('Manage your profile and account settings') }}
                </flux:subheading>
            </div>
        </div>

        {{-- Quick Info Cards --}}
        <div class="grid gap-3 mt-4 sm:grid-cols-3">
            {{-- Profile Card --}}
            <div
                class="flex items-center gap-3 p-3 transition-all border rounded-lg bg-white/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-700 hover:border-primary-300 dark:hover:border-primary-800 hover:shadow-sm">
                <div class="flex items-center justify-center rounded-lg w-9 h-9 bg-primary-100 dark:bg-primary-900/50">
                    <flux:icon.user variant="solid" class="size-4 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <flux:text class="text-xs font-medium text-zinc-900 dark:text-zinc-100">
                        {{ auth()->user()->name }}
                    </flux:text>
                    <flux:text variant="subtle" class="text-xs">
                        {{ __('Your Name') }}
                    </flux:text>
                </div>
            </div>

            {{-- Email Card --}}
            <div
                class="flex items-center gap-3 p-3 transition-all border rounded-lg bg-white/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-700 hover:border-primary-300 dark:hover:border-primary-800 hover:shadow-sm">
                <div class="flex items-center justify-center rounded-lg w-9 h-9 bg-primary-100 dark:bg-primary-900/50">
                    <flux:icon.envelope variant="solid" class="size-4 text-primary-600 dark:text-primary-400" />
                </div>
                <div class="flex-1 min-w-0">
                    <flux:text class="text-xs font-medium truncate text-zinc-900 dark:text-zinc-100">
                        {{ auth()->user()->email }}
                    </flux:text>
                    <div class="flex items-center gap-1">
                        @if(auth()->user()->hasVerifiedEmail())
                            <flux:icon.check-badge variant="solid" class="size-3 text-green-500" />
                            <flux:text variant="subtle" class="text-xs text-green-600 dark:text-green-400">
                                {{ __('Verified') }}
                            </flux:text>
                        @else
                            <flux:icon.exclamation-triangle variant="solid" class="size-3 text-amber-500" />
                            <flux:text variant="subtle" class="text-xs text-amber-600 dark:text-amber-400">
                                {{ __('Unverified') }}
                            </flux:text>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Security Card --}}
            <div
                class="flex items-center gap-3 p-3 transition-all border rounded-lg bg-white/50 dark:bg-zinc-900/50 border-zinc-200 dark:border-zinc-700 hover:border-primary-300 dark:hover:border-primary-800 hover:shadow-sm">
                <div class="flex items-center justify-center rounded-lg w-9 h-9 bg-primary-100 dark:bg-primary-900/50">
                    <flux:icon.shield-check variant="solid" class="size-4 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <flux:text class="text-xs font-medium text-zinc-900 dark:text-zinc-100">
                        @if(auth()->user()->two_factor_secret)
                            {{ __('2FA Active') }}
                        @else
                            {{ __('2FA Inactive') }}
                        @endif
                    </flux:text>
                    <flux:text variant="subtle" class="text-xs">
                        {{ __('Security Status') }}
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative Separator --}}
    <flux:separator variant="subtle" class="mt-6" />
</div>