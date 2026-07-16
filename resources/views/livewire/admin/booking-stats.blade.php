<div class="max-w-7xl mx-auto" wire:poll.60s>
    <div
        class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 mx-4">

        <div class="px-4 sm:px-0 mb-6">
            <h2
                class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
                Statistiche
            </h2>
        </div>

        {{-- CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mx-4">
            @foreach ([['label' => 'Incasso', 'value' => number_format($this->stats['counts']['earnings'], 0, ',', '') . '€', 'icon' => 'fa-solid fa-coins', 'color' => 'green', 'isPending' => false], ['label' => 'Totali', 'value' => $this->stats['counts']['total'], 'icon' => 'fa-solid fa-boxes-stacked', 'color' => 'green', 'isPending' => false], ['label' => 'Confermate', 'value' => $this->stats['counts']['confirmed'], 'icon' => 'fa-solid fa-circle-check', 'color' => 'green', 'isPending' => false], ['label' => 'In Attesa', 'value' => $this->stats['totalPending'], 'icon' => 'fa-solid fa-clock', 'color' => $this->stats['totalPending'] > 0 ? 'amber' : 'green', 'isPending' => $this->stats['totalPending'] > 0]] as $stat)
                <div
                    class="group relative bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 transition-all duration-300">

                    <div class="absolute top-4 right-4 opacity-75 group-hover:opacity-100 transition-opacity">
                        <i
                            class="{{ $stat['icon'] }} text-4xl text-{{ $stat['color'] }}-500 {{ $stat['isPending'] ? 'animate-pulse' : '' }}"></i>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">
                            {{ $stat['label'] }}
                        </span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                                {{ $stat['value'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
