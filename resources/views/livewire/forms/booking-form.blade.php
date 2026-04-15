<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
    <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Prenota il tuo viaggio</h3>

    <div class="space-y-6">
        {{-- Calendar --}}
        <div wire:ignore>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleziona le date</label>
            <div class="relative">
                <input x-data x-init="flatpickr($el, {
                    mode: 'range',
                    minDate: 'today',
                    dateFormat: 'Y-m-d',
                    locale: 'it',
                    onChange: function(selectedDates, dateStr) {
                        @this.set('dateRange', dateStr);
                    }
                })" type="text"
                    class="w-full pl-10 pr-4 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl focus:ring-amber-500 focus:border-amber-500"
                    placeholder="Scegli quando partire...">
                <div class="absolute left-3 top-3.5 text-gray-400">
                </div>
            </div>
        </div>

        {{-- Costs --}}
        @if ($totalDays > 0)
            <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl space-y-2" x-transition>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Giorni totali:</span>
                    <span class="font-bold">{{ $totalDays }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Prezzo giornaliero:</span>
                    <span>{{ $camper->price_per_day }}€</span>
                </div>
                <div class="flex justify-between text-lg border-t border-amber-200 dark:border-amber-800 pt-2">
                    <span class="font-bold text-gray-900 dark:text-white">Totale:</span>
                    <span class="font-extrabold text-amber-600">{{ $totalPrice }}€</span>
                </div>
            </div>

            <button wire:click="saveBooking"
                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-amber-600/20 uppercase tracking-widest">
                Vai al pagamento
            </button>
        @else
            <p class="text-sm text-gray-500 text-center italic">
                Seleziona un intervallo di date per vedere il preventivo.
            </p>
        @endif
    </div>
</div>
