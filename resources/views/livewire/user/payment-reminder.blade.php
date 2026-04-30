<div>
    @if ($booking && $timeLeft)
        <div wire:poll.1s="updateTime"
            class="mt-6 p-6 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border-2 border-amber-500/30 text-center shadow-lg">
            <div class="flex flex-col items-center">
                <div class="relative flex items-center justify-center mb-2">
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
                    Il camper <span class="font-bold text-amber-600">{{ $booking->camper->name }}</span> è riservato per
                    te!
                </p>
                <div
                    class="flex items-center justify-center gap-1 text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $formattedDates }}</span>
                </div>

                <a href="{{ route('checkout', $booking) }}"
                    class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:shadow-amber-500/50 uppercase tracking-widest">
                    Paga Ora
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
        </div>
    @endif
</div>
