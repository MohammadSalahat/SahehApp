<div class="group" x-data="{ 
        currentValue: 0, 
        targetValue: {{ $targetValue }}, 
        duration: {{ $duration }},
        hasAnimated: false
     }" x-init="
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    animateCounter();
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe($el);
        
        function animateCounter() {
            const start = Date.now();
            const startValue = 0;
            const endValue = targetValue;
            
            function update() {
                const elapsed = Date.now() - start;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function for smoother animation
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                
                currentValue = Math.floor(startValue + (endValue - startValue) * easeOutQuart);
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    currentValue = endValue;
                }
            }
            
            requestAnimationFrame(update);
        }
     ">
    <div
        class="relative bg-white/5 backdrop-blur-xl rounded-3xl p-10 hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 border border-white/10 hover:border-white/20 shadow-2xl hover:shadow-[#d4b896]/20">
        <div
            class="absolute top-0 right-0 w-24 h-24 bg-[#d4b896]/20 rounded-full blur-2xl group-hover:bg-[#d4b896]/30 transition-all duration-500">
        </div>
        <div class="relative">
            @if($isPercentage)
                <div class="flex items-start justify-center mb-4 w-fit">
                    <span class="text-6xl md:text-7xl font-black text-white" x-text="currentValue"></span>
                    <span class="text-4xl font-black text-[#d4b896] mt-2">%</span>
                </div>
            @else
                <div class="text-6xl md:text-7xl font-black mb-4 text-white" x-text="currentValue"></div>
            @endif

            <div class="h-1 w-16 bg-gradient-to-r from-[#d4b896] to-transparent mb-4 rounded-full"></div>
            <div class="text-white/90 text-lg font-semibold">{{ $label }}</div>
        </div>
    </div>
</div>