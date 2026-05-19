<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="w-full md:w-[48rem] max-w-3xl mx-auto px-4 text-center">
            <div
                class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-xl border border-green-100 dark:border-gray-700 min-w-full">

                <div
                    class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Prenotazione Confermata!
                </h1>

                <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                    Grazie <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>,
                    l'acconto per il <span class="text-amber-600 font-bold">{{ $booking->camper->name }}</span> è stato
                    ricevuto correttamente.
                </p>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-8 inline-block w-full">
                    <div class="space-y-4">
                        <!-- Info Periodo -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                            <p class="text-gray-500 uppercase tracking-wider text-xs font-bold">Periodo del viaggio</p>
                            <p class="text-gray-900 dark:text-white text-base">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Dettaglio Pagamenti -->
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div>
                                <p class="text-green-600 uppercase tracking-wider text-xs font-bold">Acconto Pagato
                                    (30%)</p>
                                <p class="text-xl font-black text-green-600">
                                    {{ number_format($booking->deposit_amount, 2, ',', '.') }}€
                                </p>
                            </div>
                            <div>
                                <p class="text-amber-600 uppercase tracking-wider text-xs font-bold">Da saldare al
                                    ritiro (70%)</p>
                                <p class="text-xl font-black text-amber-600">
                                    {{ number_format($booking->balance_amount, 2, ',', '.') }}€
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-amber-100/50 dark:bg-amber-900/20 p-3 rounded-lg border border-amber-200 dark:border-amber-800 text-center">
                            <p class="text-amber-800 dark:text-amber-400 text-xs font-bold uppercase">
                                Prezzo Totale: {{ number_format($booking->total_price, 2, ',', '.') }}€
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-500 mb-8">
                    Riceverai a breve una mail con il riepilogo. <br>
                    Verificheremo i dettagli e potrai seguire lo
                    stato della tua prenotazione direttamente nella tua dashboard.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                        Vai alla tua Dashboard
                    </a>

                    <a href="{{ route('welcome') }}"
                        class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                        Torna alla Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
