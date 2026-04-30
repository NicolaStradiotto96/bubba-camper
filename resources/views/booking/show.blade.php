<x-app-layout>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 flex items-center min-h-[calc(100vh-160px)]">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-4">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white text-center">Completa la tua prenotazione
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-center">Stai prenotando: <span
                        class="font-bold text-amber-600">{{ $camper->name }}</span></p>
            </div>

            <a href="{{ route('show', $camper->slug) }}" wire:navigate
                class="relative inline-block mb-6 text-amber-600 dark:text-amber-600 font-medium group transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor"
                    class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ __('Torna indietro') }}
                <span
                    class="absolute left-0 bottom-0 w-0 h-0.5 bg-amber-600 dark:bg-amber-600 transition-all duration-300 group-hover:w-full"></span>
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2">
                    <livewire:forms.booking-form :camper="$camper" />
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $camper->image_path) }}"
                            class="rounded-xl mb-4 w-full h-40 object-cover">
                        <div class="space-y-2">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $camper->name }}</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                Assistenza stradale inclusa
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                Sanificazione inclusa
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
