<x-app-layout>
    <div class="py-20 bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div
                class="bg-white dark:bg-gray-800 p-8 md:p-12 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700">

                <div
                    class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Pagamento non completato
                </h1>

                <p class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                    L'operazione è stata annullata. Non è stato effettuato alcun addebito sulla tua carta.
                </p>

                <div class="flex flex-col gap-4 justify-center">
                    <a href="{{ route('booking.show', $booking->camper->slug) }}"
                        class="inline-flex justify-center items-center px-6 py-4 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition">
                        Riprova a prenotare
                    </a>
                    <a href="{{ route('index') }}"
                        class="text-sm text-gray-500 hover:text-amber-600 transition underline">
                        Torna a vedere i nostri camper
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
