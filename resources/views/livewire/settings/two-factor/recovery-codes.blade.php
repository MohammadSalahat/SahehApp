<div class="py-6 space-y-6 border-2 shadow-sm rounded-xl border-primary-200 dark:border-primary-900/50 bg-primary-50/30 dark:bg-primary-950/20"
    wire:cloak x-data="{ showRecoveryCodes: false }">
    {{-- Header --}}
    <div class="px-6 space-y-3">
        <div class="flex items-center gap-2">
            <flux:icon.lock-closed variant="solid" class="size-5 text-primary-500" />
            <flux:heading size="lg" level="3" class="text-primary-700 dark:text-primary-300">{{ __('Recovery Codes') }}
            </flux:heading>
        </div>
        <flux:text variant="subtle">
            {{ __('Recovery codes are one-time passwords that let you regain access if you lose your authenticator device. Store them securely in a password manager.') }}
        </flux:text>

        {{-- Warning Callout --}}
        <flux:callout variant="warning" icon="exclamation-triangle" class="mt-3">
            <flux:text class="text-sm">
                <strong>{{ __('Important:') }}</strong>
                {{ __('Each code can only be used once. Make sure to save these codes in a secure location before closing this page.') }}
            </flux:text>
        </flux:callout>
    </div>

    {{-- Action Buttons --}}
    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <flux:button x-show="!showRecoveryCodes" icon="eye" icon:variant="outline" variant="primary"
                @click="showRecoveryCodes = true;" aria-expanded="false" aria-controls="recovery-codes-section"
                class="w-full sm:w-auto">
                {{ __('View Recovery Codes') }}
            </flux:button>

            <flux:button x-show="showRecoveryCodes" icon="eye-slash" icon:variant="outline" variant="ghost"
                @click="showRecoveryCodes = false" aria-expanded="true" aria-controls="recovery-codes-section"
                class="w-full sm:w-auto">
                {{ __('Hide Recovery Codes') }}
            </flux:button>

            @if (filled($recoveryCodes))
                <flux:button x-show="showRecoveryCodes" icon="arrow-path" icon:variant="outline" variant="filled"
                    wire:click="regenerateRecoveryCodes" class="w-full sm:w-auto">
                    {{ __('Regenerate Codes') }}
                </flux:button>
            @endif
        </div>

        {{-- Recovery Codes Display --}}
        <div x-show="showRecoveryCodes" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1" id="recovery-codes-section"
            class="relative overflow-hidden" x-bind:aria-hidden="!showRecoveryCodes">
            <div class="mt-4 space-y-3">
                @error('recoveryCodes')
                    <flux:callout variant="danger" icon="x-circle" heading="{{$message}}" />
                @enderror

                @if (filled($recoveryCodes))
                    {{-- Codes Grid with Copy Functionality --}}
                    <div class="relative p-5 font-mono text-sm border rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border-zinc-200 dark:border-zinc-700"
                        role="list" :aria-label="__('Recovery codes')" x-data="{
                                copied: false,
                                async copyAll() {
                                    const codes = @js($recoveryCodes).join('\n');
                                    try {
                                        await navigator.clipboard.writeText(codes);
                                        this.copied = true;
                                        setTimeout(() => this.copied = false, 2000);
                                    } catch (e) {
                                        console.warn('Could not copy to clipboard');
                                    }
                                }
                            }">
                        {{-- Copy All Button --}}
                        <div class="absolute top-3 right-3">
                            <flux:button variant="primary" size="sm" @click="copyAll()"
                                x-bind:icon="copied ? 'check' : 'clipboard'" icon:variant="solid">
                                <span x-text="copied ? '{{ __('Copied!') }}' : '{{ __('Copy All') }}'"></span>
                            </flux:button>
                        </div>

                        {{-- Codes Grid --}}
                        <div class="grid gap-2 mt-8 sm:grid-cols-2">
                            @foreach($recoveryCodes as $index => $code)
                                <div role="listitem"
                                    class="flex items-center justify-between p-3 transition-colors border rounded-md select-text bg-white dark:bg-zinc-900 border-primary-200 dark:border-primary-800 hover:border-primary-300 dark:hover:border-primary-700"
                                    wire:loading.class="opacity-50 animate-pulse">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 text-xs font-bold rounded-full bg-primary-200 dark:bg-primary-700 text-primary-700 dark:text-primary-300">
                                            {{ $index + 1 }}
                                        </span>
                                        <code
                                            class="font-mono text-sm font-semibold tracking-wider text-primary-900 dark:text-primary-100">{{ $code }}</code>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Usage Instructions --}}
                    <flux:callout variant="info" icon="information-circle"
                        class="bg-primary-50 dark:bg-primary-950/20 border-primary-200 dark:border-primary-900/50">
                        <div class="space-y-2 text-sm text-primary-700 dark:text-primary-300">
                            <p class="font-medium">{{ __('How to use recovery codes:') }}</p>
                            <ol class="space-y-1 ms-4 list-decimal list-inside">
                                <li>{{ __('When logging in, if you can\'t access your authenticator app, click "Use recovery code"') }}
                                </li>
                                <li>{{ __('Enter one of these codes to access your account') }}</li>
                                <li>{{ __('Each code works only once and will be removed after use') }}</li>
                                <li>{{ __('Generate new codes if you run out or suspect they\'ve been compromised') }}</li>
                            </ol>
                        </div>
                    </flux:callout>

                    {{-- Security Best Practices --}}
                    <flux:callout variant="warning" icon="shield-exclamation">
                        <div class="space-y-2 text-sm">
                            <p class="font-medium">{{ __('Security Best Practices:') }}</p>
                            <ul class="space-y-1 ms-4 list-disc list-inside">
                                <li>{{ __('Store codes in a secure password manager') }}</li>
                                <li>{{ __('Never share your recovery codes with anyone') }}</li>
                                <li>{{ __('Don\'t store codes in plain text files') }}</li>
                                <li>{{ __('Consider printing and storing in a safe place') }}</li>
                            </ul>
                        </div>
                    </flux:callout>
                @endif
            </div>
        </div>
    </div>
</div>