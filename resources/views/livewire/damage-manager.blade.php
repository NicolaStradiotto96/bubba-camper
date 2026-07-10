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

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-1 text-center">
            {{ $damageId ? 'Modifica Danno' : 'Segnala Danno' }}
        </h2>

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-1 text-center">
            Prenotazione <strong class="id">#{{ $booking->id }}</strong>
        </h2>

        {{-- DETAILS --}}
        <div class="text-center mb-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Veicolo: <strong
                    class="text-gray-900 dark:text-white">{{ $booking->camper?->name ?? 'Veicolo non specificato' }}</strong>
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Cliente: <strong class="text-gray-900 dark:text-white">{{ $booking->customer_first_name }}
                    {{ $booking->customer_last_name }}</strong>
            </p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-center font-bold">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="saveDamage" class="space-y-6">

            {{-- DAMAGE DETAILS --}}
            <section class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-base sm:text-lg font-bold text-amber-500 uppercase tracking-widest mb-4 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Dettagli del danno
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    {{-- Price --}}
                    <div>
                        <x-input-label for="amount" value="Importo Penale (€)" />
                        <x-text-input wire:model="amount" id="amount" class="block mt-1 w-full text-center"
                            type="number" step="0.01" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    {{-- Description --}}
                    <div>
                        <x-input-label for="description" value="Descrizione Danno" />
                        <textarea wire:model="description" id="description" rows="3"
                            class="text-center mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm resize-none"
                            placeholder="Dettagli sulla natura del danno..."></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Photos --}}
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-amber-500 font-bold uppercase mb-6 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-camera"></i> Foto del Danno
                        </h3>

                        <div class="w-full text-center">

                            <input type="file" id="photos" wire:model="temporary_photos" multiple accept="image/*"
                                class="hidden" />

                            <button type="button" onclick="document.getElementById('photos').click()"
                                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-widest py-2.5 px-5 rounded-xl transition shadow-md">
                                <i class="fa-solid fa-plus"></i> Aggiungi Foto
                            </button>

                            <div class="mt-4 min-h-[82px] max-h-20 flex flex-wrap justify-center gap-3 overflow-y-auto">
                                @foreach ($photos as $index => $photo)
                                    <div class="relative shadow rounded-lg overflow-hidden border border-amber-500">
                                        <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover">

                                        <button type="button" wire:click="removePhoto({{ $index }})"
                                            class="absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-bl flex items-center justify-center transition-colors focus:outline-none">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Existing Photos --}}
                            @if (count($existing_photos) > 0)
                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs uppercase font-black tracking-wider text-gray-400 mb-3">Foto attuali:
                                    </p>
                                    <div class="flex flex-wrap justify-center gap-3">
                                        @foreach ($existing_photos as $photo)
                                            <div
                                                class="relative shadow rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                                <img src="{{ asset('storage/' . $photo->path) }}"
                                                    class="h-20 w-20 object-cover">

                                                <button type="button"
                                                    wire:click="removeExistingPhoto({{ $photo->id }})"
                                                    wire:confirm="Vuoi eliminare questa foto?"
                                                    class="absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-bl flex items-center justify-center transition-colors focus:outline-none">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-center gap-3">
                <x-secondary-button href="{{ route('dashboard') }}" wire:navigate
                    class="w-full sm:w-auto justify-center">
                    Annulla
                </x-secondary-button>

                <x-primary-button wire:loading.attr="disabled" wire:target="photos, temporary_photos, saveDamage"
                    class="w-full sm:w-auto justify-center">

                    <span wire:loading.remove wire:target="photos, temporary_photos, saveDamage">
                        {{ $damageId ? 'Aggiorna Danno' : 'Notifica Danno' }}
                    </span>
                    <span wire:loading wire:target="photos, temporary_photos, saveDamage">
                        Caricamento...
                    </span>
                </x-primary-button>
            </div>

        </form>
    </div>
</div>
