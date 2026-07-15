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

        <form class="space-y-6" novalidate>

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
                        <x-text-input x-price wire:model.blur="amount" id="amount"
                            class="block mt-1 w-full text-center" type="text" step="0.01" />
                        <x-input-error :messages="$errors->get('amount')" class="text-center mt-1" />
                    </div>

                    {{-- Description --}}
                    <div>
                        <x-input-label for="description" value="Descrizione Danno" />
                        <textarea wire:model.blur="description" id="description" rows="3"
                            class="text-center mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-amber-500 dark:focus:ring-amber-500 rounded-md shadow-sm resize-none"
                            placeholder="Dettagli sulla natura del danno..."></textarea>
                        <x-input-error :messages="$errors->get('description')" class="text-center mt-1" />
                    </div>

                    {{-- Photos --}}
                    <div class="p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-amber-500 font-bold uppercase mb-6 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-camera"></i> Foto del Danno
                        </h3>

                        <div class="w-full text-center">

                            <input type="file" id="photos" wire:model.blur="temporary_photos" multiple
                                accept=".png,.jpg,.jpeg,application/pdf" class="hidden" />

                            <button type="button" onclick="document.getElementById('photos').click()"
                                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition text-white text-xs font-black uppercase tracking-widest py-2.5 px-5 rounded-xl shadow-md">
                                <i class="fa-solid fa-plus"></i> Aggiungi Foto
                            </button>

                            <div class="mt-4 min-h-[82px] max-h-20 flex flex-wrap justify-center gap-3 overflow-y-auto">
                                @foreach ($photos as $index => $photo)
                                    <div class="relative shadow rounded-lg overflow-hidden border border-amber-500">
                                        @if (in_array($photo->extension(), ['png', 'jpg', 'jpeg']))
                                            <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover">
                                        @else
                                            <div
                                                class="h-20 w-20 flex items-center justify-center bg-gray-200 dark:bg-gray-900">

                                                <i
                                                    class="fa-solid fa-file-{{ $photo->extension() === 'pdf' ? 'pdf' : 'code' }} text-2xl 
                                                    {{ $photo->extension() === 'pdf' ? 'text-red-500' : 'text-gray-500' }}"></i>
                                            </div>
                                        @endif

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
                                    <p class="text-xs uppercase font-black tracking-wider text-gray-400 mb-3">Foto
                                        attuali:
                                    </p>
                                    <div class="flex flex-wrap justify-center gap-3">
                                        @foreach ($existing_photos as $photo)
                                            <div
                                                class="relative shadow rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                                @if (str_ends_with(strtolower($photo->path), '.pdf'))
                                                    <div
                                                        class="h-20 w-20 flex items-center justify-center bg-gray-200 dark:bg-gray-900">
                                                        <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . $photo->path) }}"
                                                        class="h-20 w-20 object-cover">
                                                @endif

                                                <button type="button"
                                                    onclick="confirmAction({{ $photo->id }}, 'ELIMINARE LA FOTO?', 'Questa azione non può essere annullata.', 'removeExistingPhoto')"
                                                    class="absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white w-6 h-6 rounded-bl flex items-center justify-center transition-colors focus:outline-none">
                                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($errors->has('photos.*'))
                            <div class="mt-1">
                                @foreach ($errors->get('photos.*') as $message)
                                    <x-input-error :messages="$message[0]" class="text-center mt-1" />
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-center gap-3">
                <x-secondary-button href="{{ route('dashboard') }}" wire:navigate
                    class="w-full sm:w-auto justify-center">
                    Annulla
                </x-secondary-button>

                <x-primary-button type="button" wire:click="saveDamage" wire:loading.attr="disabled"
                    wire:target="photos, temporary_photos, saveDamage"
                    class="w-full sm:w-auto justify-center disabled:opacity-50 disabled:cursor-wait">

                    {{ $damageId ? 'Aggiorna Danno' : 'Notifica Danno' }}

                </x-primary-button>
            </div>

        </form>
    </div>
</div>
