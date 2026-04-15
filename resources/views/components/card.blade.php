@props(['camper'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
    <div class="relative h-75 w-full">
        <img src="{{ asset('storage/' . $camper->image_path) }}" alt="{{ $camper->name }}"
            class="w-full h-full object-cover">

        <div
            class="absolute top-4 right-4 bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
            {{ $camper->getPriceForDate() }}€ / gg
        </div>
    </div>

    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $camper->name }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mt-2 line-clamp-1">
            {{ Str::limit($camper->description, 80) }}
        </p>

        <div class="mt-6 flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-6">
            <span class="text-green-600 dark:text-green-400 text-sm font-bold italic">Disponibile subito</span> <a
                href="#">
                <x-primary-anchor href="{{ route('show', $camper) }}" wire:navigate>
                    {{ __('Vedi Dettagli') }}
                </x-primary-anchor>
            </a>
        </div>
    </div>
</div>
