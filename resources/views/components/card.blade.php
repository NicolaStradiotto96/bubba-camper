@props(['camper'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
    <div class="relative h-75 w-full">
        <img src="{{ asset('storage/' . $camper->image_path) }}" alt="{{ $camper->name }}"
            class="w-full h-full object-cover">

        <div
            class="absolute top-4 right-4 bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
            {{ $camper->price_per_day }}€ / giorno
        </div>
    </div>

    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $camper->name }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
            {{ Str::limit($camper->description, 100) }}
        </p>

        <div class="mt-6 flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4">
            <span class="text-gray-500 dark:text-gray-400 text-sm">Disponibile subito</span>
            <a href="#">
                <x-primary-button class="ms-3">
                    {{ __('Vedi dettagli') }}
                </x-primary-button>
            </a>
        </div>
    </div>
</div>
