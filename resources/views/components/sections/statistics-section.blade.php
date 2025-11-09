<section class="py-24 bg-gradient-to-br bg-primary relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0"
            style="background-image: radial-gradient(circle, white 2px, transparent 2px); background-size: 40px 40px;">
        </div>
    </div>

    <div class="absolute top-20 left-10 w-72 h-72 bg-[#d4b896]/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-20">
            <h2 class="text-5xl md:text-6xl font-bold text-white mb-6 tracking-tight">
                {{ __('home.statistics_title') }}
            </h2>
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="w-12 h-1 bg-[#d4b896] rounded-full"></div>
                <div class="w-3 h-3 bg-[#d4b896] rounded-full"></div>
                <div class="w-12 h-1 bg-[#d4b896] rounded-full"></div>
            </div>
            <p class="text-white/80 text-xl max-w-3xl mx-auto leading-relaxed">{{ __('home.statistics_desc') }}</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6 lg:gap-8 max-w-7xl mx-auto">
            @livewire('statistics-card', ['value' => '620', 'label' => __('home.news_verified')])
            @livewire('statistics-card', ['value' => '89', 'label' => __('home.avg_accuracy'), 'isPercentage' => true])
            @livewire('statistics-card', ['value' => '1250', 'label' => __('home.registered_users')])
            @livewire('statistics-card', ['value' => '5', 'label' => __('home.legal_sources')])
        </div>
    </div>
</section>