@props(['camper'])

<div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-300 dark:border-gray-700 flex flex-col h-full">
    <div class="relative h-64 w-full flex-shrink-0 bg-gray-100 dark:bg-gray-900">
        <img src="{{ asset('storage/' . $camper->image_path) }}" alt="{{ $camper->name }}"
            class="w-full h-full object-cover object-center text-white">

        @auth
            @if (auth()->user()->is_admin)
                <a href="{{ route('camper.edit', $camper) }}" wire:navigate
                    class="absolute top-4 left-4 z-10 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-md font-black text-xs uppercase tracking-wider shadow-lg transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> Modifica
                </a>
            @endif
        @endauth

        <div
            class="absolute top-4 right-4 z-10 px-3 py-1.5 bg-amber-600 text-white rounded-xl font-black text-xs tracking-wider shadow-lg">
            {{ $camper->getPriceForDate() }}€ / gg
        </div>
    </div>

    <div class="p-6 flex flex-col flex-grow justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                {{ $camper->name }}
            </h2>

            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm leading-relaxed line-clamp-2">
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

            <x-primary-anchor href="{{ route('show', $camper) }}" wire:navigate>
                {{ __('Vedi Dettagli') }}
            </x-primary-anchor>
        </div>
    </div>
</div>
