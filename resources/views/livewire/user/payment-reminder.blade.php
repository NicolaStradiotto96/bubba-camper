<div wire:poll.1s="updateTime">
    @if ($booking && $timeLeft && $isPaying)
        <div>
            <div
                class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-400 text-4xl"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                Pagamento Interrotto
            </h1>

            <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                Nessun problema! Nessun addebito è stato effettuato. <br>
                Il camper è ancora bloccato per te per pochi minuti.
            </p>
        </div>


        <div class="bg-amber-50 dark:bg-amber-900/10 p-6 rounded-2xl border-2 border-amber-500/30 mb-4">
            <p class="text-sm font-bold uppercase tracking-widest text-amber-600 mb-4">Puoi concludere entro:</p>

            <div class="relative flex items-center justify-center mb-4">
                <i class="fa-solid fa-clock text-amber-500 opacity-20 animate-spin text-5xl" style="animation-duration: 10s;"></i>
                <span class="absolute text-xl font-black text-amber-600 dark:text-amber-400 font-mono">
                    {{ $timeLeft }}
                </span>
            </div>

            <h4 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Completa il
                pagamento</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Il camper <span class="font-bold text-amber-600">{{ $booking->camper->name }}</span> è riservato
                per te
            </p>
            <div
                class="flex items-center justify-center gap-1 text-sm font-medium text-gray-500 dark:text-gray-400 mb-4 mt-2">
                <span>{{ $formattedDates }}</span>
            </div>

            <a href="{{ route('checkout', $booking) }}"
                class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-amber-500/50 uppercase tracking-widest">
                Paga Ora
            </a>
        </div>
    @else
        <div>
            <div
                class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400 text-4xl"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                Pagamento Scaduto
            </h1>

            <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                La tua sessione di pagamento è terminata. <br>
                Il camper è tornato disponibile.
            </p>
        </div>

        <div x-transition
            class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-2xl border border-red-500/50 mb-4 transition-all text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-red-600">
                Tempo scaduto
            </p>
        </div>
    @endif
</div>
