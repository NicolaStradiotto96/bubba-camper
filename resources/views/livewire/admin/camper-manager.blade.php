<div class="mx-4">
    <div
        class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-8 text-center">
            {{ $isEditMode ? 'Modifica Camper' : 'Crea Camper' }}
        </h2>

        <form wire:submit.prevent="saveCamper" class="space-y-8">

            {{-- GENERAL INFORMATIONS --}}
            <section class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-base sm:text-lg font-bold text-amber-500 uppercase tracking-widest mb-4 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-info-circle"></i> Informazioni Generali
                </h3>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-5">
                    <div class="grid grid-cols-1 justify-center items-center">
                        <div>
                            <x-input-label for="name" value="Nome Camper" />
                            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text"
                                placeholder="es: McLouis Glamys 226" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 justify-center items-center">
                        <div>
                            <x-input-label for="is_active" value="Stato" />
                            <label class="relative inline-flex items-center cursor-pointer select-none my-2">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">

                                <div
                                    class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600 dark:peer-checked:bg-amber-500">
                                </div>
                            </label>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-4">
                    <div>
                        <x-input-label value="Stagione Bassa (€)" />
                        <x-text-input wire:model="prices.low" type="number" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('prices.low')" />
                    </div>
                    <div>
                        <x-input-label value="Stagione Media (€)" />
                        <x-text-input wire:model="prices.mid" type="number" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('prices.mid')" />

                    </div>
                    <div>
                        <x-input-label value="Stagione Alta (€)" />
                        <x-text-input wire:model="prices.high" type="number" class="mt-1 w-full" />
                        <x-input-error :messages="$errors->get('prices.high')" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="description" value="Descrizione" />
                    <textarea wire:model="description" id="description" rows="3"
                        class="text-center mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm resize-none"></textarea>
                    <x-input-error :messages="$errors->get('description')" />

                </div>
            </section>

            {{-- MAIN INFORMATIONS --}}
            <section x-data="{ open: false }"
                class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                    <h3
                        class="font-bold text-amber-500 uppercase tracking-widest flex justify-center items-center gap-2">
                        <i class="fa-solid fa-van-shuttle"></i>Caratteristiche Principali
                    </h3>
                    <button type="button" class="text-gray-500 hover:text-amber-500 transition-colors">
                        <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <div x-show="open" x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="max-h-0 opacity-0" x-transition:enter-end="max-h-[2000px] opacity-100"
                    x-transition:leave="transition-all ease-in duration-300"
                    x-transition:leave-start="max-h-[2000px] opacity-100" x-transition:leave-end="max-h-0 opacity-0"
                    class="space-y-4 mt-4 overflow-hidden">

                    <div x-data="{ sub: 'Caratteristiche principali' }">

                        @foreach ($camperAttributes['main'] as $subCategory => $items)
                            <div x-show="sub === '{{ $subCategory }}'" class="space-y-3 p-3">
                                @foreach ($items as $index => $item)
                                    <div class="flex gap-2">
                                        <x-text-input
                                            wire:model="camperAttributes.main.{{ $subCategory }}.{{ $index }}.label"
                                            placeholder="Etichetta" class="w-1/2" />
                                        <x-text-input
                                            wire:model="camperAttributes.main.{{ $subCategory }}.{{ $index }}.value"
                                            placeholder="Valore" class="w-1/2" />
                                        <button type="button"
                                            wire:click="removeRow('main', '{{ $subCategory }}', {{ $index }})"
                                            class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <div class="flex justify-center items-center">
                                    <x-secondary-button type="button"
                                        wire:click="addRow('main', '{{ $subCategory }}')"
                                        class="text-amber-500 text-sm font-bold uppercase">
                                        + Aggiungi riga a {{ $subCategory }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- SPECS --}}
            <section x-data="{ open: false }"
                class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                    <h3
                        class="font-bold text-amber-500 uppercase tracking-widest flex justify-center items-center gap-2">
                        <i class="fa-solid fa-gears"></i>Caratteristiche Tecniche
                    </h3>
                    <button type="button" class="text-gray-500 hover:text-amber-500 transition-colors">
                        <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <div x-show="open" x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="max-h-0 opacity-0" x-transition:enter-end="max-h-[2000px] opacity-100"
                    x-transition:leave="transition-all ease-in duration-300"
                    x-transition:leave-start="max-h-[2000px] opacity-100" x-transition:leave-end="max-h-0 opacity-0"
                    class="space-y-4 mt-4 overflow-hidden">

                    <div x-data="{ sub: 'Caratteristiche tecniche' }">
                        <select x-model="sub"
                            class="w-full mb-4 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:border-amber-500 dark:focus:border-amber-500 focus:outline-none focus:ring-0">
                            @foreach (array_keys($camperAttributes['specs']) as $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>

                        @foreach ($camperAttributes['specs'] as $subCategory => $items)
                            <div x-show="sub === '{{ $subCategory }}'" class="space-y-3 p-3">
                                @foreach ($items as $index => $item)
                                    <div class="flex gap-2">
                                        <x-text-input
                                            wire:model="camperAttributes.specs.{{ $subCategory }}.{{ $index }}.label"
                                            placeholder="Etichetta" class="w-1/2" />
                                        <x-text-input
                                            wire:model="camperAttributes.specs.{{ $subCategory }}.{{ $index }}.value"
                                            placeholder="Valore" class="w-1/2" />
                                        <button type="button"
                                            wire:click="removeRow('specs', '{{ $subCategory }}', {{ $index }})"
                                            class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <div class="flex justify-center items-center">
                                    <x-secondary-button type="button"
                                        wire:click="addRow('specs', '{{ $subCategory }}')"
                                        class="text-amber-500 text-sm font-bold uppercase">
                                        + Aggiungi riga a {{ $subCategory }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- EQUIPMENT --}}
            <section x-data="{ open: false }"
                class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                    <h3
                        class="font-bold text-amber-500 uppercase tracking-widest flex justify-center items-center gap-2">
                        <i class="fa-solid fa-toolbox"></i>Equipaggiamento
                    </h3>
                    <button type="button" class="text-gray-500 hover:text-amber-500 transition-colors">
                        <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <div x-show="open" x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="max-h-0 opacity-0" x-transition:enter-end="max-h-[2000px] opacity-100"
                    x-transition:leave="transition-all ease-in duration-300"
                    x-transition:leave-start="max-h-[2000px] opacity-100" x-transition:leave-end="max-h-0 opacity-0"
                    class="space-y-4 mt-4 overflow-hidden">

                    <div x-data="{ sub: 'Alla guida' }">
                        <select x-model="sub"
                            class="w-full mb-4 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:border-amber-500 dark:focus:border-amber-500 focus:outline-none focus:ring-0">
                            @foreach (array_keys($camperAttributes['equipment']) as $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>

                        @foreach ($camperAttributes['equipment'] as $subCategory => $items)
                            <div x-show="sub === '{{ $subCategory }}'" class="space-y-3 p-3">
                                @foreach ($items as $index => $item)
                                    <div class="flex gap-2">
                                        <x-text-input
                                            wire:model="camperAttributes.equipment.{{ $subCategory }}.{{ $index }}.label"
                                            placeholder="Etichetta" class="w-1/2" />
                                        <x-text-input
                                            wire:model="camperAttributes.equipment.{{ $subCategory }}.{{ $index }}.value"
                                            placeholder="Valore" class="w-1/2" />
                                        <button type="button"
                                            wire:click="removeRow('equipment', '{{ $subCategory }}', {{ $index }})"
                                            class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <div class="flex justify-center items-center">
                                    <x-secondary-button type="button"
                                        wire:click="addRow('equipment', '{{ $subCategory }}')"
                                        class="text-amber-500 text-sm font-bold uppercase">
                                        + Aggiungi riga a {{ $subCategory }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- POLICIES --}}
            <section x-data="{ open: false }"
                class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">

                <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                    <h3
                        class="font-bold text-amber-500 uppercase tracking-widest flex justify-center items-center gap-2">
                        <i class="fa-solid fa-hand-holding-dollar"></i>Cauzione
                    </h3>
                    <button type="button" class="text-gray-500 hover:text-amber-500 transition-colors">
                        <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>

                <div x-show="open" x-transition:enter="transition-all ease-out duration-300"
                    x-transition:enter-start="max-h-0 opacity-0" x-transition:enter-end="max-h-[2000px] opacity-100"
                    x-transition:leave="transition-all ease-in duration-300"
                    x-transition:leave-start="max-h-[2000px] opacity-100" x-transition:leave-end="max-h-0 opacity-0"
                    class="space-y-4 mt-4 overflow-hidden">

                    <div x-data="{ sub: 'Cauzione' }">

                        @foreach ($camperAttributes['policies'] as $subCategory => $items)
                            <div x-show="sub === '{{ $subCategory }}'" class="space-y-3 p-3">
                                @foreach ($items as $index => $item)
                                    <div class="flex gap-2">
                                        <x-text-input
                                            wire:model="camperAttributes.policies.{{ $subCategory }}.{{ $index }}.label"
                                            placeholder="Etichetta" class="w-1/2" />
                                        <x-text-input
                                            wire:model="camperAttributes.policies.{{ $subCategory }}.{{ $index }}.value"
                                            placeholder="Valore" class="w-1/2" />
                                        <button type="button"
                                            wire:click="removeRow('policies', '{{ $subCategory }}', {{ $index }})"
                                            class="text-red-500 hover:text-red-700 p-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <div class="flex justify-center items-center">
                                    <x-secondary-button type="button"
                                        wire:click="addRow('policies', '{{ $subCategory }}')"
                                        class="text-amber-500 text-sm font-bold uppercase">
                                        + Aggiungi riga a {{ $subCategory }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- IMAGES --}}
            <section class="p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3 class="text-amber-500 font-bold uppercase mb-6 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-images"></i> Contenuti Multimediali
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- Main Image --}}
                    <div
                        class="w-full text-center flex flex-col justify-between p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-900/30">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100 uppercase mb-4">
                                Foto Principale
                            </label>

                            <div class="flex flex-col justify-center items-center">
                                <input type="file" id="main_image" wire:model="main_image" accept="image/*"
                                    class="hidden"
                                    onchange="document.getElementById('text-main-image').innerText = this.files[0] ? this.files[0].name : 'Nessun file selezionato'" />

                                <button type="button" onclick="document.getElementById('main_image').click()"
                                    class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-widest py-2.5 px-5 rounded-xl transition shadow-md focus:outline-none">
                                    <i class="fa-solid fa-camera"></i> Sfoglia...
                                </button>

                                <span wire:ignore id="text-main-image"
                                    class="text-xs text-gray-400 font-sans italic mt-2 block max-w-full truncate px-4">
                                    Nessun file selezionato
                                </span>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="mt-4 min-h-[96px] flex flex-col items-center justify-center">
                            @if ($main_image && empty($errors->get('main_image')))
                                <span class="text-xs uppercase font-black tracking-wider text-amber-500 mb-1">Nuova
                                    immagine</span>
                                <div
                                    class="relative inline-block shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <img src="{{ $main_image->temporaryUrl() }}" class="h-24 w-40 object-cover">
                                </div>
                            @elseif ($isEditMode && $image_path)
                                <span
                                    class="text-xs uppercase font-black tracking-wider text-gray-400 mb-1">Immagine
                                    attuale</span>
                                <div
                                    class="relative inline-block shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                    <img src="{{ asset('storage/' . $image_path) }}" class="h-24 w-40 object-cover">
                                </div>
                            @else
                                <div class="text-gray-300 dark:text-gray-600 text-4xl">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </div>

                        <div class="min-h-[24px] text-center mt-2">
                            <x-input-error :messages="$errors->get('main_image')" class="text-xs" />
                        </div>
                    </div>

                    {{-- Gallery Images --}}
                    <div
                        class="w-full text-center flex flex-col justify-between p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50/50 dark:bg-gray-900/30">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100 uppercase mb-4">
                                Galleria Immagini
                            </label>

                            <div class="flex flex-col justify-center items-center">
                                <input type="file" id="images" wire:model="images" multiple accept="image/*"
                                    class="hidden"
                                    onchange="document.getElementById('text-gallery-images').innerText = this.files.length > 0 ? this.files.length + ' file selezionati' : 'Nessun file selezionato'" />

                                <button type="button" onclick="document.getElementById('images').click()"
                                    class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black uppercase tracking-widest py-2.5 px-5 rounded-xl transition shadow-md focus:outline-none">
                                    <i class="fa-solid fa-camera"></i> Sfoglia...
                                </button>

                                <span wire:ignore id="text-gallery-images"
                                    class="text-xs text-gray-400 font-sans italic mt-2 block">
                                    Nessun file selezionato
                                </span>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="mt-4 min-h-[96px] flex flex-col items-center justify-center w-full">

                            @if ($images && empty($errors->get('images.*')))
                                <span class="text-xs uppercase font-black tracking-wider text-amber-500 mb-1">Nuove immagini</span>
                                <div
                                    class="flex flex-wrap justify-center gap-3 max-h-24 overflow-y-auto p-1 w-full mb-3">
                                    @foreach ($images as $index => $img)
                                        <div
                                            class="relative shadow rounded-lg overflow-hidden border border-amber-500 group">
                                            <img src="{{ $img->temporaryUrl() }}" class="h-16 w-16 object-cover">
                                            <button type="button" wire:click="removeNewImage({{ $index }})"
                                                class="absolute top-0 right-0 bg-red-500 hover:bg-red-600 text-white w-4 h-4 rounded-bl flex items-center justify-center transition-colors focus:outline-none">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if ($isEditMode && !empty($old_images))
                                <span class="text-xs uppercase font-black tracking-wider text-gray-400 mb-1">Galleria attuale</span>
                                <div class="flex flex-wrap justify-center gap-3 max-h-24 overflow-y-auto p-1 w-full">
                                    @foreach ($old_images as $index => $old_img)
                                        <div
                                            class="relative shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 group">
                                            <img src="{{ asset('storage/' . $old_img) }}"
                                                class="h-16 w-16 object-cover opacity-85">
                                            <button type="button" wire:click="removeOldImage({{ $index }})"
                                                class="absolute top-0 right-0 bg-red-600 hover:bg-red-700 text-white w-4 h-4 rounded-bl flex items-center justify-center transition-colors focus:outline-none"
                                                title="Elimina definitivamente questa foto">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if (!$images && (!$isEditMode || empty($old_images)))
                                <div class="text-gray-300 dark:text-gray-600 text-4xl">
                                    <i class="fa-solid fa-images"></i>
                                </div>
                            @endif
                        </div>

                        <div class="min-h-[24px] text-center mt-2">
                            <x-input-error :messages="$errors->get('images.*')" class="text-xs" />
                        </div>
                    </div>

                </div>
            </section>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

                <div class="flex items-center gap-3 w-full sm:w-auto justify-start">
                    @if ($isEditMode)
                        <x-danger-button type="button" @click="confirmCamperDeletion($wire)"
                            class="w-full sm:w-auto justify-center">
                            Elimina Camper
                        </x-danger-button>
                    @endif
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <x-primary-button wire:loading.attr="disabled" class="w-full sm:w-auto justify-center">
                        {{ $isEditMode ? 'Salva Modifiche' : 'Crea Camper' }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</div>
