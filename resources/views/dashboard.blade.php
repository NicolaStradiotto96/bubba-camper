<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (auth()->user()->is_admin)
                {{-- ADMIN DASHBOARD --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border-l-4 border-amber-500">
                        <h3 class="text-sm font-bold text-gray-500 uppercase">Prenotazioni Totali</h3>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">24</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 dark:text-white">Ultime Richieste di Noleggio</h3>
                </div>
            @else
                {{-- USERS DASHBOARD --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ciao, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">Pronto per la tua prossima avventura con Bubba Camper?
                    </p>

                    <div class="mt-8 p-4 bg-amber-100 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <p class="text-amber-700 dark:text-amber-400">Non hai ancora prenotazioni attive. Scegli le date e parti!</p>
                        <x-primary-anchor class="mt-3" href="{{ route('index') }}" wire:navigate>
                            {{ __('Prenota ora') }}
                        </x-primary-anchor>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
