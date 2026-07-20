@props(['camper'])

<div itemscope itemtype="https://schema.org/Product"
    class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-md overflow-hidden border border-gray-300 dark:border-gray-700 flex flex-col h-full">
    <meta itemprop="name" content="{{ $camper->name }}">

    <div itemprop="offers" itemscope itemtype="https://schema.org/Offer">
        <meta itemprop="priceCurrency" content="EUR" />
        <meta itemprop="price" content="{{ $camper->getPriceForDate() }}" />
        <link itemprop="availability"
            href="{{ $camper->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}" />
    </div>

    <div class="relative h-64 w-full flex-shrink-0 bg-gray-100 dark:bg-gray-900">
        <img src="{{ asset('storage/' . $camper->image_path) }}" alt="{{ $camper->name }}" loading="lazy"
            class="w-full h-full object-cover object-center text-white">

        @auth
            @if (auth()->user()->is_admin)
                <a href="{{ route('camper.edit', $camper) }}" wire:navigate
                    class="absolute top-4 left-4 z-10 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-[2rem] font-black text-xs uppercase tracking-wider shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 transition flex items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> Modifica
                </a>
            @endif
        @endauth

        <div
            class="absolute top-4 right-4 z-10 px-3 py-1.5 bg-amber-600 text-white rounded-[2rem] font-black text-xs tracking-wider shadow-lg">
            {{ $camper->getPriceForDate() }}€ / gg
        </div>
    </div>

    <div class="p-6 flex flex-col flex-grow justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                {{ $camper->name }}
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm leading-relaxed line-clamp-2 min-h-[3rem]">
                {{ $camper->description }}
            </p>
        </div>

        <div class="mt-6 flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-6 gap-3">
            <p
                class="text-sm font-bold tracking-widest uppercase flex items-center justify-center gap-2 {{ $camper->is_active ? 'text-green-500' : 'text-red-500' }}">
                <span
                    class="w-2 h-2 mb-0.5 rounded-full {{ $camper->is_active ? 'bg-green-500 animate-pulse' : 'bg-red-500 animate-pulse' }}">
                </span>
                {{ $camper->is_active ? 'Disponibile' : 'Non Disponibile' }}
            </p>

            <x-primary-anchor href="{{ route('show', $camper) }}"
                aria-label="Vedi i dettagli del camper {{ $camper->name }}" wire:navigate x-data="{ loading: false }"
                @click="loading = true" x-bind:class="loading ? 'opacity-50 cursor-wait' : ''">
                {{ __('Vedi Dettagli') }}
            </x-primary-anchor>
        </div>
    </div>
</div>
