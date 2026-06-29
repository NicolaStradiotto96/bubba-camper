<div class="min-h-[calc(100vh-160px)] mx-4">

    <div class="max-w-3xl flex items-center justify-center lg:justify-start mx-auto">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 ">
            <i class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
            {{ __('Torna alla dashboard') }}
        </a>
    </div>

    <div
        class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase text-center mb-3">
            Modifica Prenotazione #{{ $booking->id }}
        </h2>



        {{-- DETAILS --}}
        <div class="border-b border-gray-200 dark:border-gray-700 text-center pb-3 mb-3">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Veicolo: <strong class="text-gray-900 dark:text-white">{{ $booking->camper->name }}</strong>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Cliente: <strong class="text-gray-900 dark:text-white">{{ $booking->customer_first_name }}
                    {{ $booking->customer_last_name }}</strong>
            </p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-center font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="updateDates" class="space-y-6">

            {{-- Calendar --}}
            <div wire:ignore wire:key="calendar-container-{{ $camper_id }}">
                <x-input-label value="Intervallo Indisponibilità" />

                <input type="text" id="new_dates_range" x-data="{
                    instance: null
                }" x-init="instance = flatpickr($el, {
                    mode: 'range',
                    minDate: 'today',
                    dateFormat: 'd-m-Y',
                    locale: { rangeSeparator: ' al ', firstDayOfWeek: 1 },
                    defaultDate: ['{{ $start_date }}', '{{ $end_date }}'],
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length > 0) {
                            const startDate = instance.formatDate(selectedDates[0], 'Y-m-d');
                            const endDate = instance.formatDate(selectedDates.length === 2 ?
                                selectedDates[1] : selectedDates[0], 'Y-m-d');
                            $wire.set('start_date', startDate);
                            $wire.set('end_date', endDate);
                        }
                    }
                });
                
                $wire.getBookedDatesProperty().then(booked => {
                    instance.set('disable', booked);
                });"
                    class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm text-center"
                    placeholder="Seleziona date...">
            </div>
            @error('start_date')
                <div class="text-red-500 text-sm font-bold mt-2">{{ $message }}</div>
            @enderror
            <x-input-error :messages="$errors->get('new_dates_range')" class="mt-1" />

            {{-- Price --}}
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span class="text-gray-500">Prezzo Originale:</span>
                    <span class="text-gray-400 line-through">€ {{ number_format($booking->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-2xl font-black mt-2">
                    <span class="text-gray-900 dark:text-white">Nuovo Totale:</span>
                    <span class="text-amber-500">€ {{ number_format($new_total_price, 2) }}</span>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-sm text-gray-500">
                        Differenza da saldare:
                        <span
                            class="font-bold {{ $new_total_price - $booking->total_price >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            € {{ number_format($new_total_price - $booking->total_price, 2) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex justify-center gap-3 mt-8">
                <x-secondary-button type="button" onclick="history.back()">Annulla</x-secondary-button>
                <x-primary-button class="bg-amber-500">Aggiorna Prenotazione</x-primary-button>
            </div>
    </div>
    </form>
</div>
</div>
