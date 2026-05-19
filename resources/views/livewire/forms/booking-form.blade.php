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
        <div class="min-h-[241px] relative flex flex-col">

            <div x-show="$wire.days_count >= 2" x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative bg-white dark:bg-gray-800 p-5 rounded-xl border-2 border-amber-200 dark:border-amber-900 z-10 h-auto">

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Giorni totali:</span>
                        <span class="font-bold dark:text-white" x-text="$wire.days_count"></span>
                    </div>

                    <div class="flex justify-between text-base border-t border-gray-100 dark:border-gray-700 pt-2">
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Prezzo totale:</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="$wire.total_price + '€'"></span>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/20 p-3 rounded-lg space-y-1">
                        <div class="flex justify-between text-sm">
                            <span
                                class="text-amber-800 dark:text-amber-400 font-bold uppercase text-[10px] tracking-widest">Acconto
                                oggi (30%):</span>
                            <span class="font-extrabold text-amber-600 dark:text-amber-400"
                                x-text="($wire.total_price * 0.3).toFixed(2) + '€'"></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500 dark:text-gray-400">Saldo al ritiro (70%):</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300"
                                x-text="($wire.total_price * 0.7).toFixed(2) + '€'"></span>
                        </div>
                    </div>

                    <button wire:click="saveBooking"
                        class="w-full mt-2 bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-amber-600/20 uppercase tracking-widest flex justify-center items-center">
                        <span wire:loading.remove wire:target="saveBooking">Paga Acconto</span> {{-- Cambiato testo --}}
                        <span wire:loading wire:target="saveBooking" class="flex items-center">Verifica...</span>
                    </button>
                </div>

            </div>

            <div
                class="absolute inset-0 p-5 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center z-0 h-auto">
                <p class="text-sm text-gray-500 text-center italic">
                    Seleziona un intervallo di date per vedere il preventivo.
                </p>
            </div>

        </div>

    </div>

</div>
