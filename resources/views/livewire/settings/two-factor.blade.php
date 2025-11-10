<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Two-Factor Authentication')" :subheading="__('Add an extra layer of security to your account')">
        {{-- What is 2FA Info Box --}}
        <flux:callout variant="info" icon="information-circle"
            class="my-6 bg-primary-50 dark:bg-primary-950/20 border-primary-200 dark:border-primary-900/50">
            <div class="space-y-2">
                <flux:heading size="sm" class="text-primary-700 dark:text-primary-300">
                    {{ __('What is Two-Factor Authentication?') }}</flux:heading>
                <flux:text class="text-sm text-primary-600 dark:text-primary-400">
                    {{ __('Two-factor authentication (2FA) adds an extra layer of security to your account. When enabled, you\'ll need both your password and a temporary code from your phone to log in.') }}
                </flux:text>
            </div>
        </flux:callout>

        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                {{-- 2FA Enabled State --}}
                <div
                    class="p-6 space-y-6 border-2 rounded-xl border-primary-200 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-950/20">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 p-2 rounded-lg bg-primary-100 dark:bg-primary-900/50">
                            <flux:icon.shield-check variant="solid" class="size-6 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <flux:heading size="lg" class="text-primary-700 dark:text-primary-300">
                                    {{ __('2FA is Active') }}</flux:heading>
                                <flux:badge color="lime" size="sm">
                                    <flux:icon.check-circle variant="solid" class="size-3" />
                                    {{ __('Enabled') }}
                                </flux:badge>
                            </div>

                            <flux:text class="text-sm">
                                {{ __('Your account is protected with two-factor authentication. You will be prompted for a secure, random code during login from your authenticator app.') }}
                            </flux:text>

                            {{-- Supported Apps --}}
                            <div
                                class="p-4 space-y-2 border rounded-lg bg-white/50 dark:bg-zinc-900/50 border-primary-200 dark:border-primary-800">
                                <flux:text variant="subtle"
                                    class="text-xs font-medium tracking-wide uppercase text-primary-600 dark:text-primary-400">
                                    {{ __('Compatible Authenticator Apps:') }}
                                </flux:text>
                                <flux:text class="text-sm">
                                    {{ __('Google Authenticator, Microsoft Authenticator, Authy, 1Password, Bitwarden') }}
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    {{-- Recovery Codes Section --}}
                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                    {{-- Disable Button --}}
                    <div class="flex justify-start pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button variant="danger" icon="shield-exclamation" icon:variant="outline" wire:click="disable">
                            {{ __('Disable Two-Factor Authentication') }}
                        </flux:button>
                    </div>
                </div>
            @else
                {{-- 2FA Disabled State --}}
                <div
                    class="p-6 space-y-6 border rounded-xl border-zinc-200 dark:border-white/10 bg-amber-50/50 dark:bg-amber-950/20">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 p-2 rounded-lg bg-amber-100 dark:bg-amber-900/50">
                            <flux:icon.shield-exclamation variant="solid"
                                class="text-amber-600 size-6 dark:text-amber-400" />
                        </div>
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <flux:heading size="lg">{{ __('2FA is Inactive') }}</flux:heading>
                                <flux:badge color="red" size="sm">
                                    <flux:icon.x-circle variant="solid" class="size-3" />
                                    {{ __('Disabled') }}
                                </flux:badge>
                            </div>

                            <flux:text class="text-sm">
                                {{ __('Your account is not protected by two-factor authentication. Enable 2FA to add an extra layer of security using a TOTP-supported application on your phone.') }}
                            </flux:text>

                            {{-- Benefits List --}}
                            <div class="p-4 space-y-3 rounded-lg bg-white/50 dark:bg-zinc-900/50">
                                <flux:text variant="subtle" class="text-xs font-medium uppercase tracking-wide">
                                    {{ __('Benefits of 2FA:') }}
                                </flux:text>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-start gap-2">
                                        <flux:icon.check variant="solid"
                                            class="flex-shrink-0 mt-0.5 text-green-500 size-4" />
                                        <span>{{ __('Protects against password theft') }}</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <flux:icon.check variant="solid"
                                            class="flex-shrink-0 mt-0.5 size-4 text-primary-500" />
                                        <span>{{ __('Prevents unauthorized access') }}</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <flux:icon.check variant="solid"
                                            class="flex-shrink-0 mt-0.5 size-4 text-primary-500" />
                                        <span>{{ __('Secures your sensitive data') }}</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <flux:icon.check variant="solid"
                                            class="flex-shrink-0 mt-0.5 size-4 text-primary-500" />
                                        <span>{{ __('Industry-standard security practice') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Enable Button --}}
                    <div class="flex justify-start pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:button variant="primary" icon="shield-check" icon:variant="solid" wire:click="enable"
                            class="font-semibold">
                            {{ __('Enable Two-Factor Authentication') }}
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </x-settings.layout>

    {{-- Setup Modal --}}
    <flux:modal name="two-factor-setup-modal" class="max-w-md md:min-w-md" @close="closeModal" wire:model="showModal">
        <div class="space-y-6">
            {{-- Modal Header --}}
            <div class="flex flex-col items-center space-y-4">
                <div
                    class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div
                        class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        <div
                            class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <div
                            class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>

                        <flux:icon.qr-code class="relative z-20 dark:text-accent-foreground" />
                    </div>
                </div>

                <div class="space-y-2 text-center">
                    <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                    <flux:text variant="subtle">{{ $this->modalConfig['description'] }}</flux:text>
                </div>
            </div>

            @if ($showVerificationStep)
                {{-- Verification Step --}}
                <div class="space-y-6">
                    <flux:callout variant="info" icon="information-circle">
                        <flux:text class="text-sm">
                            {{ __('Enter the 6-digit code from your authenticator app to complete setup.') }}
                        </flux:text>
                    </flux:callout>

                    <div class="flex flex-col items-center space-y-3">
                        <x-input-otp :digits="6" name="code" wire:model="code" autocomplete="one-time-code" />
                        @error('code')
                            <flux:text color="red" class="text-sm">
                                <flux:icon.x-circle variant="solid" class="inline-block size-4 me-1" />
                                {{ $message }}
                            </flux:text>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <flux:button variant="outline" class="flex-1" wire:click="resetVerification" icon="arrow-left"
                            icon:variant="outline">
                            {{ __('Back') }}
                        </flux:button>

                        <flux:button variant="primary" class="flex-1" wire:click="confirmTwoFactor"
                            x-bind:disabled="$wire.code.length < 6" icon="check" icon:variant="outline">
                            {{ __('Confirm & Enable') }}
                        </flux:button>
                    </div>
                </div>
            @else
                {{-- QR Code Step --}}
                @error('setupData')
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}" />
                @enderror

                {{-- Instructions --}}
                <flux:callout variant="info" icon="information-circle">
                    <div class="space-y-2 text-sm">
                        <p class="font-medium">{{ __('Setup Instructions:') }}</p>
                        <ol class="space-y-1 ms-4 list-decimal list-inside">
                            <li>{{ __('Open your authenticator app') }}</li>
                            <li>{{ __('Scan the QR code below') }}</li>
                            <li>{{ __('Enter the 6-digit code shown in the app') }}</li>
                        </ol>
                    </div>
                </flux:callout>

                {{-- QR Code --}}
                <div class="flex justify-center">
                    <div
                        class="relative w-64 overflow-hidden border rounded-lg border-stone-200 dark:border-stone-700 aspect-square">
                        @empty($qrCodeSvg)
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-white dark:bg-stone-700 animate-pulse">
                                <flux:icon.loading />
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full p-4">
                                {!! $qrCodeSvg !!}
                            </div>
                        @endempty
                    </div>
                </div>

                <div>
                    <flux:button :disabled="$errors->has('setupData')" variant="primary" class="w-full"
                        wire:click="showVerificationIfNecessary" icon="arrow-right" icon:variant="outline" iconTrailing>
                        {{ $this->modalConfig['buttonText'] }}
                    </flux:button>
                </div>

                {{-- Manual Entry Option --}}
                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-stone-200 dark:bg-stone-600"></div>
                        <span
                            class="relative px-3 text-xs font-medium bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-400">
                            {{ __('or, enter the code manually') }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-2" x-data="{
                                copied: false,
                                async copy() {
                                    try {
                                        await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                        this.copied = true;
                                        setTimeout(() => this.copied = false, 1500);
                                    } catch (e) {
                                        console.warn('Could not copy to clipboard');
                                    }
                                }
                            }">
                        <div class="flex items-stretch w-full border rounded-xl dark:border-stone-700">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3 bg-stone-100 dark:bg-stone-700">
                                    <flux:icon.loading variant="mini" />
                                </div>
                            @else
                                <input type="text" readonly value="{{ $manualSetupKey }}"
                                    class="w-full p-3 font-mono text-sm bg-transparent outline-none text-stone-900 dark:text-stone-100 select-all" />

                                <button @click="copy()"
                                    class="px-4 transition-colors border-l cursor-pointer border-stone-200 dark:border-stone-600 hover:bg-stone-50 dark:hover:bg-stone-700"
                                    title="{{ __('Copy to clipboard') }}">
                                    <flux:icon.document-duplicate x-show="!copied" variant="outline" class="size-5" />
                                    <flux:icon.check x-show="copied" variant="solid" class="text-green-500 size-5" />
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>
</section>