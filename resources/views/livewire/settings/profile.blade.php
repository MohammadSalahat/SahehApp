<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile Information')" :subheading="__('Update your account\'s profile information and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            {{-- Name Field --}}
            <div class="space-y-2">
                <flux:input wire:model="name" :label="__('Full Name')" type="text" required autofocus
                    autocomplete="name" icon="user" icon:variant="outline" :placeholder="__('Enter your full name')" />
                <flux:text variant="subtle" class="text-xs">
                    {{ __('This name will be displayed publicly on your profile.') }}
                </flux:text>
            </div>

            {{-- Email Field --}}
            <div class="space-y-2">
                <flux:input wire:model="email" :label="__('Email Address')" type="email" required autocomplete="email"
                    icon="envelope" icon:variant="outline" :placeholder="__('your.email@example.com')" />

                {{-- Email Verification Status --}}
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <flux:callout variant="warning" icon="exclamation-triangle" class="mt-3">
                        <div class="space-y-2">
                            <flux:text>
                                {{ __('Your email address is unverified.') }}
                            </flux:text>

                            <flux:button variant="outline" size="sm" icon="paper-airplane" icon:variant="outline"
                                wire:click.prevent="resendVerificationNotification">
                                {{ __('Resend Verification Email') }}
                            </flux:button>

                            @if (session('status') === 'verification-link-sent')
                                <flux:text class="font-medium text-green-600 dark:text-green-400">
                                    <flux:icon.check-circle variant="solid" class="inline-block size-4 me-1" />
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </flux:text>
                            @endif
                        </div>
                    </flux:callout>
                @else
                    <flux:text variant="subtle" class="text-xs flex items-center gap-1">
                        <flux:icon.check-badge variant="solid" class="size-4 text-green-500" />
                        {{ __('Your email address is verified.') }}
                    </flux:text>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <flux:button variant="primary" type="submit" class="flex-1 sm:flex-none" icon="check"
                    icon:variant="solid">
                    {{ __('Save Changes') }}
                </flux:button>

                <x-action-message class="flex items-center gap-2" on="profile-updated">
                    <flux:badge color="lime" size="sm" class="animate-in fade-in zoom-in duration-300">
                        <flux:icon.check-circle variant="solid" class="size-3" />
                        {{ __('Saved successfully!') }}
                    </flux:badge>
                </x-action-message>
            </div>

            {{-- Info Box --}}
            <flux:callout variant="info" icon="information-circle"
                class="mt-6 bg-primary-50 dark:bg-primary-950/20 border-primary-200 dark:border-primary-900/50">
                <flux:text class="text-sm text-primary-700 dark:text-primary-300">
                    {{ __('Want to change your password or enable two-factor authentication? Visit the respective sections below.') }}
                </flux:text>
            </flux:callout>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>