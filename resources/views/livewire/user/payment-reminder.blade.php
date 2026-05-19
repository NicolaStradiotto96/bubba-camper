<div wire:poll.1s="updateTime">
    @if ($booking && $timeLeft && $isPaying)
        <div>
            <div
                class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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
                <svg class="w-16 h-16 text-amber-500 opacity-20 animate-spin-slow" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" style="animation-duration: 10s;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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
                <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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
