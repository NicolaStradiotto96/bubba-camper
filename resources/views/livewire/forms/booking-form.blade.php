<div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-gray-300 dark:border-gray-700"
    x-data="{ showContract: false }">

    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase text-center">Prenota il tuo viaggio</h3>

    <div class="space-y-2">

        {{-- Calendar --}}
        <div wire:ignore wire:key="calendar-{{ count($this->bookedDates) }}">

            <div class="relative" x-data="{ booked: @js($this->bookedDates) }">
                <input type="text" id="date_range" name="date_range"
                    aria-label="Seleziona le date di inizio e fine noleggio" autocomplete="off" x-init="if ($el._flatpickr) { $el._flatpickr.destroy(); }
                    
                    $el._flatpickr = flatpickr($el, {
                        mode: 'range',
                        minDate: 'today',
                        maxDate: new Date().fp_incr(365),
                        dateFormat: 'd-m-Y',
                        locale: {
                            rangeSeparator: ' al ',
                            firstDayOfWeek: 1
                        },
                        minRange: 1,
                        disable: booked,
                        position: 'below center',
                        onOpen: function() {
                            $wire.$refresh();
                        },
                        onChange: function(selectedDates, dateStr) {
                            $wire.set('terms_accepted', false);
                            $wire.set('privacy_accepted', false);
                    
                            if (selectedDates.length === 1) {
                                $wire.set('days_count', 0);
                                $wire.set('total_price', 0);
                                return;
                            }
                    
                            if (selectedDates.length === 2 && selectedDates[0].getTime() === selectedDates[1].getTime()) {
                                $el._flatpickr.clear();
                                $wire.set('date_range', '');
                                $wire.set('days_count', 0);
                                return;
                            }
                    
                            if (selectedDates.length === 2) {
                                $wire.set('date_range', dateStr);
                            }
                        }
                    });
                    
                    window.addEventListener('clear-calendar', () => {
                        if ($el._flatpickr) { $el._flatpickr.clear(); }
                    });
                    
                    $watch('$wire.date_range', newVal => {
                        if (!newVal && $el._flatpickr) {
                            $el._flatpickr.clear();
                        }
                    });"
                    class="w-full p-2 mt-3 border-gray-300 dark:border-gray-600 dark:bg-gray-900 rounded-2xl focus:border-amber-500 dark:focus:border-amber-500 focus:ring-amber-500 dark:focus:ring-amber-500 text-gray-900 dark:text-white flatpickr-animation text-center"
                    placeholder="Scegli quando partire...">
                <p class="mt-1 text-xs text-gray-400 italic text-center">Minimo 2 giorni di noleggio</p>
            </div>

        </div>

        <div class="min-h-[16px] text-center">
            <x-input-error :messages="$errors->get('date_range')" class="text-xs" />
            <x-input-error :messages="$errors->get('days_count')" class="text-xs" />
        </div>

        {{-- Costs --}}
        <div class="grid grid-cols-1 grid-rows-1 relative w-full max-w-[600px]">

            <div x-show="$wire.days_count >= 2" x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="col-start-1 row-start-1 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border-2 border-amber-500 z-10 h-full flex flex-col justify-between">

                <div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 font-black uppercase">Giorni totali:</span>
                            <span class="font-bold text-gray-900 dark:text-white" x-text="$wire.days_count"
                                aria-live="polite"></span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 font-black uppercase">Costo totale:</span>
                            <span class="font-bold text-gray-900 dark:text-white" x-text="$wire.total_price + '€'"
                                aria-live="polite"></span>
                        </div>

                        <div class="space-y-1 border-t border-gray-100 dark:border-gray-700 pt-2">
                            <div class="flex justify-between">
                                <span class="text-amber-500 dark:text-amber-500 font-black uppercase">Acconto
                                    oggi:</span>
                                <span class="font-extrabold text-amber-500 dark:text-amber-500"
                                    x-text="($wire.total_price * 0.3).toFixed(2) + '€'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400 font-black uppercase">Saldo al
                                    ritiro:</span>
                                <span class="font-medium text-gray-900 dark:text-white"
                                    x-text="($wire.total_price * 0.7).toFixed(2) + '€'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Contract --}}
                    <div class="w-full text-left border-t border-gray-100 dark:border-gray-700 pt-2 mt-2">
                        <label
                            class="block text-xs font-black text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">
                            <i class="fa-solid fa-file-contract text-amber-500 mr-0.5"></i> Contratto di Noleggio
                        </label>

                        <div class="relative">
                            <button type="button" @click="showContract = true"
                                class="absolute top-3 right-3 flex items-center justify-center z-10 p-2 bg-gray-200 dark:bg-gray-700 rounded-xl text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-amber-500 dark:hover:bg-amber-500 hover:text-white dark:hover:text-black focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                                title="Ingrandisci Contratto">
                                <i class="fa-solid fa-expand text-xs"></i>
                            </button>

                            <div
                                class="w-full h-32 overflow-y-auto p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 text-[11px] text-gray-600 dark:text-gray-400 font-sans space-y-3 shadow-inner focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                <x-contract />
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="flex items-start gap-3 mt-2">
                            <div class="flex items-center h-5 mt-1.5">
                                <input id="terms_accepted" wire:model="terms_accepted" type="checkbox"
                                    class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 transition dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                            </div>
                            <div class="text-xs">
                                <label for="terms_accepted"
                                    class="font-bold text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                                    Accetto il <button type="button" @click="showContract = true"
                                        class="text-amber-500 hover:underline focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Contratto di
                                        Noleggio</button> e i termini di cancellazione.
                                </label>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">Dichiaro di approvare il
                                    pagamento dell'acconto del 30% e il depósito della cauzione di 500€ al ritiro.</p>
                                <div class="min-h-[16px]">
                                    <x-input-error :messages="$errors->get('terms_accepted')" class="text-xs" />
                                </div>
                            </div>
                        </div>

                        {{-- Privacy --}}
                        <div class="flex items-start gap-3 mt-1">
                            <div class="flex items-center h-5 mt-1.5">
                                <input id="privacy_accepted" wire:model="privacy_accepted" type="checkbox"
                                    class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 transition dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                            </div>
                            <div class="text-xs">
                                <label for="privacy_accepted"
                                    class="font-bold text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                                    Ho letto e accetto l'<a href="https://www.iubenda.com/privacy-policy/48326335" target="_blank"
                                        class="text-amber-500 hover:underline focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Informativa sulla Privacy</a>.
                                </label>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">Consento al trattamento dei dati
                                    per la gestione della prenotazione e dei documenti di guida.</p>
                                <div class="min-h-[16px]">
                                    <x-input-error :messages="$errors->get('privacy_accepted')" class="text-xs" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <x-primary-button wire:click="saveBooking" wire:loading.attr="disabled" wire:target="saveBooking"
                    class="w-full mt-3 flex justify-center items-center disabled:opacity-50 disabled:cursor-wait">
                    {{ __('Vai al pagamento') }}
                </x-primary-button>
            </div>

            <div x-show="$wire.days_count < 2"
                class="col-start-1 row-start-1 p-5 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[2rem] flex flex-col items-center justify-center z-0 min-h-[488px]">
                <p class="text-sm text-gray-400 text-center italic">
                    Seleziona un intervallo di date per vedere il preventivo.
                </p>
            </div>

        </div>

    </div>

    {{-- MODAL --}}
    <div x-show="showContract" x-trap="showContract" x-cloak x-effect="document.body.classList.toggle('overflow-hidden', showContract)"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="showContract = false" @keydown.escape.window="showContract = false"
            class="w-full max-w-4xl h-[90vh] bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2rem] shadow-2xl flex flex-col overflow-hidden">

            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 tabindex="1" class="font-bold text-gray-900 dark:text-white uppercase focus:outline-none">Contratto di Noleggio</h3>
                <button @click="showContract = false" title="Chiudi"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 p-1 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 transition"><i
                        class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 text-sm text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                <x-contract />
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-center">
                <button @click="showContract = false; $wire.set('terms_accepted', true)"
                    class="px-6 py-2 bg-amber-600 text-white rounded-[2rem] font-bold uppercase text-xs hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Accetto
                </button>
            </div>
        </div>
    </div>

</div>
