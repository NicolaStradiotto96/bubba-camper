<div x-data="{ uploading: false }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-error="uploading = false" class="space-y-4">

    <form wire:submit.prevent="uploadDocuments" class="space-y-4">
        <input type="hidden" wire:model="bookingId">

        {{-- Driver License --}}
        <div class="bg-gray-200/50 dark:bg-gray-900/20 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
            <label class="block text-xl font-black text-gray-900 dark:text-gray-100 uppercase mb-4 text-center">Patente
                di Guida</label>
            <div class="grid grid-cols-2 gap-4">

                {{-- Front --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Fronte</span>
                    <input type="file" id="driver_license_front" wire:model="driver_license_front"
                        wire:change="$refresh" accept=".jpg,.jpeg,.png,.pdf" class="hidden" />
                    <button type="button" onclick="document.getElementById('driver_license_front').click()"
                        class="w-full inline-flex justify-center items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 rounded-xl transition shadow-md hover:bg-amber-700 overflow-hidden">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>
                    <span class="block text-xs text-gray-400 font-sans italic mt-2 truncate">
                        {{ $this->driver_license_front ? $this->driver_license_front->getClientOriginalName() : 'Nessun file selezionato' }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($driver_license_front)
                            <img src="{{ $driver_license_front->temporaryUrl() }}"
                                class="h-20 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('driver_license_front')" class="text-xs mt-1" />
                </div>

                {{-- Back --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Retro</span>
                    <input type="file" id="driver_license_back" wire:model="driver_license_back"
                        wire:change="$refresh" accept=".jpg,.jpeg,.png,.pdf" class="hidden" />
                    <button type="button" onclick="document.getElementById('driver_license_back').click()"
                        class="w-full inline-flex justify-center items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 rounded-xl transition shadow-md hover:bg-amber-700 overflow-hidden">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>
                    <span class="block text-xs text-gray-400 font-sans italic mt-2 truncate">
                        {{ $this->driver_license_back ? $this->driver_license_back->getClientOriginalName() : 'Nessun file selezionato' }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($driver_license_back)
                            <img src="{{ $driver_license_back->temporaryUrl() }}"
                                class="h-20 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('driver_license_back')" class="text-xs mt-1" />
                </div>
            </div>
        </div>

        {{-- ID Card --}}
        <div class="bg-gray-200/50 dark:bg-gray-900/20 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
            <label class="block text-xl font-black text-gray-900 dark:text-gray-100 uppercase mb-4 text-center">Carta
                d'Identità</label>
            <div class="grid grid-cols-2 gap-4">

                {{-- Front --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Fronte</span>
                    <input type="file" id="id_card_front" wire:model="id_card_front" wire:change="$refresh"
                        accept=".jpg,.jpeg,.png,.pdf" class="hidden" />
                    <button type="button" onclick="document.getElementById('id_card_front').click()"
                        class="w-full inline-flex justify-center items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 rounded-xl transition shadow-md hover:bg-amber-700 overflow-hidden">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>
                    <span class="block text-xs text-gray-400 font-sans italic mt-2 truncate">
                        {{ $this->id_card_front ? $this->id_card_front->getClientOriginalName() : 'Nessun file selezionato' }}
                    </span>
                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($id_card_front)
                            <img src="{{ $id_card_front->temporaryUrl() }}"
                                class="h-20 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('id_card_front')" class="text-xs mt-1" />
                </div>

                {{-- Back --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Retro</span>
                    <input type="file" id="id_card_back" wire:model="id_card_back" wire:change="$refresh"
                        accept=".jpg,.jpeg,.png,.pdf" class="hidden" />
                    <button type="button" onclick="document.getElementById('id_card_back').click()"
                        class="w-full inline-flex justify-center items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 rounded-xl transition shadow-md hover:bg-amber-700 overflow-hidden">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>
                    <span class="block text-xs text-gray-400 font-sans italic mt-2 truncate">
                        {{ $this->id_card_back ? $this->id_card_back->getClientOriginalName() : 'Nessun file selezionato' }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($id_card_back)
                            <img src="{{ $id_card_back->temporaryUrl() }}"
                                class="h-20 w-full object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('id_card_back')" class="text-xs mt-1" />
                </div>
            </div>
        </div>

        <x-primary-button type="submit" wire:loading.attr="disabled"
            wire:target="uploadDocuments, driver_license_front, driver_license_back, id_card_front, id_card_back"
            class="w-full mt-4 flex justify-center items-center py-3 disabled:opacity-50">

            <span wire:loading.remove
                wire:target="uploadDocuments, driver_license_front, driver_license_back, id_card_front, id_card_back">
                Invia Documenti
            </span>

            <span wire:loading wire:target="driver_license_front, driver_license_back, id_card_front, id_card_back">
                Caricamento...
            </span>

            <span wire:loading wire:target="uploadDocuments">
                Invio in corso...
            </span>
        </x-primary-button>
    </form>
</div>
