@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

<div class="min-h-[calc(100vh-160px)] mx-4">

    <div class="max-w-3xl flex items-center justify-center lg:justify-start mx-auto">
        <a href="{{ route('dashboard') }}"
            class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
            <i class="fa-solid fa-arrow-left mr-1.5 transition duration-300 group-hover:-translate-x-1"></i>
            {{ __('Torna alla dashboard') }}
        </a>
    </div>

    <div
        class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-700">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase text-center mb-3">
            Modifica Prenotazione <strong class="id">#{{ $booking->id }}</strong>
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

        <form novalidate>

            {{-- Calendar --}}
            <div wire:ignore wire:key="calendar-container-{{ $camper_id }}">
                <x-input-label value="Intervallo Indisponibilità" />

                <input type="text" id="new_dates_range" x-data="{
                    instance: null
                }" x-init="instance = flatpickr($el, {
                    mode: 'range',
                    minDate: 'today',
                    dateFormat: 'd-m-Y',
                    defaultDate: ['{{ $start_date }}', '{{ $end_date }}'],
                    locale: { rangeSeparator: ' al ', firstDayOfWeek: 1 },
                    onChange: function(selectedDates) {
                        if (selectedDates.length > 0) {
                            $wire.set('start_date', flatpickr.formatDate(selectedDates[0], 'd-m-Y'));
                            $wire.set('end_date', flatpickr.formatDate(selectedDates[selectedDates.length - 1], 'd-m-Y'));
                            $wire.validateRange().then(isValid => {
                                if (!isValid) {
                                    instance.clear();
                                    alert('Periodo non disponibile.');
                                }
                            });
                        }
                    }
                });
                
                $wire.getBookedDatesProperty().then(booked => {
                    instance.set('disable', booked);
                    instance.redraw();
                });"
                    class="w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-amber-500 dark:focus:ring-amber-500 rounded-2xl shadow-sm text-center"
                    placeholder="Seleziona date...">
            </div>

            <div class="min-h-5 text-center mt-1">
                @error('start_date')
                    <div class="text-red-500 text-sm font-bold mt-2">{{ $message }}</div>
                @enderror
                <x-input-error :messages="$errors->get('new_dates_range')" />
            </div>


            {{-- Price --}}
            <div
                class="mt-2 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-100 dark:border-gray-600">
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
                <x-primary-button type="button" wire:click="updateDates"
                    class="bg-amber-500 disabled:opacity-50 disabled:cursor-wait" wire:loading.attr="disabled">
                    {{ __('Aggiorna Prenotazione') }}
                </x-primary-button>
            </div>
    </div>
    </form>
</div>
</div>
