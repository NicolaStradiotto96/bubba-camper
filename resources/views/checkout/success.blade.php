<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="w-full md:w-[48rem] max-w-3xl mx-auto px-4 text-center">
            <div
                class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-xl border border-green-100 dark:border-gray-700 min-w-full">

                <div
                    class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-check text-green-600 dark:text-green-500 text-3xl"></i>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 uppercase">
                    Prenotazione Confermata!
                </h1>

                <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                    Grazie <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->first_name }}</span>,
                    l'acconto per il camper <span class="text-amber-500 font-bold">{{ $booking->camper->name }}</span> è
                    stato
                    ricevuto correttamente.
                </p>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-4 inline-block w-full">
                    <div>
                        <!-- Info Periodo -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                            <p class="text-gray-400 uppercase tracking-wider font-bold">Periodo del viaggio</p>
                            <p class="text-gray-900 dark:text-white text-base">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}
                                <span class="text-amber-500 mx-1">➔</span>
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Dettaglio Pagamenti -->
                        <div class="grid grid-cols-2 gap-4 border-b border-gray-200 dark:border-gray-600 py-4">
                            <div>
                                <p class="text-green-500 uppercase tracking-wider text-xs font-bold">Acconto Pagato
                                    (30%)</p>
                                <p class="text-xl font-black text-green-500">
                                    {{ number_format($booking->down_payment, 2, ',', '.') }}€
                                </p>
                            </div>
                            <div>
                                <p class="text-amber-500 uppercase tracking-wider text-xs font-bold">Da saldare al
                                    ritiro (70%)</p>
                                <p class="text-xl font-black text-amber-500">
                                    {{ number_format($booking->balance_payment, 2, ',', '.') }}€
                                </p>
                            </div>
                        </div>

                        <div class="px-4 pt-4 rounded-lg text-center">
                            <p class="text-gray-900 dark:text-white text-xs font-bold uppercase">
                                Prezzo Totale
                            </p>
                            <p class="text-xl font-black text-gray-900 dark:text-white">
                                {{ number_format($booking->total_price, 2, ',', '.') }}€</p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Riceverai a breve una mail con il riepilogo. <br>
                    Verificheremo i dettagli e potrai seguire lo
                    stato della tua prenotazione direttamente nella tua dashboard.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard') }}"
                        class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm uppercase">
                        Vai alla tua Dashboard
                    </a>

                    <a href="{{ route('welcome') }}"
                        class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm uppercase">
                        Torna alla Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
