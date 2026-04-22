<x-app-layout>
    <div class="py-20 bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <div
                class="bg-white dark:bg-gray-800 p-8 md:p-12 rounded-3xl shadow-xl border border-green-100 dark:border-gray-700">

                <div
                    class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Pagamento Ricevuto!
                </h1>

                <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                    Grazie <span
                        class="font-bold text-gray-900 dark:text-white">{{ $booking->customer_first_name }}</span>, la
                    tua prenotazione per il <span class="text-amber-600 font-bold">{{ $booking->camper->name }}</span> è
                    stata registrata correttamente.
                </p>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-8 text-left inline-block w-full">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 uppercase tracking-wider text-xs font-bold">Periodo</p>
                            <p class="text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 uppercase tracking-wider text-xs font-bold">Totale Pagato</p>
                            <p class="text-gray-900 dark:text-white font-bold">
                                {{ number_format($booking->total_price, 2) }}€</p>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-500 mb-8">
                    Riceverai a breve una mail di conferma. Stefano verificherà i dettagli e confermerà definitivamente
                    il tuo viaggio nella tua dashboard.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex justify-center items-center px-6 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition shadow-lg shadow-amber-600/20">
                        Vai alla tua Dashboard
                    </a>
                    <a href="{{ route('index') }}"
                        class="inline-flex justify-center items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Torna alla Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
