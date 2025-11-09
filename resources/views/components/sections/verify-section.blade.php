<section id="verify" class="py-20 bg-[#f8f6f0]">
    <!-- Include Interactive Verification Loader -->
    <x-verification-loader />

    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-bold text-center text-[#4a6b5a] mb-6">
            {{ __('home.verify_section_title') }}
        </h2>
        <p class="text-center text-gray-600 mb-16 text-lg">
            {{ __('home.verify_section_desc') }}
        </p>

        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>

            <form id="verification-form" action="{{ route('verify') }}" method="POST"
                onsubmit="handleFormSubmit(event)">
                @csrf
                <div class="mb-6 flex items-center gap-3 text-[#4a6b5a]">
                    <svg class="w-8 h-8 pulse-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-bold text-xl">{{ __('home.news_text_label') }}</span>
                </div>

                @error('content')
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        {{ $message }}
                    </div>
                @enderror

                <textarea id="news-content" name="content" required minlength="20"
                    class="w-full h-48 p-6 border-2 border-gray-200 rounded-xl focus:border-[#4a6b5a] focus:outline-none resize-none text-lg leading-relaxed transition-all duration-300 focus:shadow-lg focus:ring-2 focus:ring-[#4a6b5a]/20"
                    placeholder="{{ __('home.news_text_placeholder') }}">{{ old('content') }}</textarea>

                <button type="submit" id="submit-btn"
                    class="bg-gradient-to-r from-[#4a6b5a] to-[#5a7a6a] text-white w-full mt-8 py-5 rounded-xl text-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    {{ __('home.verify_news') }}
                </button>
            </form>

            <script>
                function handleFormSubmit(event) {
                    event.preventDefault();

                    const content = document.getElementById('news-content').value.trim();

                    // Validate content length
                    if (content.length < 20) {
                        // Show error in a nicer way
                        const textarea = document.getElementById('news-content');
                        textarea.style.borderColor = '#ef4444';
                        textarea.focus();

                        // Create or update error message
                        let errorMsg = document.getElementById('content-error');
                        if (!errorMsg) {
                            errorMsg = document.createElement('div');
                            errorMsg.id = 'content-error';
                            errorMsg.className = 'mt-2 text-red-600 text-sm font-medium';
                            textarea.parentNode.insertBefore(errorMsg, textarea.nextSibling);
                        }
                        errorMsg.textContent = '{{ __("home.validation_min_length") }}';

                        return false;
                    }

                    // Remove error styling if present
                    const textarea = document.getElementById('news-content');
                    textarea.style.borderColor = '';
                    const errorMsg = document.getElementById('content-error');
                    if (errorMsg) {
                        errorMsg.remove();
                    }

                    // Show the loader
                    if (window.VerificationLoader) {
                        window.VerificationLoader.show();
                    }

                    // Submit the form after showing loader
                    setTimeout(() => {
                        event.target.submit();
                    }, 300);
                }
            </script>
        </div>
    </div>
</section>