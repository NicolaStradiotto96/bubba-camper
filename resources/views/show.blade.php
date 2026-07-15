<x-app-layout title="{{ $camper->name }}">
    <div x-data="{
        openSpecs: false,
        openEquipment: false,
        openPolicies: false
    }"
        x-effect="document.body.style.overflow = (openSpecs || openEquipment || openPolicies) ? 'hidden' : ''"
        class="bg-gray-50 dark:bg-gray-900 flex items-center min-h-[calc(100vh-160px)]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-8">

            {{-- BACK --}}
            <div class="flex items-center justify-center lg:justify-start">
                <a href="{{ route('index') }}" wire:navigate
                    class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                    <i
                        class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
                    {{ __('Torna indietro') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <div class="flex flex-col justify-center">

                    {{-- AVAILABILITY --}}
                    <div
                        class="w-full text-center py-3 px-4 rounded-xl mb-4 border transition-all duration-300 {{ $camper->is_active ? 'bg-green-950/20 border-green-500/50 text-green-500' : 'bg-red-950/20 border-red-500/50 text-red-500' }}">
                        <p class="text-sm font-black tracking-widest uppercase flex items-center justify-center gap-2">
                            <span
                                class="w-2 h-2 rounded-full {{ $camper->is_active ? 'bg-green-500 animate-pulse' : 'bg-red-500 animate-pulse' }}"></span>
                            Stato: {{ $camper->is_active ? 'Disponibile' : 'Non Disponibile' }}
                        </p>
                    </div>

                    {{-- IMAGES --}}
                    <div x-data="{
                        activeSlide: 0,
                        touchStart: 0,
                        slides: {{ json_encode($camper->images ?? [$camper->image_path]) }},
                        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                        prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
                        init() {
                            GLightbox({ selector: '.glightbox' });
                        }
                    }" @touchstart="touchStart = $event.touches[0].clientX"
                        @touchend="
                        let touchEnd = $event.changedTouches[0].clientX;
                        if (touchStart - touchEnd > 50) next();
                        if (touchStart - touchEnd < -50) prev();
                    "
                        class="relative overflow-hidden bg-white dark:bg-gray-900 shadow-xl rounded-xl group border border-gray-300 dark:border-gray-700">

                        <div class="relative h-64 sm:h-80 md:h-[500px] w-full">
                            <template x-for="(slide, index) in slides" :key="index">
                                <div x-show="activeSlide === index"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute inset-0">

                                    <a :href="'/storage/' + slide" class="glightbox w-full h-full block">
                                        <img :src="'/storage/' + slide"
                                            class="w-full h-full object-cover cursor-zoom-in"
                                            alt="Noleggio camper {{ $camper->name }}">
                                    </a>
                                </div>
                            </template>
                        </div>

                        <template x-if="slides.length > 1">
                            <div>
                                <button @click="prev()"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/75 p-2 rounded-full text-amber-500 hover:bg-gray-400/50 hover:dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition opacity-50 group-hover:opacity-100">
                                    <i class="fa-solid fa-chevron-left text-lg"></i>
                                </button>
                                <button @click="next()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-300/50 dark:bg-gray-900/75 p-2 rounded-full text-amber-500 hover:bg-gray-400/50 hover:dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition opacity-50 group-hover:opacity-100">
                                    <i class="fa-solid fa-chevron-right text-lg"></i>
                                </button>
                            </div>
                        </template>

                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index"
                                    :class="activeSlide === index ? 'bg-amber-500 w-6' : 'bg-gray-600/50 dark:bg-white/50 w-2'"
                                    class="border border-gray-300 dark:border-gray-700 h-2 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-500 transition"></button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- DETAILS --}}
                <div class="flex flex-col justify-center">

                    {{-- Name --}}
                    <div class="text-center lg:text-left">
                        <h1 itemprop="name"
                            class="text-4xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter text-center">
                            {{ $camper->name }}
                        </h1>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-4">

                        {{-- Seats --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">
                                Posti Viaggio
                            </span>
                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-users text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                <span class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">
                                    {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Posti Viaggio')['value'] ?? 'N/D' }}
                                </span>
                            </div>
                        </div>

                        {{-- Beds --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">
                                Posti Letto
                            </span>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-bed text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                <span class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">
                                    {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Posti Letto')['value'] ?? 'N/D' }}
                                </span>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div itemprop="offers" itemscope itemtype="https://schema.org/Offer"
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-semibold tracking-wider">
                                Tariffa Attuale
                            </span>
                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-money-bill-wave text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                <span class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white">
                                    <meta itemprop="priceCurrency" content="EUR" />
                                    <span itemprop="price"
                                        content="{{ $camper->getPriceForDate() }}">{{ $camper->getPriceForDate() }}</span>€/gg
                                    <span class="font-normal text-gray-400">*</span>
                                </span>
                            </div>
                        </div>

                        {{-- Driver License --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span
                                class="block text-sm text-gray-400 uppercase font-bold tracking-wider">Requisiti</span>
                            <div
                                class="flex items-center mt-1 text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-id-card text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Requisiti')['value'] ?? 'N/D' }}
                            </div>
                        </div>

                        {{-- Abroad --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">
                                Viaggi all'Estero
                            </span>
                            <div
                                class="flex items-center mt-1 text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-earth-europe text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Viaggi all\'Estero')['value'] ?? 'N/D' }}
                            </div>
                        </div>

                        {{-- Insurance --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">
                                Copertura Danni
                            </span>
                            <div
                                class="flex items-center mt-1 text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-shield-halved text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Copertura Danni')['value'] ?? 'N/D' }}
                            </div>
                        </div>

                        {{-- Animals --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">
                                Animali a Bordo
                            </span>
                            <div
                                class="flex items-center mt-1 text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-dog text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Animali a Bordo')['value'] ?? 'N/D' }}
                            </div>
                        </div>

                        {{-- Smokers --}}
                        <div
                            class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-gray-700 flex flex-col items-center justify-center text-center overflow-hidden">
                            <span class="block text-sm text-gray-400 uppercase font-bold tracking-wider">
                                Fumatori
                            </span>
                            <div
                                class="flex items-center mt-1 text-sm sm:text-lg font-black text-gray-900 dark:text-white uppercase">
                                <i class="fa-solid fa-smoking text-amber-500 mr-1.5 text-sm sm:text-lg"></i>
                                {{ collect($camper->attributes['main']['Caratteristiche principali'] ?? [])->firstWhere('label', 'Fumatori')['value'] ?? 'N/D' }}
                            </div>
                        </div>

                    </div>

                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div class="flex w-full">
                                <button @click="openSpecs = true" type="button"
                                    class="w-full bg-white dark:bg-gray-800/50 hover:bg-gray-100 hover:dark:bg-gray-700 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white hover:text-black dark:hover:white text-xs font-black tracking-widest uppercase py-3 px-4 rounded-xl shadow-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-amber-500 transition group">
                                    <i class="fa-solid fa-gears text-green-500 text-lg"></i>
                                    {{ __('Caratteristiche tecniche') }}
                                </button>
                            </div>

                            <div class="flex w-full">
                                <button @click="openEquipment = true" type="button"
                                    class="w-full bg-white dark:bg-gray-800/50 hover:bg-gray-100 hover:dark:bg-gray-700 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white hover:text-black dark:hover:white text-xs font-black tracking-widest uppercase py-3 px-4 rounded-xl shadow-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-amber-500 transition group">
                                    <i class="fa-solid fa-toolbox text-green-500 text-lg"></i>
                                    {{ __('Equipaggiamento') }}
                                </button>
                            </div>

                            <div class="flex w-full">
                                <button @click="openPolicies = true" type="button"
                                    class="w-full bg-white dark:bg-gray-800/50 hover:bg-gray-100 hover:dark:bg-gray-700 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-white hover:text-black dark:hover:white text-xs font-black tracking-widest uppercase py-3 px-4 rounded-xl shadow-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-amber-500 transition group">
                                    <i class="fa-solid fa-hand-holding-dollar text-green-500 text-lg"></i>
                                    {{ __('Cauzione e annullamento') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-gray-400 italic text-center">
                        * Il prezzo indicato si riferisce al mese corrente. Le tariffe variano in base alla stagione
                        (Bassa, Media, Alta). Seleziona le date nel modulo di prenotazione per il calcolo esatto.
                    </p>

                    <div class="mt-8">
                        <x-primary-anchor href="{{ route('booking.show', $camper->slug) }}" wire:navigate
                            x-data="{ loading: false }" @click="loading = true"
                            x-bind:class="loading ? 'opacity-50 cursor-wait' : ''"
                            class="w-full py-4 text-lg flex justify-center uppercase tracking-widest font-bold">
                            {{ __('Prenota questo camper') }}
                        </x-primary-anchor>
                    </div>
                </div>
            </div>
        </div>

        {{-- SPECS MODAL --}}
        <div x-show="openSpecs" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="openSpecs = false" x-show="openSpecs"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white dark:bg-gray-900 w-full max-w-[960px] rounded-xl border-2 border-gray-200 dark:border-gray-800 p-6 shadow-2xl relative max-h-[90vh] flex flex-col">

                <button @click="openSpecs = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-4 right-4 z-50">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>

                <div class="w-full font-black text-center flex flex-col h-full overflow-hidden">
                    <div class="flex-shrink-0 pt-2">
                        <i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i>
                        <h2
                            class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3 mt-2">
                            {{ $camper->name }}</h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center font-medium"
                                itemprop="description">
                                {{ $camper->description }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 dark:text-gray-300 overflow-y-auto flex-grow pr-1 mt-2">
                        @php
                            $specsOrder = [
                                'Caratteristiche tecniche' => 'fa-gears',
                                'Autonomia' => 'fa-bolt',
                            ];
                        @endphp

                        @foreach ($specsOrder as $catName => $icon)
                            @if (isset($camper->attributes['specs'][$catName]))
                                <div class="py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <h4
                                        class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                        <i class="fa-solid {{ $icon }} text-lg text-amber-500 mr-1"></i>
                                        {{ $catName }}
                                    </h4>
                                    <div
                                        class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400 font-medium">
                                        @foreach ($camper->attributes['specs'][$catName] as $item)
                                            <div class="py-2">
                                                {{ $item['label'] }}
                                                @if (!empty($item['value']))
                                                    <span
                                                        class="text-white ml-1 font-bold">{{ $item['value'] }}</span>
                                                @else
                                                    <i class="fa-solid fa-check text-green-500 ml-1"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- EQUIPMENT MODAL --}}
        <div x-show="openEquipment" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="openEquipment = false" x-show="openEquipment"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white dark:bg-gray-900 w-full max-w-[960px] rounded-xl border-2 border-gray-200 dark:border-gray-800 p-6 shadow-2xl relative max-h-[90vh] flex flex-col">

                <button @click="openEquipment = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-4 right-4 z-50">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>

                <div class="w-full font-black text-center flex flex-col h-full overflow-hidden">
                    <div class="flex-shrink-0 pt-2">
                        <i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i>
                        <h2
                            class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3 mt-2">
                            {{ $camper->name }}</h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center font-medium">
                                {{ $camper->description }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 dark:text-gray-300 overflow-y-auto flex-grow pr-1 mt-2">
                        @php
                            $eqOrder = [
                                'Alla guida' => 'fa-map-location-dot',
                                'Vita a bordo' => 'fa-couch',
                                'Cucina / Dinette' => 'fa-utensils',
                                'Zona bagno' => 'fa-shower',
                                'Esterno' => 'fa-caravan',
                            ];
                        @endphp
                        @foreach ($eqOrder as $catName => $icon)
                            @if (isset($camper->attributes['equipment'][$catName]))
                                <div class="py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <h4
                                        class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                        <i class="fa-solid {{ $icon }} text-lg text-amber-500 mr-1"></i>
                                        {{ $catName }}
                                    </h4>
                                    <div
                                        class="grid grid-cols-2 md:grid-cols-3 justify-center items-center text-gray-600 dark:text-gray-400 font-medium">
                                        @foreach ($camper->attributes['equipment'][$catName] as $item)
                                            <div class="py-2">
                                                {{ $item['label'] }}
                                                @if (!empty($item['value']))
                                                    <span
                                                        class="text-white ml-1 font-bold">{{ $item['value'] }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- POLICIES MODAL --}}
        <div x-show="openPolicies" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="openPolicies = false" x-show="openPolicies"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white dark:bg-gray-900 w-full max-w-[960px] rounded-xl border-2 border-gray-200 dark:border-gray-800 p-6 shadow-2xl relative max-h-[90vh] flex flex-col">

                <button @click="openPolicies = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 absolute top-4 right-4 z-50">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>

                <div class="w-full font-black text-center flex flex-col h-full overflow-hidden">
                    <div class="flex-shrink-0 pt-2">
                        <i class="fa-solid fa-van-shuttle text-amber-500" style="font-size: 5rem;"></i>
                        <h2
                            class="text-4xl text-gray-900 dark:text-white uppercase tracking-tighter text-center mb-3 mt-2">
                            {{ $camper->name }}</h2>
                        <div class="text-base leading-relaxed px-1 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-amber-600 dark:text-amber-500 text-center font-medium">
                                {{ $camper->description }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 dark:text-gray-300 overflow-y-auto flex-grow pr-1">

                        {{-- Deposit --}}
                        <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4 class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2">
                                <i class="fa-solid fa-hand-holding-dollar text-lg text-amber-500 mr-1"></i> Cauzione
                            </h4>
                            <div
                                class="grid grid-cols-2 justify-center items-center text-gray-600 dark:text-gray-400 font-medium">
                                @if (isset($camper->attributes['policies']['Cauzione']))
                                    @foreach ($camper->attributes['policies']['Cauzione'] as $item)
                                        <div class="py-2">{{ $item['label'] }} <span
                                                class="text-white ml-1 font-bold">{{ $item['value'] }}</span></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Hours --}}
                        <div class="py-3 border-b border-gray-100 dark:border-gray-700">
                            <h4
                                class="text-lg text-gray-900 dark:text-white uppercase tracking-widest mb-3 gap-2 flex items-center justify-center font-black">
                                <i class="fa-solid fa-clock text-lg text-amber-500 mr-1"></i> Ritiro e Riconsegna
                            </h4>
                            <div
                                class="grid grid-cols-1 justify-center items-center text-gray-600 dark:text-gray-400 font-medium">
                                <div class="py-2">
                                    Lun - Ven
                                    <time datetime="10:00-13:00/16:00-20:00" class="text-white ml-1 font-bold">
                                        10:00 - 13:00 / 16:00 - 20:00
                                    </time>
                                </div>
                                <p
                                    class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-1 text-center italic w-full block">
                                    * Eventuali variazioni di orario vanno concordate preventivamente.
                                </p>
                            </div>

                            {{-- Penalty --}}
                            <div class="py-3 last:border-0">
                                <h4
                                    class="text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest mb-3 gap-2 font-black">
                                    <i class="fa-solid fa-calendar-xmark text-lg text-amber-500 mr-1"></i> Condizioni
                                    di
                                    annullamento
                                </h4>

                                <div class="w-full font-sans p-3 text-left block">
                                    <div class="relative px-4 my-3">
                                        <div
                                            class="absolute top-1/2 left-8 right-8 h-1 bg-gray-300 dark:bg-gray-700 rounded-full block z-0 -translate-y-1/2">
                                        </div>
                                        <div class="relative flex justify-between items-center z-10 w-full">
                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span
                                                    class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-2 px-1">Oltre
                                                    61 gg</span>
                                                <div
                                                    class="w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 shadow bg-green-500">
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-green-500 mt-2 block h-8 px-1">Penale
                                                    10%</span>
                                            </div>
                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span
                                                    class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-2 px-1">Da
                                                    60 a 31 gg</span>
                                                <div
                                                    class="w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 shadow bg-yellow-500">
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-yellow-500 mt-2 block h-8 px-1">Penale
                                                    50%</span>
                                            </div>
                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span
                                                    class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-2 px-1">Da
                                                    30 a 11 gg</span>
                                                <div
                                                    class="w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 shadow bg-amber-500">
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-amber-500 mt-2 block h-8 px-1">Penale
                                                    80%</span>
                                            </div>
                                            <div class="flex flex-col items-center text-center w-1/4">
                                                <span
                                                    class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider block h-8 mb-2 px-1">Meno
                                                    di 10 gg</span>
                                                <div
                                                    class="w-5 h-5 rounded-full border-4 border-white dark:border-gray-900 shadow bg-red-500">
                                                </div>
                                                <span class="text-xs font-bold text-red-500 mt-2 block h-8 px-1">Penale
                                                    100%</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-4 gap-3 pt-4 text-xs md:text-sm text-center font-sans font-medium">
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800/40 rounded-xl border-b-2 border-green-500">
                                            <h5
                                                class="font-black uppercase tracking-wider text-xs text-green-500 mb-1">
                                                Preavviso sopra i 61 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Trattenuta del
                                                <span class="font-black text-gray-900 dark:text-white">10%</span>
                                                dell'importo.
                                            </p>
                                        </div>
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800/40 rounded-xl border-b-2 border-yellow-500">
                                            <h5
                                                class="font-black uppercase tracking-wider text-xs text-yellow-500 mb-1">
                                                Preavviso tra 60 e 31 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Trattenuta del
                                                <span class="font-black text-gray-900 dark:text-white">50%</span>
                                                dell'importo.
                                            </p>
                                        </div>
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800/40 rounded-xl border-b-2 border-amber-500">
                                            <h5
                                                class="font-black uppercase tracking-wider text-xs text-amber-500 mb-1">
                                                Preavviso tra 30 e 11 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Trattenuta del
                                                <span class="font-black text-gray-900 dark:text-white">80%</span>
                                                dell'importo.
                                            </p>
                                        </div>
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800/40 rounded-xl border-b-2 border-red-500">
                                            <h5 class="font-black uppercase tracking-wider text-xs text-red-500 mb-1">
                                                Preavviso sotto i 10 giorni</h5>
                                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">Trattenuta del
                                                <span class="font-black text-gray-900 dark:text-white">100%</span>
                                                dell'importo.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- SEO --}}
        @php
            $productSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $camper->name,
                'image' => asset('storage/' . ($camper->images[0] ?? $camper->image_path)),
                'description' => $camper->description,
                'brand' => ['@type' => 'Brand', 'name' => config('app.name')],
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'EUR',
                    'price' => $camper->getPriceForDate(), // Assicurati che ritorni solo il numero
                    'availability' => $camper->is_active
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                    'url' => url()->current(),
                ],
            ];
        @endphp

        <script type="application/ld+json">
    {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app-layout>
