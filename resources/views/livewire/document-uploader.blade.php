<div x-data="{ uploading: false }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-error="uploading = false" class="space-y-4">
    <form wire:submit.prevent="uploadDocuments">

        <input type="hidden" wire:model="bookingId">

        <div class="flex justify-center items-center gap-2">

            {{-- Driver License --}}
            <div class="w-full text-center">
                <label class="block text-sm font-bold text-gray-900 dark:text-gray-100 uppercase mb-2">Patente di
                    Guida</label>

                <div class="flex flex-col justify-center items-center">
                    <input type="file" id="driver_license" wire:model="driver_license" accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        onchange="document.getElementById('text-license').innerText = this.files[0] ? this.files[0].name : 'Nessun file'" />

                    <button type="button" onclick="document.getElementById('driver_license').click()"
                        class="inline-flex items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 px-4 rounded-xl transition shadow-md">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>

                    <span wire:ignore id="text-license" class="text-xs text-gray-400 font-sans italic mt-1">Nessun
                        file selezionato</span>
                </div>

                <div class="min-h-[40px] text-center mt-1">
                    <x-input-error :messages="$errors->get('driver_license')" class="text-xs" />
                </div>
            </div>

            {{-- ID Card --}}
            <div class="w-full text-center">
                <label class="block text-sm font-bold text-gray-900 dark:text-gray-100 uppercase mb-2">Carta
                    d'Identità</label>

                <div class="flex flex-col justify-center items-center">
                    <input type="file" id="id_card" wire:model="id_card" accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        onchange="document.getElementById('text-idcard').innerText = this.files[0] ? this.files[0].name : 'Nessun file'" />

                    <button type="button" onclick="document.getElementById('id_card').click()"
                        class="inline-flex items-center gap-2 bg-amber-600 text-white text-xs font-black uppercase tracking-widest py-2 px-4 rounded-xl transition shadow-md">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Sfoglia...
                    </button>

                    <span wire:ignore id="text-idcard" class="text-xs text-gray-400 font-sans italic mt-1">Nessun
                        file selezionato</span>
                </div>

                <div class="min-h-[40px] text-center mt-1">
                    <x-input-error :messages="$errors->get('id_card')" class="text-xs" />
                </div>
            </div>
        </div>

        <x-primary-button type="submit" x-bind:disabled="uploading"
            class="w-full mt-1 flex justify-center items-center">

            <span x-show="!uploading" wire:loading.remove wire:target="uploadDocuments">
                Invia Documenti
            </span>

            <span x-show="uploading" style="display: none;">
                Attendi...
            </span>
            <span wire:loading wire:target="uploadDocuments" style="display: none;">
                Invio in corso...
            </span>
        </x-primary-button>
    </form>
</div>
