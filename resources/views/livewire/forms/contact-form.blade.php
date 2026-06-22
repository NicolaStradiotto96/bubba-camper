<div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-300 dark:border-gray-700">
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest">
            Contattaci via email
        </h2>
    </div>

    {{-- FORM --}}
    <form wire:submit.prevent="sendEmail"
        @form-reset.window="$el.reset()" class="space-y-4">
        @csrf

        {{-- Honeypot --}}
        <input id="website" wire:model="website" type="text" tabindex="-1" autocomplete="off" aria-hidden="true"
            style="position:absolute; left:-9999px; height:0; overflow:hidden;">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Name --}}
            <div>
                <x-input-label for="name" :value="__('Nome')" />
                <x-text-input id="name" wire:model="name" type="text" class="block mt-1 w-full" required
                    autocomplete="given-name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-center" />
            </div>
            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" wire:model="email" type="email" class="block mt-1 w-full" required
                    autocomplete="family-name" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-center" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Booking Period --}}
            <div wire:ignore wire:key="contact-calendar-{{ count($this->bookedDates) }}" class="md:col-span-2">
                <x-input-label for="date_range" :value="__('Seleziona il periodo')" />

                <div x-data="{ booked: @js($this->bookedDates) }">
                    <x-text-input 
                        id="date_range" 
                        name="date_range" 
                        autocomplete="off" 
                        x-init="
                            if ($el._flatpickr) { $el._flatpickr.destroy(); }
                            
                            $el._flatpickr = flatpickr($el, {
                                mode: 'range',
                                dateFormat: 'd-m-Y', 
                                locale: {
                                    rangeSeparator: ' al ',
                                    firstDayOfWeek: 1
                                },
                                minDate: 'today',
                                maxDate: new Date().fp_incr(365),
                                disable: booked,
                                position: 'below center',
                                onReady: function(selectedDates, dateStr, instance) {
                                    const monthSelect = instance.calendarContainer.querySelector('.flatpickr-monthDropdown-months');
                                    if (monthSelect) {
                                        monthSelect.id = 'flatpickr_month_select';
                                        monthSelect.name = 'flatpickr_month_select';
                                    }
                            
                                    const yearInput = instance.calendarContainer.querySelector('.numInput.cur-year');
                                    if (yearInput) {
                                        yearInput.id = 'flatpickr_year_input';
                                        yearInput.name = 'flatpickr_year_input';
                                    }
                                },
                                onChange: function(selectedDates, dateStr) {
                                    $wire.date_range = dateStr;
                                }
                            });

                            $watch('$wire.date_range', newVal => {
                                if (!newVal && $el._flatpickr) {
                                    $el._flatpickr.clear();
                                }
                            });
                        "
                        type="text" 
                        class="text-center block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm transition duration-150 ease-in-out" 
                        placeholder="Scegli le date di inizio e fine" 
                    />
                </div>
                <x-input-error :messages="$errors->get('date_range')" class="mt-2 text-center" />
            </div>
        </div>

        {{-- Message --}}
        <div>
            <x-input-label for="message" :value="__('Il tuo messaggio')" />
            <textarea id="message" wire:model="message" rows="5"
                class="text-center mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm resize-none"
                placeholder="Parlaci del tuo itinerario..."></textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2 text-center" />
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-700 text-center"
                role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-700 text-center"
                role="alert">
                {{ session('error') }}
            </div> @endif
        <div class="flex justify-center">
        <x-primary-button class="w-full flex justify-center items-center py-3">
            <span wire:loading.remove wire:target="sendEmail">{{ __('Invia richiesta') }}</span>
            <span wire:loading wire:target="sendEmail">{{ __('Invio in corso...') }}</span>
        </x-primary-button>
</div>
</form>
</div>
