<div class="min-h-[calc(100vh-160px)] mx-4">

    <div class="max-w-3xl flex items-center justify-center lg:justify-start mx-auto">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 ">
            <i class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
            {{ __('Torna indietro') }}
        </a>
    </div>

    <div
        class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-8 text-center">
            Nuova Prenotazione
        </h2>

        <form wire:submit.prevent="saveManualBooking" class="space-y-6">

            {{-- CUSTOMER INFOS --}}
            <section
                class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                <h3
                    class="text-lg font-bold text-amber-500 uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user"></i> Dati Cliente
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- First Name --}}
                    <div>
                        <x-input-label for="customer_first_name" value="Nome" />
                        <x-text-input wire:model="customer_first_name" id="customer_first_name"
                            class="block mt-1 w-full" type="text" />
                        <x-input-error :messages="$errors->get('customer_first_name')" class="mt-1" />
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <x-input-label for="customer_last_name" value="Cognome" />
                        <x-text-input wire:model="customer_last_name" id="customer_last_name" class="block mt-1 w-full"
                            type="text" />
                        <x-input-error :messages="$errors->get('customer_last_name')" class="mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="customer_email" value="Email" />
                        <x-text-input wire:model="customer_email" id="customer_email" class="block mt-1 w-full"
                            type="email" />
                        <x-input-error :messages="$errors->get('customer_email')" class="mt-1" />
                    </div>

                    {{-- Phone --}}
                    <div x-data="{
                        iti: null,
                        init() {
                            this.$nextTick(() => {
                                if (typeof window.intlTelInput !== 'function') return;
                    
                                this.iti = window.intlTelInput($refs.phoneInput, {
                                    initialCountry: 'it',
                                    separateDialCode: true,
                                    countrySearch: true,
                                    i18n: window.itiI18nIt,
                                });
                    
                                if (window.itiUtils) {
                                    this.iti.utils = window.itiUtils;
                                }
                    
                                const syncPhone = () => {
                                    $refs.phoneInput.value = $refs.phoneInput.value.replace(/\D/g, '');
                    
                                    const dialCodeEl = this.$el.querySelector('.iti__selected-dial-code');
                                    const dialCode = dialCodeEl ? dialCodeEl.innerText.trim() : '';
                    
                                    const rawNumber = $refs.phoneInput.value;
                    
                                    if (rawNumber === '') {
                                        this.$wire.set('customer_phone', '', false);
                                        return;
                                    }
                    
                                    const fullNumber = dialCode + rawNumber;
                                    this.$wire.set('customer_phone', fullNumber, false);
                                };
                    
                                $refs.phoneInput.addEventListener('countrychange', syncPhone);
                                $refs.phoneInput.addEventListener('input', syncPhone);
                            });
                        }
                    }">
                        <x-input-label for="customer_phone" value="Telefono" />

                        <div wire:ignore class="mt-1">
                            <x-text-input x-ref="phoneInput" type="tel" id="customer_phone"
                                class="block w-full text-start" />
                        </div>

                        <x-input-error :messages="$errors->get('customer_phone')" class="mt-2" />
                    </div>
                </div>
            </section>

            {{-- BOOKING INFOS --}}
            <section
                class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 space-y-3">
                <h3
                    class="text-lg font-bold text-amber-500 uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-days"></i> Dettagli Noleggio
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Camper --}}
                    <div>
                        <x-input-label for="camper_id" value="Veicolo" />
                        <select wire:model.live="camper_id" id="camper_id"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-amber-500 dark:focus:border-amber-500 focus:outline-none focus:ring-0 dark:text-white text-center">
                            <option value="">Seleziona un camper...</option>
                            @foreach ($campers as $camper)
                                <option value="{{ $camper->id }}">{{ $camper->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('camper_id')" class="mt-1" />
                    </div>

                    {{-- Price --}}
                    <div>
                        <x-input-label for="total_price" value="Prezzo Totale (€)" />
                        <x-text-input wire:model="total_price" id="total_price" class="block mt-1 w-full" type="number"
                            step="0.01" />
                        <x-input-error :messages="$errors->get('total_price')" class="mt-1" />
                    </div>
                </div>

                {{-- Dates --}}
                <div wire:ignore wire:key="calendar-{{ count($this->bookedDates) }}">

                    <div class="relative" x-data="{ booked: @js($this->bookedDates) }">
                        <x-input-label value="Intervallo Noleggio" />

                        <input type="text" id="date_range" name="date_range" autocomplete="off"
                            x-init="if ($el._flatpickr) { $el._flatpickr.destroy(); }
                            
                            $el._flatpickr = flatpickr($el, {
                                mode: 'range',
                                minDate: 'today',
                                maxDate: new Date().fp_incr(365),
                                dateFormat: 'd-m-Y',
                                locale: {
                                    rangeSeparator: ' al ',
                                    firstDayOfWeek: 1
                                },
                                disable: booked,
                                position: 'top center',
                                onChange: function(selectedDates, dateStr) {
                                    if (selectedDates.length > 0) {
                                        $wire.set('date_range', dateStr);
                                    }
                                }
                            });
                            
                            window.addEventListener('clear-calendar', () => {
                                if ($el._flatpickr) { $el._flatpickr.clear(); }
                            });"
                            class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm text-center"
                            placeholder="Seleziona date...">
                        <x-input-error :messages="$errors->get('date_range')" class="mt-1" />

                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 w-full sm:w-auto justify-end">
                <x-secondary-button href="{{ route('dashboard') }}" wire:navigate
                    class="w-full sm:w-auto justify-center">
                    Annulla
                </x-secondary-button>
                <x-primary-button wire:loading.attr="disabled" class="w-full sm:w-auto justify-center">
                    <span wire:loading.remove wire:target="saveManualBooking">
                        Crea Prenotazione
                    </span>

                    <span wire:loading wire:target="saveManualBooking">
                        <i class="fa-solid fa-spinner fa-spin mr-[0.3rem]"></i> Invio in corso...
                    </span>
                </x-primary-button>
            </div>

        </form>
    </div>
</div>
