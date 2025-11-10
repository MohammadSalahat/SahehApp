<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update Password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        {{-- Security Best Practices --}}
        <flux:callout variant="info" icon="shield-check"
            class="my-6 bg-primary-50 dark:bg-primary-950/20 border-primary-200 dark:border-primary-900/50">
            <div class="space-y-2">
                <flux:heading size="sm" class="text-primary-700 dark:text-primary-300">
                    {{ __('Password Security Tips') }}</flux:heading>
                <flux:text class="text-sm text-primary-600 dark:text-primary-400">
                    {{ __('A strong password should:') }}
                </flux:text>
                <ul class="text-sm space-y-1 ms-4 list-disc list-inside text-primary-700 dark:text-primary-300">
                    <li>{{ __('Be at least 8 characters long') }}</li>
                    <li>{{ __('Include uppercase and lowercase letters') }}</li>
                    <li>{{ __('Contain numbers and special characters') }}</li>
                    <li>{{ __('Be unique and not used on other sites') }}</li>
                </ul>
            </div>
        </flux:callout>

        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6" x-data="{
            password: '',
            strength: 0,
            strengthLabel: '{{ __('Very Weak') }}',
            strengthColor: 'bg-red-500',
            calculateStrength() {
                const pwd = this.password;
                let score = 0;
                
                if (!pwd) {
                    this.strength = 0;
                    return;
                }
                
                // Length check
                if (pwd.length >= 8) score++;
                if (pwd.length >= 12) score++;
                
                // Character variety
                if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
                if (/\d/.test(pwd)) score++;
                if (/[^a-zA-Z0-9]/.test(pwd)) score++;
                
                this.strength = score;
                
                if (score === 0) {
                    this.strengthLabel = '{{ __('Very Weak') }}';
                    this.strengthColor = 'bg-red-500';
                } else if (score <= 2) {
                    this.strengthLabel = '{{ __('Weak') }}';
                    this.strengthColor = 'bg-orange-500';
                } else if (score === 3) {
                    this.strengthLabel = '{{ __('Fair') }}';
                    this.strengthColor = 'bg-yellow-500';
                } else if (score === 4) {
                    this.strengthLabel = '{{ __('Good') }}';
                    this.strengthColor = 'bg-lime-500';
                } else {
                    this.strengthLabel = '{{ __('Strong') }}';
                    this.strengthColor = 'bg-green-500';
                }
            }
        }">
            {{-- Current Password --}}
            <div class="space-y-2">
                <flux:input wire:model="current_password" :label="__('Current Password')" type="password" required
                    autocomplete="current-password" icon="lock-closed" icon:variant="outline"
                    :placeholder="__('Enter your current password')" />
            </div>

            {{-- New Password --}}
            <div class="space-y-2">
                <flux:input wire:model="password" x-model="password" @input="calculateStrength()"
                    :label="__('New Password')" type="password" required autocomplete="new-password" icon="key"
                    icon:variant="outline" :placeholder="__('Enter a strong password')" />

                {{-- Password Strength Indicator --}}
                <div x-show="password.length > 0" x-transition class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <flux:text variant="subtle">{{ __('Password Strength:') }}</flux:text>
                        <flux:text x-text="strengthLabel" class="font-medium"></flux:text>
                    </div>
                    <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-300 rounded-full" x-bind:class="strengthColor"
                            x-bind:style="`width: ${strength * 20}%`"></div>
                    </div>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <flux:input wire:model="password_confirmation" :label="__('Confirm New Password')" type="password"
                    required autocomplete="new-password" icon="lock-closed" icon:variant="outline"
                    :placeholder="__('Re-enter your new password')" />
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <flux:button variant="primary" type="submit" class="flex-1 sm:flex-none" icon="check"
                    icon:variant="solid">
                    {{ __('Update Password') }}
                </flux:button>

                <x-action-message class="flex items-center gap-2" on="password-updated">
                    <flux:badge color="lime" size="sm" class="animate-in fade-in zoom-in duration-300">
                        <flux:icon.check-circle variant="solid" class="size-3" />
                        {{ __('Password updated successfully!') }}
                    </flux:badge>
                </x-action-message>
            </div>

            {{-- Security Reminder --}}
            <flux:callout variant="warning" icon="exclamation-triangle" class="mt-6">
                <flux:text class="text-sm">
                    {{ __('After changing your password, you will remain logged in on this device. However, you may be logged out of other devices and browsers.') }}
                </flux:text>
            </flux:callout>
        </form>
    </x-settings.layout>
</section>