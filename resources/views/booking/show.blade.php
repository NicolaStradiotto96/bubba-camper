<x-app-layout>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 flex items-center min-h-[calc(100vh-160px)]">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-4">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase text-center">Completa la tua prenotazione
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-center mt-4">Stai prenotando: <span
                        class="font-bold text-amber-500">{{ $camper->name }}</span></p>
            </div>

            <div class="flex items-center justify-center lg:justify-start">
                <a href="{{ route('show', $camper) }}" wire:navigate
                    class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 ">
                    <i
                        class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
                    {{ __('Torna indietro') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2">
                    <livewire:forms.booking-form :camper="$camper" />
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $camper->image_path) }}"
                            class="rounded-xl mb-4 w-full h-40 object-cover border border-gray-300 dark:border-gray-700">
                        <div class="space-y-2 text-center">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $camper->name }}</h3>
                            <p class="text-sm text-gray-400">
                                Assistenza stradale
                            </p>
                            <p class="text-sm text-gray-400">
                                Assicurazione per il veicolo
                            </p>
                            <p class="text-sm text-gray-400">
                                Sanificazione inclusa
                            </p>
                            <p class="text-sm text-gray-400">
                                Pagamento sicuro
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
