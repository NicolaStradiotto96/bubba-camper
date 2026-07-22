<div x-data="{ uploading: false }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-error="uploading = false" class="space-y-4">

    <form class="space-y-4">
        <input type="hidden" wire:model="bookingId">

        {{-- Driver License --}}
        <div class="bg-gray-200/50 dark:bg-gray-900/20 p-3 rounded-[2rem] border border-gray-200 dark:border-gray-700">

            <label class="block text-xl font-black text-gray-900 dark:text-gray-100 uppercase mb-3 text-center">Patente
                di Guida</label>

            <div class="grid grid-cols-2 gap-4">

                {{-- Front --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Fronte</span>

                    <input type="file" id="driver_license_front" wire:model="driver_license_front"
                        wire:change="$refresh" accept=".png,.jpg,.jpeg,application/pdf" class="hidden"
                        {{ $existingFiles['driver_license_front'] ?? false ? 'disabled' : '' }} />

                    <button type="button" onclick="document.getElementById('driver_license_front').click()"
                        {{ $existingFiles['driver_license_front'] ?? false ? 'disabled' : '' }}
                        class="w-full inline-flex justify-center items-center gap-2 text-white text-xs font-black uppercase tracking-widest py-2 rounded-[2rem] shadow-md overflow-hidden focus:outline-none focus:ring-2 focus:ring-amber-500 transition focus:ring-offset-2 dark:focus:ring-offset-gray-800
                        {{ $existingFiles['driver_license_front'] ?? false ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-amber-600 hover:bg-amber-700' }}">
                        <i
                            class="fa-solid {{ $existingFiles['driver_license_front'] ?? false ? 'fa-check' : 'fa-cloud-arrow-up' }}"></i>
                        {{ $existingFiles['driver_license_front'] ?? false ? 'Caricato' : 'Sfoglia...' }}
                    </button>

                    <span
                        class="block text-xs font-sans italic mt-2 truncate 
                        {{ $existingFiles['driver_license_front'] ?? false ? 'text-green-600 font-bold' : 'text-gray-400' }}">

                        {{ $existingFiles['driver_license_front'] ?? false
                            ? 'Documento caricato'
                            : ($this->driver_license_front
                                ? $this->driver_license_front->getClientOriginalName()
                                : 'Nessun file selezionato') }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($driver_license_front)
                            @if (in_array($driver_license_front->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                <img src="{{ $driver_license_front->temporaryUrl() }}"
                                    class="h-20 w-full object-cover rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                            @else
                                <div
                                    class="h-20 w-full flex items-center justify-center bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-3xl"></i>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="min-h-5 text-center mt-1">
                        <x-input-error :messages="$errors->get('driver_license_front')" />
                    </div>
                </div>

                {{-- Back --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Retro</span>

                    <input type="file" id="driver_license_back" wire:model="driver_license_back"
                        wire:change="$refresh" accept=".png,.jpg,.jpeg,application/pdf" class="hidden"
                        {{ $existingFiles['driver_license_back'] ?? false ? 'disabled' : '' }} />

                    <button type="button" onclick="document.getElementById('driver_license_back').click()"
                        {{ $existingFiles['driver_license_back'] ?? false ? 'disabled' : '' }}
                        class="w-full inline-flex justify-center items-center gap-2 text-white text-xs font-black uppercase tracking-widest py-2 rounded-[2rem] shadow-md overflow-hidden focus:outline-none focus:ring-2 focus:ring-amber-500 transition focus:ring-offset-2 dark:focus:ring-offset-gray-800
                        {{ $existingFiles['driver_license_back'] ?? false ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-amber-600 hover:bg-amber-700' }}">
                        <i
                            class="fa-solid {{ $existingFiles['driver_license_back'] ?? false ? 'fa-check' : 'fa-cloud-arrow-up' }}"></i>
                        {{ $existingFiles['driver_license_back'] ?? false ? 'Caricato' : 'Sfoglia...' }}
                    </button>

                    <span
                        class="block text-xs font-sans italic mt-2 truncate 
                        {{ $existingFiles['driver_license_back'] ?? false ? 'text-green-600 font-bold' : 'text-gray-400' }}">

                        {{ $existingFiles['driver_license_back'] ?? false
                            ? 'Documento caricato'
                            : ($this->driver_license_back
                                ? $this->driver_license_back->getClientOriginalName()
                                : 'Nessun file selezionato') }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($driver_license_back)
                            @if (in_array($driver_license_back->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                <img src="{{ $driver_license_back->temporaryUrl() }}"
                                    class="h-20 w-full object-cover rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                            @else
                                <div
                                    class="h-20 w-full flex items-center justify-center bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-3xl"></i>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="min-h-5 text-center mt-1">
                        <x-input-error :messages="$errors->get('driver_license_back')" />
                    </div>
                </div>
            </div>
        </div>

        {{-- ID Card --}}
        <div class="bg-gray-200/50 dark:bg-gray-900/20 p-3 rounded-[2rem] border border-gray-200 dark:border-gray-700">

            <label class="block text-xl font-black text-gray-900 dark:text-gray-100 uppercase mb-3 text-center">Carta
                d'Identità</label>

            <div class="grid grid-cols-2 gap-4">

                {{-- Front --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Fronte</span>

                    <input type="file" id="id_card_front" wire:model="id_card_front" wire:change="$refresh"
                        accept=".png,.jpg,.jpeg,application/pdf" class="hidden"
                        {{ $existingFiles['id_card_front'] ?? false ? 'disabled' : '' }} />

                    <button type="button" onclick="document.getElementById('id_card_front').click()"
                        {{ $existingFiles['id_card_front'] ?? false ? 'disabled' : '' }}
                        class="w-full inline-flex justify-center items-center gap-2 text-white text-xs font-black uppercase tracking-widest py-2 rounded-[2rem] shadow-md overflow-hidden focus:outline-none focus:ring-2 focus:ring-amber-500 transition focus:ring-offset-2 dark:focus:ring-offset-gray-800
                        {{ $existingFiles['id_card_front'] ?? false ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-amber-600 hover:bg-amber-700' }}">
                        <i
                            class="fa-solid {{ $existingFiles['id_card_front'] ?? false ? 'fa-check' : 'fa-cloud-arrow-up' }}"></i>
                        {{ $existingFiles['id_card_front'] ?? false ? 'Caricato' : 'Sfoglia...' }}
                    </button>

                    <span
                        class="block text-xs font-sans italic mt-2 truncate 
                        {{ $existingFiles['id_card_front'] ?? false ? 'text-green-600 font-bold' : 'text-gray-400' }}">

                        {{ $existingFiles['id_card_front'] ?? false
                            ? 'Documento caricato'
                            : ($this->id_card_front
                                ? $this->id_card_front->getClientOriginalName()
                                : 'Nessun file selezionato') }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($id_card_front)
                            @if (in_array($id_card_front->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                <img src="{{ $id_card_front->temporaryUrl() }}"
                                    class="h-20 w-full object-cover rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                            @else
                                <div
                                    class="h-20 w-full flex items-center justify-center bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-3xl"></i>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="min-h-5 text-center mt-1">
                        <x-input-error :messages="$errors->get('id_card_front')" />
                    </div>
                </div>

                {{-- Back --}}
                <div class="text-center">
                    <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Retro</span>

                    <input type="file" id="id_card_back" wire:model="id_card_back" wire:change="$refresh"
                        accept=".png,.jpg,.jpeg,application/pdf" class="hidden"
                        {{ $existingFiles['id_card_back'] ?? false ? 'disabled' : '' }} />

                    <button type="button" onclick="document.getElementById('id_card_back').click()"
                        {{ $existingFiles['id_card_back'] ?? false ? 'disabled' : '' }}
                        class="w-full inline-flex justify-center items-center gap-2 text-white text-xs font-black uppercase tracking-widest py-2 rounded-[2rem] shadow-md overflow-hidden focus:outline-none focus:ring-2 focus:ring-amber-500 transition focus:ring-offset-2 dark:focus:ring-offset-gray-800
                        {{ $existingFiles['id_card_back'] ?? false ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-amber-600 hover:bg-amber-700' }}">
                        <i
                            class="fa-solid {{ $existingFiles['id_card_back'] ?? false ? 'fa-check' : 'fa-cloud-arrow-up' }}"></i>
                        {{ $existingFiles['id_card_back'] ?? false ? 'Caricato' : 'Sfoglia...' }}
                    </button>

                    <span
                        class="block text-xs font-sans italic mt-2 truncate 
                        {{ $existingFiles['id_card_back'] ?? false ? 'text-green-600 font-bold' : 'text-gray-400' }}">

                        {{ $existingFiles['id_card_back'] ?? false
                            ? 'Documento caricato'
                            : ($this->id_card_back
                                ? $this->id_card_back->getClientOriginalName()
                                : 'Nessun file selezionato') }}
                    </span>

                    <div class="h-20 flex items-center justify-center mt-2">
                        @if ($id_card_back)
                            @if (in_array($id_card_back->getClientOriginalExtension(), ['jpg', 'jpeg', 'png']))
                                <img src="{{ $id_card_back->temporaryUrl() }}"
                                    class="h-20 w-full object-cover rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                            @else
                                <div
                                    class="h-20 w-full flex items-center justify-center bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-3xl"></i>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="min-h-5 text-center mt-1">
                        <x-input-error :messages="$errors->get('id_card_back')" />
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center gap-2 px-2">
                <input type="checkbox" id="accept_documents_policy" wire:model="accept_documents_policy" required
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-amber-500 shadow-sm focus:ring-amber-500 dark:focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition">
                <label for="accept_documents_policy" class="text-xs text-gray-600 dark:text-gray-400">
                    Autorizzo espressamente il trattamento dei documenti personali d'identità e di guida ai fini della
                    verifica e la conferma del noleggio, come da <a href="{{ route('terms') }}"
                        class="text-amber-500 hover:underline focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Termini
                        e Condizioni</a>.
            </div>
            <div class="min-h-5 text-center mt-1">
                <x-input-error :messages="$errors->get('accept_documents_policy')" />
            </div>
        </div>

        <x-primary-button type="button" wire:click="uploadDocuments" wire:loading.attr="disabled"
            wire:target="uploadDocuments, driver_license_front, driver_license_back, id_card_front, id_card_back"
            class="w-full mt-4 flex justify-center items-center disabled:opacity-50">

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
