<section id="contact" class="py-20 bg-[#f8f6f0]">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-bold text-center text-primary mb-6">{{ __('home.contact_title') }}
        </h2>
        <p class="text-center text-gray-600 mb-16 text-lg">
            {{ __('home.contact_desc') }}
        </p>

        <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl p-10">
            <livewire:forms.contact-us-form />
        </div>
    </div>
</section>