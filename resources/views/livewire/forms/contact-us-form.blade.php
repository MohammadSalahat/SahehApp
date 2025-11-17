<div class="">
    @if ($this->submitted)
        <div class="flex items-start justify-center gap-4 flex-col">
            <div class="w-full bg-primary/10 border-2 border-primary/40 text-primary px-4 py-3 rounded-lg relative"
                role="alert">
                <strong class="font-bold">{{ __('contact.success_title') }}</strong>
                <span class="block sm:inline">{{ __('contact.success_message') }}</span>
            </div>

            <button type="button" wire:click="submitAnother"
                class="bg-primary hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg">
                {{ __('contact.submit_another') }}
            </button>
        </div>

    @else
        <form class="space-y-8" wire:submit.prevent="submit">
            @csrf
            <div class="flex flex-col gap-4">
                <div class="mb-8">
                    <label for="full_name"
                        class="block text-gray-700 font-bold mb-3 text-lg">{{ __('contact.full_name') }}</label>
                    <input type="text"
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-primary/20 @error('full_name') border-red-500 @enderror"
                        placeholder="{{ __('contact.full_name_placeholder') }}" name="full_name" id="full_name"
                        wire:model.blur="full_name">
                    @error('full_name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="email" class="block text-gray-700 font-bold mb-3 text-lg">{{ __('contact.email') }}</label>
                    <input type="email"
                        class="w-full px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none text-lg transition-all duration-300 focus:ring-2 focus:ring-primary/20 @error('email') border-red-500 @enderror"
                        placeholder="{{ __('contact.email_placeholder') }}" name="email" id="email" wire:model.blur="email">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="message"
                        class="block text-gray-700 font-bold mb-3 text-lg">{{ __('contact.message') }}</label>
                    <textarea
                        class="w-full h-40 px-6 py-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none resize-none text-lg transition-all duration-300 focus:ring-2 focus:ring-primary/20 @error('message') border-red-500 @enderror"
                        placeholder="{{ __('contact.message_placeholder') }}" name="message" id="message"
                        wire:model.blur="message"></textarea>
                    @error('message')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button aria-label="submit" wire:loading.attr="disabled" wire:target="submit" type="submit"
                    class="w-fit self-end px-6 py-4 hover:bg-primary-300 bg-primary text-white rounded-md cursor-pointer flex items-center space-x-2">
                    <div wire:loading wire:target="submit" class="inline-block">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold">{{ __('contact.submit_button') }}</span>
                </button>
            </div>
        </form>
    @endif

    <!-- Toast notifications that appear fixed at the bottom right -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
            class="fixed top-16 end-4 bg-white shadow-lg rounded-md border-s-4 border-green-500 max-w-sm px-4 py-3 z-[1000] transform transition-all duration-300"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ms-3 flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ __('contact.toast_success_title') }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ session('success') }}</p>
                </div>
                <div class="ms-4 flex-shrink-0 flex">
                    <button aria-label="hide" @click="show = false"
                        class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 111.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
            class="fixed top-16 end-4 bg-white shadow-lg rounded-md border-s-4 border-red-500 max-w-sm px-4 py-3 z-[1000] transform transition-all duration-300"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 101.414 1.414L10 11.414l1.293 1.293a1 1 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ms-3 flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ __('contact.toast_error_title') }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ session('error') }}</p>
                </div>
                <div class="ms-4 flex-shrink-0 flex">
                    <button aria-label="hide" @click="show = false"
                        class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 111.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>