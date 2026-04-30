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
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border border-gray-300 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ciao,
                        {{ auth()->user()->first_name }}!
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">Pronto per la tua prossima avventura con
                        {{ config('app.name', 'Bubba Camper') }}?
                    </p>

                    @if (!auth()->user()->isPayingRightNow())
                        <div class="mt-8 flex justify-center">
                            <div
                                class=" p-4 bg-amber-100 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800 w-full md:w-1/2">
                                <p class="text-amber-700 dark:text-amber-400">Scegli le
                                    date e parti!</p>
                                <x-primary-anchor class="mt-3" href="{{ route('index') }}" wire:navigate>
                                    {{ __('Prenota ora') }}
                                </x-primary-anchor>
                            </div>
                        </div>
                    @else
                        <livewire:user.payment-reminder />
                    @endif

                </div>

                <livewire:user.booking-history />
            @endif

        </div>
    </div>
</x-app-layout>
