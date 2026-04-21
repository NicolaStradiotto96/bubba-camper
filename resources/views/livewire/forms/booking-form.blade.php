<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
    <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Prenota il tuo viaggio</h3>

    <div class="space-y-6">
        <x-input-error :messages="$errors->get('date_range')" class="mb-4" />

        {{-- Calendar --}}
        <div wire:ignore wire:key="calendar-{{ count($this->bookedDates) }}">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleziona le date</label>
            <div class="relative" x-data="{ picker: null }" x-init="picker = flatpickr($el.querySelector('input'), {
                mode: 'range',
                minDate: 'today',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd-m-Y',
                locale: 'it',
                disable: @js($this->bookedDates),
                onChange: function(selectedDates, dateStr) {
                    $wire.set('date_range', dateStr);
                }
            });"
                x-on:clear-calendar.window="picker.clear()">
                <input type="text"
                    class="w-full pl-10 pr-4 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-amber-500 focus:border-amber-500 text-gray-900 dark:text-white"
                    placeholder="Scegli quando partire...">
            </div>
        </div>

        {{-- Costs --}}
        @if ($days_count > 0)
            <div class="bg-amber-50 dark:bg-amber-900/20 p-5 rounded-xl space-y-3" x-transition>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Giorni totali:</span>
                    <span class="font-bold dark:text-white">{{ $days_count }}</span>
                </div>

                <div class="flex justify-between text-sm italic text-gray-500">
                    <span>Tariffa calcolata in base alla stagione</span>
                    <span>Media: {{ round($total_price / $days_count, 2) }}€/gg</span>
                </div>

                <div class="flex justify-between text-xl border-t border-amber-200 dark:border-amber-800 pt-3">
                    <span class="font-bold text-gray-900 dark:text-white">Totale:</span>
                    <span class="font-extrabold text-amber-600 dark:text-amber-400 text-2xl">{{ $total_price }}€</span>
                </div>
            </div>

            <button wire:click="saveBooking"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-amber-600/20 uppercase tracking-widest flex justify-center items-center">
                <span wire:loading.remove wire:target="saveBooking">Vai al pagamento</span>
                <span wire:loading wire:target="saveBooking" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Verifica in corso...
                </span>
            </button>
        @else
            <div class="p-6 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                <p class="text-sm text-gray-500 text-center italic">
                    Seleziona un intervallo di date dal calendario per calcolare il preventivo del tuo viaggio.
                </p>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="p-4 bg-green-100 text-green-700 rounded-xl text-center font-bold">
                {{ session('success') }}
            </div>
        @endif
    </div>

</div>
