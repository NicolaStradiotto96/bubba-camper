<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">

    <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Prenota il tuo viaggio</h3>

    <div class="space-y-4">

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
                minRange: 1,
                disable: @js($this->bookedDates),
                onOpen: function() {
                    $wire.$refresh();
                },
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length === 1) {
                        $wire.set('days_count', 0);
                        $wire.set('total_price', 0);
                        return;
                    }
            
                    if (selectedDates.length === 2 && selectedDates[0].getTime() === selectedDates[1].getTime()) {
                        picker.clear();
                        $wire.set('date_range', ''); // Qui scatta l'errore 'obbligatorio'
                        $wire.set('days_count', 0);
                        return;
                    }
            
                    if (selectedDates.length === 2) {
                        $wire.set('date_range', dateStr);
                    }
                }
            });"
                x-on:clear-calendar.window="picker.clear()">
                <input type="text"
                    class="w-full pr-4 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-amber-500 focus:border-amber-500 text-gray-900 dark:text-white flatpickr-animation"
                    placeholder="Scegli quando partire...">
                <p class="mt-1 text-xs text-gray-500 italic">Minimo 2 giorni di noleggio</p>
            </div>

        </div>

        <div class="min-h-[20px]">
            <x-input-error :messages="$errors->get('date_range')" />
            <x-input-error :messages="$errors->get('days_count')" />
        </div>

        {{-- Costs --}}
        <div class="min-h-[200px] relative flex flex-col">

            <div x-show="$wire.days_count >= 2" x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative bg-white dark:bg-gray-800 p-5 rounded-xl border-2 border-amber-200 dark:border-amber-900 z-50 h-[12rem]">

                <div class="space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Giorni totali:</span>
                        <span class="font-bold dark:text-white" x-text="$wire.days_count"></span>
                    </div>

                    <div class="flex justify-between text-sm italic text-gray-500">
                        <span>Tariffa calcolata in base alla stagione</span>
                        <span>Media: <span
                                x-text="$wire.days_count > 0 ? ($wire.total_price / $wire.days_count).toFixed(2) : '0.00'"></span>€/gg</span>
                    </div>

                    <div class="flex justify-between text-xl border-t border-amber-200 dark:border-amber-800 pt-3">
                        <span class="font-bold text-gray-900 dark:text-white">Totale:</span>
                        <span class="font-extrabold text-amber-600 dark:text-amber-400 text-2xl"
                            x-text="$wire.total_price + '€'"></span>
                    </div>

                    <button wire:click="saveBooking"
                        class="w-full mt-4 bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-amber-600/20 uppercase tracking-widest flex justify-center items-center">
                        <span wire:loading.remove wire:target="saveBooking">Vai al pagamento</span>
                        <span wire:loading wire:target="saveBooking" class="flex items-center">Verifica...</span>
                    </button>
                </div>
            </div>

            <div
                class="absolute inset-0 p-5 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center z-0 h-[12rem]">
                <p class="text-sm text-gray-500 text-center italic">
                    Seleziona un intervallo di date (min. 2 giorni) per vedere il preventivo.
                </p>
            </div>

        </div>

    </div>

</div>
