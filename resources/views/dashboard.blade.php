<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="min-h-[calc(100vh-209px)]">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (auth()->user()->is_admin)
                {{-- ADMIN DASHBOARD --}}
                <livewire:admin.booking-index />
            @else
                {{-- USERS DASHBOARD --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 mx-4 text-center border border-gray-300 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ciao,
                        {{ auth()->user()->first_name }}!
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">Pronto per la tua prossima avventura con
                        <strong class="text-amber-500">{{ config('app.name', 'Bubba Camper') }}</strong>?
                    </p>

                    @if (!auth()->user()->isPayingRightNow())
                        <div class="mt-6 flex justify-center">
                            <div
                                class="w-full md:w-1/2">
                                <p class="text-black dark:text-white font-black uppercase">Scegli le
                                    date e parti!</p>
                                <x-primary-anchor class="mt-3" href="{{ route('index') }}" wire:navigate>
                                    {{ __('Prenota ora') }}
                                </x-primary-anchor>
                            </div>
                        </div>
                    @else
                        <div class="mt-8 flex flex-col items-center">
                            <livewire:user.payment-reminder />
                            <p class="text-xs text-gray-400 max-w-md italic">
                                *Ricorda: se il tempo scade, la prenotazione verrà annullata automaticamente.
                            </p>
                        </div>
                    @endif

                </div>

                <livewire:user.booking-history />
            @endif

        </div>
    </div>
</x-app-layout>
