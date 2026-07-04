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

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-8 text-center">
            Gestione Indisponibilità
        </h2>

        <div x-data="{ open: false, message: '' }"
            @notify.window="message = $event.detail.message; open = true"
            x-show="open" x-transition class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-center font-bold">
            <span x-text="message"></span>
        </div>

        <form wire:submit.prevent="saveBlock" class="space-y-6">
            <section
                class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 space-y-4">
                <h3
                    class="text-lg font-bold text-amber-500 uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Dettagli Blocco
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Camper --}}
                    <div>
                        <x-input-label for="camper_id" value="Veicolo" />
                        <select wire:model.live="camper_id" id="camper_id"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-amber-500 dark:focus:border-amber-500 focus:outline-none focus:ring-0 dark:text-white text-center">
                            <option value="">Seleziona camper...</option>
                            @foreach ($campers as $camper)
                                <option value="{{ $camper->id }}">{{ $camper->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('camper_id')" class="mt-1" />
                    </div>

                    {{-- Calendar --}}
                    <div wire:ignore wire:key="calendar-container-{{ $camper_id }}">
                        <x-input-label value="Intervallo Indisponibilità" />

                        <input type="text" id="maintenance_range" x-data="{
                            instance: null
                        }" x-init="instance = flatpickr($el, {
                            mode: 'range',
                            minDate: 'today',
                            dateFormat: 'd-m-Y',
                            locale: { rangeSeparator: ' al ', firstDayOfWeek: 1 },
                            position: 'bottom center',
                            onChange: function(selectedDates, dateStr, instance) {
                                if (selectedDates.length > 0) {
                                    const startDate = instance.formatDate(selectedDates[0], 'd-m-Y');
                                    const endDate = instance.formatDate(selectedDates.length === 2 ?
                                        selectedDates[1] : selectedDates[0], 'd-m-Y');
                                    $wire.set('start_date', startDate);
                                    $wire.set('end_date', endDate);
                                    $wire.validateRange();
                                }
                            }
                        });
                        const
                            updateDisabledDates = () => {
                                $wire.call('getBookedDatesProperty').then(booked => {
                                    instance.set('disable', booked);
                                });
                            };
                        
                        updateDisabledDates();
                        
                        $watch('camper_id', () => updateDisabledDates());
                        
                        window.addEventListener('set-flatpickr-date', (e) => {
                            instance.setDate([e.detail.start, e.detail.end]);
                            updateDisabledDates();
                        });
                        
                        window.addEventListener('clear-calendar', () => {
                            instance.clear();
                            updateDisabledDates();
                        });"
                            class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm text-center"
                            placeholder="Seleziona date...">
                    </div>
                    <x-input-error :messages="$errors->get('maintenance_range')" class="mt-1" />

                </div>

                {{-- Reason --}}
                <div>
                    <x-input-label for="reason" value="Motivazione" />
                    <x-text-input wire:model.live="reason" id="reason" class="block mt-1 w-full" type="text"
                        placeholder="es: Manutenzione periodica" />
                    <x-input-error :messages="$errors->get('reason')" class="mt-1" />
                </div>
            </section>

            <div class="flex justify-center gap-3">
                <x-secondary-button type="button" wire:click="cancelEdit"
                    class="text-gray-500 hover:text-gray-700 ml-4">
                    Annulla
                </x-secondary-button>
                <x-primary-button>
                    {{ $editingId ? 'Aggiorna' : 'Blocca Date' }}
                </x-primary-button>
            </div>
        </form>

        {{-- Maintenance List --}}
        <div class="mt-6 pt-4 border-t border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 uppercase text-center">
                Blocchi Attivi
                @if ($camper_id && $blocks->count() > 0)
                    <span class="text-amber-500">{{ $blocks->first()->camper->name }}</span>
                @endif
            </h3>

            <div class="space-y-2">
                @forelse ($blocks as $block)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">
                            <i class="fa-solid fa-screwdriver-wrench text-amber-500 mr-2"></i>
                            <strong>{{ $block->camper->name }}</strong>:

                            @if ($block->start_date->isSameDay($block->end_date))
                                <span>{{ $block->start_date->format('d-m-Y') }}</span>
                            @else
                                <span>{{ $block->start_date->format('d-m-Y') }}</span>
                                <span class="text-amber-500 px-1">➔</span>
                                <span class="mr-2">{{ $block->end_date->format('d-m-Y') }}</span>
                            @endif

                            <span class="italic text-gray-500 text-sm">{{ $block->reason }}</span>
                        </span>

                        <div class="flex">
                            <button wire:click="editBlock({{ $block->id }})" @disabled($editingId || $this->isDirty)
                                class="text-amber-500 {{ $editingId || $this->isDirty ? 'opacity-30 cursor-not-allowed' : 'hover:text-amber-700' }} ml-1 px-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button wire:click="removeBlock({{ $block->id }})" @disabled($editingId || $this->isDirty)
                                class="text-red-500 {{ $editingId || $this->isDirty ? 'opacity-30 cursor-not-allowed' : 'hover:text-red-700' }}  px-1">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 italic py-3">Nessun blocco attivo
                        {{ $camper_id ? 'per questo camper' : '' }}</p>
                @endforelse
            </div>

            <div class="mt-6 px-4">
                {{ $blocks->links() }}
            </div>
        </div>

    </div>
</div>
