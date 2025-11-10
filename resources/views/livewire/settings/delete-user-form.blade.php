<section class="mt-10 space-y-6">
    {{-- Danger Zone Container --}}
    <div class="p-6 space-y-6 border-2 shadow-lg rounded-xl border-red-200 dark:border-red-900/50 bg-red-50/30 dark:bg-red-950/20">
        
        {{-- Danger Zone Header --}}
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/50">
                    <flux:icon.exclamation-triangle variant="solid" class="text-red-600 dark:text-red-400 size-6"/>
                </div>
                <div>
                    <flux:heading size="lg" class="text-red-700 dark:text-red-300">{{ __('Danger Zone') }}</flux:heading>
                    <flux:subheading class="text-sm text-red-600 dark:text-red-400">{{ __('Irreversible actions') }}</flux:subheading>
                </div>
            </div>
            
            {{-- Warning Callout --}}
            <flux:callout variant="danger" icon="shield-exclamation">
                <div class="space-y-2">
                    <p class="font-semibold">{{ __('Warning: This action is permanent and cannot be undone!') }}</p>
                    <p class="text-sm">{{ __('Deleting your account will permanently remove all your data from our servers. This includes:') }}</p>
                </div>
            </flux:callout>

            {{-- What Will Be Deleted List --}}
            <div class="p-4 space-y-3 border-2 border-red-200 rounded-lg bg-white dark:bg-zinc-900 dark:border-red-900/50">
                <flux:text class="font-medium text-red-700 dark:text-red-300">{{ __('What will be deleted:') }}</flux:text>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2 text-sm">
                        <flux:icon.x-circle variant="solid" class="mt-0.5 text-red-500 size-4 flex-shrink-0"/>
                        <span>{{ __('Your profile information and settings') }}</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <flux:icon.x-circle variant="solid" class="mt-0.5 text-red-500 size-4 flex-shrink-0"/>
                        <span>{{ __('All your saved content and preferences') }}</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <flux:icon.x-circle variant="solid" class="mt-0.5 text-red-500 size-4 flex-shrink-0"/>
                        <span>{{ __('Your verification history and feedback') }}</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <flux:icon.x-circle variant="solid" class="mt-0.5 text-red-500 size-4 flex-shrink-0"/>
                        <span>{{ __('Access to your account - you will be logged out immediately') }}</span>
                    </li>
                </ul>
            </div>

            {{-- Alternative Actions Suggestion --}}
            <flux:callout variant="info" icon="information-circle">
                <div class="space-y-2 text-sm">
                    <p class="font-medium">{{ __('Consider these alternatives:') }}</p>
                    <ul class="space-y-1 ms-4 list-disc list-inside">
                        <li>{{ __('Change your password if security is your concern') }}</li>
                        <li>{{ __('Update your email if you want a fresh start') }}</li>
                        <li>{{ __('Contact support if you\'re experiencing issues') }}</li>
                    </ul>
                </div>
            </flux:callout>
        </div>

        {{-- Delete Button --}}
        <flux:modal.trigger name="confirm-user-deletion">
            <flux:button 
                variant="danger" 
                icon="trash"
                icon:variant="solid"
                x-data="" 
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="w-full sm:w-auto"
            >
                {{ __('Delete My Account Permanently') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Confirmation Modal --}}
    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-2xl">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            
            {{-- Modal Header with Icon --}}
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/50">
                        <flux:icon.exclamation-triangle variant="solid" class="text-red-600 dark:text-red-400 size-7"/>
                    </div>
                    <flux:heading size="lg" class="text-red-700 dark:text-red-300">{{ __('Delete Account') }}</flux:heading>
                </div>
                
                <flux:subheading class="text-base">
                    {{ __('Are you absolutely sure you want to delete your account?') }}
                </flux:subheading>
                
                {{-- Final Warning --}}
                <flux:callout variant="danger" icon="shield-exclamation">
                    <p class="text-sm font-medium">
                        {{ __('This will permanently delete all your data. This action cannot be undone, recovered, or reversed.') }}
                    </p>
                </flux:callout>
            </div>

            {{-- What Happens Next --}}
            <div class="p-4 space-y-3 border rounded-lg bg-zinc-50 dark:bg-zinc-800/50 border-zinc-200 dark:border-zinc-700">
                <flux:text class="font-medium">{{ __('What happens after deletion:') }}</flux:text>
                <ol class="space-y-2 text-sm ms-4 list-decimal list-inside text-zinc-600 dark:text-zinc-400">
                    <li>{{ __('You will be immediately logged out from all devices') }}</li>
                    <li>{{ __('Your data will be permanently removed within 24 hours') }}</li>
                    <li>{{ __('You cannot create a new account with the same email for 30 days') }}</li>
                    <li>{{ __('Any ongoing processes or verifications will be cancelled') }}</li>
                </ol>
            </div>

            {{-- Password Confirmation --}}
            <div>
                <flux:input 
                    wire:model="password" 
                    :label="__('Enter your password to confirm')" 
                    type="password"
                    icon="lock-closed"
                    icon:variant="outline"
                    :placeholder="__('Your current password')"
                />
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end sm:space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button 
                        variant="ghost"
                        icon="arrow-left"
                        icon:variant="outline"
                        class="w-full sm:w-auto"
                    >
                        {{ __('Cancel - Keep My Account') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button 
                    variant="danger" 
                    type="submit"
                    icon="trash"
                    icon:variant="solid"
                    class="w-full sm:w-auto"
                >
                    {{ __('Yes, Delete My Account Forever') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
