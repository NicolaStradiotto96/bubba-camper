@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

<x-app-layout title="Prenotazione Confermata">
    <div
        class="bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">

        @if ($booking->payment_status === 'paid')
            <div class="w-full md:w-[48rem] max-w-3xl mx-auto px-4 text-center">
                <div
                    class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl border border-green-100 dark:border-gray-700 min-w-full">

                    <div
                        class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-check text-green-600 dark:text-green-500 text-3xl"></i>
                    </div>

                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4 uppercase">
                        Prenotazione Confermata!
                    </h1>

                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">
                        Grazie <span
                            class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->first_name }}</span>,
                        abbiamo ricevuto l'acconto per il camper <span
                            class="text-amber-500 font-bold">{{ $booking->camper->name }}</span>
                    </p>

                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-[2rem] p-6 mb-2 inline-block w-full">
                        <div>
                            <!-- Booked Dates -->
                            <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                                <p class="text-gray-400 uppercase tracking-wider font-bold">Periodo del viaggio</p>
                                <p class="text-gray-900 dark:text-white text-base">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}
                                    <span class="text-amber-500 mx-1">➔</span>
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}
                                </p>
                            </div>

                            <!-- Payment Details -->
                            <div class="grid grid-cols-2 gap-4 border-b border-gray-200 dark:border-gray-600 py-4">
                                <div>
                                    <p class="text-green-500 uppercase tracking-wider text-xs font-bold">Acconto Pagato
                                        (30%)</p>
                                    <p class="text-xl font-black text-green-500">
                                        {{ number_format($booking->down_payment, 2, ',', '') }}€
                                    </p>
                                </div>
                                <div>
                                    <p class="text-amber-500 uppercase tracking-wider text-xs font-bold">Da saldare al
                                        ritiro (70%)</p>
                                    <p class="text-xl font-black text-amber-500">
                                        {{ number_format($booking->balance_payment, 2, ',', '') }}€
                                    </p>
                                </div>
                            </div>

                            <div class="px-4 pt-4 text-center">
                                <p class="text-gray-900 dark:text-white text-xs font-bold uppercase">
                                    Prezzo Totale
                                </p>
                                <p class="text-xl font-black text-gray-900 dark:text-white">
                                    {{ number_format($booking->total_price, 2, ',', '') }}€</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-2">
                        Riceverai a breve una mail con il riepilogo.
                    </p>

                    {{-- Document Uploader --}}
                    @if (
                        $booking->payment_status === 'paid' &&
                            (!$booking->driver_license_front_path ||
                                !$booking->driver_license_back_path ||
                                !$booking->id_card_front_path ||
                                !$booking->id_card_back_path))
                        <div
                            class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-[2rem] p-4 mb-6">

                            <h3
                                class="text-lg font-bold text-amber-800 dark:text-amber-500 mb-2 uppercase animate-pulse">
                                <i class="fa-solid fa-file-arrow-up mr-2"></i> Completa la tua pratica
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mb-4">
                                Per finalizzare la prenotazione, carica una copia della tua carta d'identità e della
                                patente
                                di
                                guida.
                            </p>

                            <x-primary-button
                                onclick="window.location.href='{{ route('dashboard') }}?open_doc_modal={{ $booking->id }}'"
                                class="bg-amber-500 hover:bg-amber-600">
                                CARICA I DOCUMENTI
                            </x-primary-button>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-[2rem] hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm uppercase">
                            Vai alla tua Dashboard
                        </a>

                        <a href="{{ route('welcome') }}"
                            class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-[2rem] hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm uppercase">
                            Torna alla Home
                        </a>
                    </div>
                </div>
            </div>

            {{-- CONFETTI --}}
            <script>
                (async () => {
                    const checkConfetti = setInterval(async () => {
                        if (window.JSConfetti) {
                            clearInterval(checkConfetti);
                            const jsConfetti = new window.JSConfetti();

                            await jsConfetti.addConfetti({
                                confettiColors: ['#f59e0b', '#eab308', '#ef4444', '#ffffff'],
                                confettiNumber: 500,
                            });

                            setTimeout(async () => {
                                await jsConfetti.addConfetti({
                                    emojis: ['🚐', '🎉', '⭐', '🌍'],
                                    emojiSize: 35,
                                    confettiNumber: 100,
                                });
                            }, 50);

                            setTimeout(async () => {
                                await jsConfetti.addConfetti({
                                    confettiColors: ['#f59e0b', '#eab308', '#ef4444', '#ffffff'],
                                    confettiNumber: 250,
                                });
                            }, 500);
                        }
                    }, 50);
                })();
            </script>
        @else
            <div class="w-full md:w-[48rem] max-w-3xl mx-auto px-4 text-center">
                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl border border-blue-100 dark:border-gray-700">
                    <div class="animate-spin text-4xl text-amber-500 mb-4">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white uppercase">Verifica pagamento in
                        corso...</h1>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Stiamo elaborando il tuo pagamento. La pagina si aggiornerà automaticamente non appena
                        riceveremo la conferma.
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}"
                            class="text-sm text-gray-500 hover:text-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                            Torna alla dashboard se il pagamento impiega troppo tempo
                        </a>
                    </div>

                    <script>
                        setTimeout(() => location.reload(), 5000);
                    </script>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
