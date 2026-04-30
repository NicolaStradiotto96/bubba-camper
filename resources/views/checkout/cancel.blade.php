<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700">

                <div
                    class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Pagamento interrotto
                </h1>

                <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                    Nessun problema! Nessun addebito è stato effettuato. <br>
                    Il camper è ancora bloccato per te per pochi minuti.
                </p>

                <div>
                    @if (auth()->user()->isPayingRightNow())
                        <div
                            class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-dashed border-amber-500/50 mb-4">
                            <p class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-4">Puoi concludere
                                ora:
                            </p>
                            <livewire:user.payment-reminder />
                        </div>
                    @else
                        <div
                            class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-red-500/50 mb-4">
                            <p class="text-sm font-bold uppercase tracking-widest text-red-600">Tempo scaduto
                            </p>
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 items-center">
                        <p class="text-sm text-gray-500">Vuoi pensarci con calma?</p>

                        <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                Vai alla tua Dashboard
                            </a>

                            <a href="{{ route('index') }}"
                                class="px-6 py-3 text-amber-600 font-bold hover:text-amber-700 transition">
                                Torna ai Camper
                            </a>
                        </div>

                        <p class="text-[11px] text-gray-400 max-w-sm italic">
                            *Ricorda: se il tempo scade, la prenotazione verrà annullata automaticamente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
