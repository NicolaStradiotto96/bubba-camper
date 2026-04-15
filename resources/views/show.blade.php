<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back --}}
            <a href="{{ route('index') }}" wire:navigate
                class="relative inline-block mb-6 text-amber-600 font-medium group transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Torna indietro') }}
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                {{-- Images --}}
                <div x-data="{
                    activeSlide: 0,
                    touchStart: 0,
                    showModal: false,
                    imgModalSrc: '',
                    slides: {{ json_encode($camper->images ?? [$camper->image_path]) }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
                    openModal(src) {
                        this.imgModalSrc = src;
                        this.showModal = true;
                        document.body.style.overflow = 'hidden';
                    },
                    closeModal() {
                        this.showModal = false;
                        document.body.style.overflow = 'auto';
                    }
                }" @touchstart="touchStart = $event.touches[0].clientX"
                    @touchend="
                        let touchEnd = $event.changedTouches[0].clientX;
                        if (touchStart - touchEnd > 50) next();
                        if (touchStart - touchEnd < -50) prev();
                    "
                    style="touch-action: pan-y;"
                    class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-xl group">

                    <div class="relative h-64 sm:h-80 md:h-[500px] w-full">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 transform translate-x-8"
                                x-transition:enter-end="opacity-100 transform translate-x-0" class="absolute inset-0">
                                <img :src="'/storage/' + slide" @click="openModal('/storage/' + slide)"
                                    class="w-full h-full object-contain cursor-zoom-in" alt="Dettaglio Camper">
                            </div>
                        </template>
                    </div>

                    <template x-if="slides.length > 1">
                        <div>
                            <button @click="prev()"
                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/50 p-2 rounded-full text-amber-600 hover:bg-gray-400/50 hover:dark:bg-gray-900 transition opacity-50 group-hover:opacity-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button @click="next()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/50 p-2 rounded-full text-amber-600 hover:bg-gray-400/50 hover:dark:bg-gray-900 transition opacity-50 group-hover:opacity-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index"
                                :class="activeSlide === index ? 'bg-amber-600 w-6' : 'bg-gray-600/50 dark:bg-white/50 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>

                    {{-- Modal --}}
                    <div x-show="showModal" x-transition.opacity
                        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4"
                        @keydown.escape.window="closeModal()">

                        <button @click="closeModal()"
                            class="absolute top-6 right-6 text-white hover:text-amber-500 transition">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <img :src="imgModalSrc" @click.away="closeModal()"
                            class="max-w-full max-h-full border bg-black/90 rounded-lg shadow-2xl object-contain">
                    </div>
                </div>

                {{-- Details --}}
                <div class="flex flex-col justify-center text-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter">
                        {{ $camper->name }}
                    </h1>

                    <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 text-center">
                        {{ $camper->description }}
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-6">
                        <div
                            class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="block text-sm text-gray-500 uppercase">Posti Letto</span>
                            <span class="text-2xl font-bold text-amber-600">{{ $camper->beds }}</span>
                        </div>
                        <div
                            class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <span class="block text-sm text-gray-500 uppercase">Prezzo al giorno</span>
                            <span class="text-2xl font-bold text-amber-600">{{ $camper->price_per_day }}€</span>
                        </div>
                    </div>

                    <div class="mt-10">
                        <x-primary-anchor href="{{ route('booking.show', $camper->slug) }}" wire:navigate
                            class="w-full py-4 text-lg flex justify-center">
                            {{ __('Noleggia Ora') }}
                        </x-primary-anchor>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
