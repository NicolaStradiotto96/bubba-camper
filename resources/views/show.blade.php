<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 flex items-center min-h-[calc(100vh-160px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- BACK --}}
            <div class="flex items-center justify-center lg:justify-start">
                <a href="{{ route('index') }}" wire:navigate
                    class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 ">
                    <i
                        class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
                    {{ __('Torna indietro') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <div class="flex flex-col justify-center">
                    {{-- IMAGES --}}
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
                        class="relative overflow-hidden bg-white dark:bg-gray-900 shadow-xl rounded-xl group border border-gray-300 dark:border-gray-700">

                        <div class="relative h-64 sm:h-80 md:h-[500px] w-full">
                            <template x-for="(slide, index) in slides" :key="index">
                                <div x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0">
                                    <img :src="'/storage/' + slide" @click="openModal('/storage/' + slide)"
                                        class="w-full h-full object-cover cursor-zoom-in" alt="Dettaglio Camper">
                                </div>
                            </template>
                        </div>

                        <template x-if="slides.length > 1">
                            <div>
                                <button @click="prev()"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/75 p-2 rounded-full text-amber-500 hover:bg-gray-400/50 hover:dark:bg-gray-900 transition opacity-50 group-hover:opacity-100">
                                    <i class="fa-solid fa-chevron-left text-lg"></i>
                                </button>
                                <button @click="next()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/75 p-2 rounded-full text-amber-500 hover:bg-gray-400/50 hover:dark:bg-gray-900 transition opacity-50 group-hover:opacity-100">
                                    <i class="fa-solid fa-chevron-right text-lg"></i>
                                </button>
                            </div>
                        </template>

                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index"
                                    :class="activeSlide === index ? 'bg-amber-500 w-6' : 'bg-gray-600/50 dark:bg-white/50 w-2'"
                                    class="border border-gray-300 dark:border-gray-700 h-2 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>

                        {{-- MODAL --}}
                        <div x-show="showModal" x-transition.opacity
                            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4"
                            @keydown.escape.window="closeModal()">

                            <button @click="closeModal()"
                                class="absolute top-6 right-6 text-white hover:text-amber-500 transition">
                                <i class="fa-solid fa-xmark text-3xl"></i>
                            </button>

                            <img :src="imgModalSrc" @click.away="closeModal()"
                                class="max-w-full max-h-full border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-2xl object-contain">
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div x-data="{ expanded: false }" class="mt-4 text-center lg:text-left">
                        <p
                            class="text-md text-gray-600 dark:text-gray-400 text-center leading-relaxed transition-all duration-300">
                            {{ $camper->description }}
                        </p>

                        <div class="flex flex-col justify-center items-center mt-2 space-y-2">
                            <button id="btn-description" data-name="{{ $camper->name }}"
                                data-description="{{ $camper->description }}"
                                data-full-description="{{ $camper->full_description }}"
                                class="relative block text-sm font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider group transition-colors duration-300 focus:outline-none">

                                {{ __('Descrizione ed equipaggiamento') }}

                                <span
                                    class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-600 dark:bg-amber-500 transition-all duration-300 group-hover:w-full"></span>
                            </button>

                            <button id="btn-description" data-name="{{ $camper->name }}"
                                data-description="{{ $camper->description }}"
                                data-full-description="{{ $camper->full_description }}"
                                class="relative block text-sm font-bold text-amber-600 dark:text-amber-500 uppercase tracking-wider group transition-colors duration-300 focus:outline-none">

                                {{ __('Cauzione e annullamento') }}

                                <span
                                    class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-600 dark:bg-amber-500 transition-all duration-300 group-hover:w-full"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DETAILS --}}
                <div class="flex flex-col justify-center">

                    {{-- Name --}}
                    <div class="text-center lg:text-left">
                        <h1
                            class="text-4xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter text-center">
                            {{ $camper->name }}
                        </h1>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-4">

                        {{-- Seats --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">Posti
                                Viaggio</span>
                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-users text-amber-500  mr-1.5 text-lg"></i>
                                <span
                                    class="text-lg font-bold text-gray-900 dark:text-white">{{ $camper->seats }}</span>
                            </div>
                        </div>

                        {{-- Beds --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">Posti
                                Letto</span>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-bed text-amber-500  mr-1.5 text-lg"></i>
                                <span
                                    class="text-lg font-bold text-gray-900 dark:text-white">{{ $camper->beds }}</span>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">Tariffa
                                Attuale</span>
                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-money-bill-wave text-amber-500  mr-1.5 text-lg"></i>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $camper->getPriceForDate() }}€/gg<span
                                        class="font-normal text-gray-400">*</span>
                                </span>
                            </div>
                        </div>

                        {{-- Driver License --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span
                                class="block text-sm text-gray-400 uppercase font-bold tracking-wider">Requisiti</span>
                            <div
                                class="flex items-center mt-1 text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-id-card text-amber-500 mr-1.5 text-lg"></i>
                                Patente B
                            </div>
                        </div>

                        {{-- Animals --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">Animali a
                                Bordo</span>
                            <div
                                class="flex items-center mt-1 text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-dog text-amber-500 mr-1.5 text-lg"></i>
                                Ammessi
                            </div>
                        </div>

                        {{-- Abroad --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">Viaggi
                                all'Estero</span>
                            <div
                                class="flex items-center mt-1 text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-earth-europe text-amber-500 mr-1.5 text-lg"></i>
                                Autorizzati
                            </div>
                        </div>

                        {{-- Insurance --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">Copertura
                                Danni</span>
                            <div
                                class="flex items-center mt-1 text-lg font-black text-green-600 dark:text-green-500 uppercase">
                                <i
                                    class="fa-solid fa-shield-halved text-green-600 dark:text-green-500 mr-1.5 text-lg"></i>
                                Kasko
                            </div>
                        </div>

                        {{-- Availability --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span
                                class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">Stato</span>
                            @if ($camper->is_active)
                                <span class="text-lg font-bold text-green-600 dark:text-green-500 uppercase mt-1">
                                    <i
                                        class="fa-solid fa-circle-check text-green-600 dark:text-green-500 mr-1.5 text-lg"></i>
                                    Disponibile
                                </span>
                            @else
                                <span class="text-lg font-bold text-red-600 dark:text-red-500 uppercase mt-1">
                                    <i
                                        class="fa-solid fa-circle-xmark text-red-600 dark:text-red-500  mr-1.5 text-lg"></i>
                                    Non disponibile
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- PDF --}}
                    @if ($camper->pdf_path ?? true)
                        <div class="mt-6">
                            <a href="{{ isset($camper->pdf_path) ? asset('storage/' . $camper->pdf_path) : '#' }}"
                                target="_blank"
                                class="w-full bg-white dark:bg-gray-800 hover:bg-gray-100 hover:dark:bg-gray-700 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:text-black dark:hover:white text-sm font-black tracking-widest uppercase py-3 px-4 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all duration-300 group">
                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                {{ __('Scarica Scheda Tecnica') }}
                            </a>
                        </div>
                    @endif

                    <p class="mt-4 text-xs text-gray-400 italic text-center lg:text-left">
                        * Il prezzo indicato si riferisce al mese corrente. Le tariffe variano in base alla stagione
                        (Bassa, Media, Alta). Seleziona le date nel modulo di prenotazione per il calcolo esatto.
                    </p>

                    <div class="mt-8">
                        <x-primary-anchor href="{{ route('booking.show', $camper->slug) }}" wire:navigate
                            class="w-full py-4 text-lg flex justify-center uppercase tracking-widest font-bold">
                            {{ __('Prenota questo camper') }}
                        </x-primary-anchor>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
