<div wire:poll.30s="updateTime">
    @if ($booking && $timeLeft && $isPaying)
        <div>
            <div
                class="w-20 h-20 bg-amber-100 dark:bg-amber-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 dark:text-amber-500 text-4xl"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 uppercase">
                Pagamento Interrotto
            </h1>

            <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                Nessun problema! Nessun addebito è stato effettuato. <br>
                Il camper è ancora bloccato per te per pochi minuti.
            </p>
        </div>


        <div class="bg-amber-50 dark:bg-amber-900/10 p-6 rounded-2xl border-2 border-amber-500 mb-4">
            <div class="relative flex items-center justify-center mb-4">
                <i class="fa-solid fa-clock text-amber-500 opacity-20 animate-spin text-5xl"
                    style="animation-duration: 10s;"></i>
                <span class="absolute text-xl font-black text-amber-600 dark:text-amber-500 font-mono">
                    @if ($booking)
                        <div x-data="{
                            expiry: {{ $expiryTimestamp }},
                            time: '',
                            init() {
                                let timer = setInterval(() => {
                                    let diff = this.expiry - Math.floor(Date.now() / 1000);
                                    if (diff <= 0) {
                                        this.time = '00:00';
                                        clearInterval(timer);
                                        $wire.$refresh();
                                    } else {
                                        let m = Math.floor(diff / 60);
                                        let s = diff % 60;
                                        this.time = m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');
                                    }
                                }, 1000);
                            }
                        }">
                            <span x-text="time"></span>
                        </div>
                    @endif
                </span>
            </div>

            <h4 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Completa il
                pagamento</h4>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Il camper <span class="font-bold text-amber-500">{{ $booking->camper->name }}</span> è riservato
                per te
            </p>
            <div
                class="flex items-center justify-center gap-1 text-sm font-medium text-gray-500 dark:text-gray-400 mb-4 mt-2">
                <span>{{ $formattedDates }}</span>
            </div>

            <x-primary-anchor href="{{ route('checkout', $booking) }}" class="px-6 py-3"
                x-data="{ loading: false }" @click="loading = true" x-bind:class="loading ? 'opacity-50 cursor-wait' : ''">
                Paga Ora
            </x-primary-anchor>
        </div>
    @else
        <div>
            <div
                class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-500 text-4xl"></i>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 uppercase">
                Pagamento Scaduto
            </h1>

            <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                La tua sessione di pagamento è terminata. <br>
                Il camper è tornato disponibile.
            </p>
        </div>

        <div x-transition
            class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-2xl border border-red-500 mb-4 transition-all text-center">
            <p class="text-sm font-bold uppercase tracking-widest text-red-500">
                Tempo scaduto
            </p>
        </div>
    @endif
</div>
