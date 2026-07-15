<div class="min-h-[calc(100vh-160px)] mx-4">

    <div class="max-w-3xl flex items-center justify-center lg:justify-start mx-auto">
        <a href="{{ route('dashboard') }}" wire:navigate
            class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
            <i class="fa-solid fa-arrow-left mr-1.5 transition-transform duration-300 group-hover:-translate-x-1"></i>
            {{ __('Torna alla dashboard') }}
        </a>
    </div>

    <div
        class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-8 text-center">
            Nuova Prenotazione
        </h2>

        <form class="space-y-6" novalidate>

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
                        <x-text-input wire:model.blur="customer_first_name" id="customer_first_name"
                            class="block mt-1 w-full" type="text" />
                        <x-input-error :messages="$errors->get('customer_first_name')" class="text-center mt-1" />
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <x-input-label for="customer_last_name" value="Cognome" />
                        <x-text-input wire:model.blur="customer_last_name" id="customer_last_name"
                            class="block mt-1 w-full" type="text" />
                        <x-input-error :messages="$errors->get('customer_last_name')" class="text-center mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="customer_email" value="Email" />
                        <x-text-input wire:model.blur="customer_email" id="customer_email" class="block mt-1 w-full"
                            type="email" />
                        <x-input-error :messages="$errors->get('customer_email')" class="text-center mt-1" />
                    </div>

                    {{-- Phone --}}
                    <div x-data="{
                        iti: null,
                        init() {
                            this.$nextTick(() => {
                                if (typeof window.intlTelInput !== 'function') return;
                    
                                const input = this.$refs.phoneInput;
                    
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
                    
                                window.addEventListener('reset-phone', () => {
                                    input.value = '';
                                });
                            });
                        }
                    }">
                        <x-input-label for="customer_phone" value="Telefono" />

                        <div wire:ignore class="mt-1">
                            <x-text-input x-numbers x-ref="phoneInput" type="tel" id="customer_phone"
                                autocomplete="tel" class="block w-full text-start" />
                        </div>

                        <x-input-error :messages="$errors->get('customer_phone')" class="text-center mt-1" />
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
                            class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:border-amber-500 dark:focus:border-amber-500 focus:outline-none focus:ring-amber-500 dark:focus:ring-amber-500 dark:text-white text-center">
                            <option value="">Seleziona un camper...</option>
                            @foreach ($campers as $camper)
                                <option value="{{ $camper->id }}">{{ $camper->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('camper_id')" class="text-center mt-1" />
                    </div>

                    {{-- Price --}}
                    <div>
                        <x-input-label for="total_price" value="Prezzo Totale (€)" />
                        <x-text-input x-price wire:model.blur="total_price" id="total_price" class="block mt-1 w-full"
                            type="text" step="0.01" />
                        <x-input-error :messages="$errors->get('total_price')" class="text-center mt-1" />
                    </div>
                </div>

                {{-- Calendar --}}
                <div wire:ignore.self wire:key="calendar-container-{{ $camper_id }}">
                    <x-input-label value="Intervallo Noleggio" />

                    <input type="text" id="date_range" name="date_range" autocomplete="off" x-data="{ instance: null }"
                        x-init="instance = flatpickr($el, {
                            mode: 'range',
                            minDate: 'today',
                            dateFormat: 'd-m-Y',
                            locale: { rangeSeparator: ' al ', firstDayOfWeek: 1 },
                            position: 'top center',
                            onChange: function(selectedDates, dateStr) {
                                if (selectedDates.length > 0) {
                                    $wire.set('date_range', dateStr);
                                }
                            }
                        });
                        
                        const updateDisabledDates = () => {
                            $el.disabled = true;
                            $el.placeholder = 'Caricamento date...';
                        
                            $wire.call('getBookedDatesProperty').then(booked => {
                                instance.set('disable', booked);
                                $el.disabled = false;
                                $el.placeholder = 'Seleziona date...';
                            });
                        };
                        
                        updateDisabledDates();
                        
                        $watch('camper_id', () => {
                            instance.clear();
                            updateDisabledDates();
                        });
                        
                        window.addEventListener('clear-calendar', () => {
                            instance.clear();
                            updateDisabledDates();
                        });"
                        class="w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-amber-500 dark:focus:ring-amber-500 rounded-md shadow-sm text-center disabled:cursor-wait disabled:opacity-50"
                        placeholder="Seleziona date...">

                    <x-input-error :messages="$errors->get('date_range')" class="text-center mt-1" />
                </div>
            </section>

            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 w-full sm:w-auto justify-center">
                <x-secondary-button type="button" wire:click="resetForm" class="w-full sm:w-auto justify-center">
                    Annulla
                </x-secondary-button>
                <x-primary-button type="button" wire:click="saveManualBooking" wire:loading.attr="disabled"
                    wire:target="saveManualBooking"
                    class="w-full sm:w-auto justify-center disabled:opacity-50 disabled:cursor-wait">
                    {{ __('Crea Prenotazione') }}
                </x-primary-button>
            </div>

        </form>
    </div>
</div>
