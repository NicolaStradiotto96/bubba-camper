<div class="min-h-[calc(100vh-160px)] mx-4" x-data="{ open: false }" @open-modal.window="open = true">
    <div
        class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase mb-6 text-center">
            Gestione Danni
        </h2>

        {{-- SEARCH BAR --}}
        <div class="mb-6 relative max-w-lg mx-auto">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search_id" placeholder="Cerca per ID Prenotazione..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">

                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2 text-center">Digita l'ID della prenotazione per filtrare i risultati
            </p>
        </div>

        {{-- TABLE --}}
        <div
            class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-8">
            <table class="w-full text-center">
                <thead class="font-black uppercase text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-2 py-4">ID</th>
                        <th class="px-2 py-4">Camper</th>
                        <th class="px-2 py-4">Stato</th>
                        <th class="px-2 py-4">Descrizione</th>
                        <th class="px-2 py-4">Importo</th>
                        <th class="px-2 py-4">Foto</th>
                        <th class="px-2 py-4">Azioni</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($damages as $d)
                        <tr class="font-black text-gray-800 dark:text-gray-200 hover:bg-gray-100/50 dark:hover:bg-gray-700/30 transition cursor-pointer"
                            wire:click="showDamage({{ $d->id }})">

                            {{-- ID --}}
                            <td class="px-2 py-4">
                                <span
                                    class="text-xs text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 py-1 px-1">
                                    #{{ $d->booking_id }}
                                </span>
                            </td>

                            {{-- Camper --}}
                            <td class="px-2 py-4 text-sm text-amber-500">
                                {{ $d->booking->camper->name ?? 'N/A' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-2 py-4 text-xs uppercase">
                                @if ($d->status === 'paid')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Pagato
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        In attesa
                                    </span>
                                @endif
                            </td>

                            {{-- Description --}}
                            <td class="px-2 py-4 text-sm font-normal italic">
                                {{ Str::limit($d->description, 40) }}
                            </td>

                            {{-- Costs --}}
                            <td class="px-2 py-4">{{ number_format($d->amount, 2, ',', '') }}€</td>

                            {{-- Photos --}}
                            <td class="px-2 py-4">
                                <span
                                    class="text-xs font-black text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 px-2 py-1 rounded-full">
                                    {{ $d->photos->count() }}
                                </span>
                            </td>

                            {{-- Buttons --}}
                            <td class="px-2 py-4" @click.stop>
                                <div class="flex justify-center gap-2">
                                    @if ($d->status === 'paid')
                                        <span class="text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </span>
                                        <span class="text-gray-300 dark:text-gray-600 cursor-not-allowed">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </span>
                                    @else
                                        <a href="{{ route('damage.edit', ['booking' => $d->booking_id, 'damage_id' => $d->id]) }}"
                                            wire:navigate class="text-amber-500 hover:text-amber-600 transition">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>
                                        <button wire:click="removeDamage({{ $d->id }})"
                                            wire:confirm="Sei sicuro di voler eliminare questo danno?"
                                            class="text-red-500 hover:text-red-600 transition">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-2 py-10 text-center text-gray-400 font-black uppercase tracking-widest">
                                Nessun danno segnalato.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $damages->links() }}
        </div>

    </div>

    {{-- MODAL --}}
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-3 overflow-y-auto"
        style="display: none;">

        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
            x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
            class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl sm:my-3 w-full sm:max-w-2xl border-2 border-gray-200 dark:border-gray-700"
            @click.away="open = false">

            @if ($selectedDamage)
                <div
                    class="bg-gray-50 dark:bg-gray-700/50 font-black p-4 border-b border-gray-100 dark:border-gray-700">
                    <div class=" flex justify-between items-center">
                        <h3 class="text-xl text-gray-900 dark:text-white uppercase tracking-wider">
                            Danno Prenotazione
                            <span
                                class="bg-gray-200 dark:bg-gray-900 py-1 px-2">#{{ $selectedDamage->booking_id }}</span>
                        </h3>
                        <button @click="open = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 p-1 rounded-lg transition-colors focus:outline-none">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="pt-1">
                        <p class="text-xs text-gray-400 uppercase tracking-widest">
                            Segnalato il:
                            <span class="text-gray-700 dark:text-gray-200">
                                {{ $selectedDamage->created_at->format('d/m/Y') }}
                                alle
                                {{ $selectedDamage->created_at->format('H:i') }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="p-6 space-y-6 text-center">
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <p class="text-lg font-black text-gray-400 uppercase tracking-widest mb-1">Stato:</p>
                        <p
                            class="inline-block font-black px-2 py-0.5 rounded-full border bg-white dark:bg-gray-900 {{ $selectedDamage->status === 'paid' ? 'border-green-500 text-green-500' : 'border-amber-500 text-amber-500 animate-pulse' }}">
                            {{ $selectedDamage->status === 'paid' ? 'PAGATO' : 'IN ATTESA' }}
                        </p>
                    </div>

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <p class="text-lg font-black text-gray-400 uppercase tracking-widest mb-1">Descrizione:</p>
                        <p class="font-black text-gray-900 dark:text-white">{{ $selectedDamage->description }}</p>
                    </div>

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                        <p class="text-lg font-black text-gray-400 uppercase tracking-widest mb-1">Importo:</p>
                        <p class="font-black text-2xl text-amber-500">
                            {{ number_format($selectedDamage->amount, 2, ',', '') }}€</p>
                    </div>

                    @if ($selectedDamage->photos->count() > 0)
                        <div class="flex flex-col items-center">
                            <p class="text-lg font-black text-gray-400 uppercase tracking-widest mb-3">Foto allegate:
                            </p>

                            <div class="flex flex-wrap justify-center gap-3 w-full">
                                @foreach ($selectedDamage->photos as $photo)
                                    <div class="w-24 sm:w-32 cursor-pointer">
                                        <a href="{{ asset('storage/' . $photo->path) }}" class="glightbox">
                                            <img src="{{ asset('storage/' . $photo->path) }}"
                                                class="w-full rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:scale-105 transition-transform duration-200">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
