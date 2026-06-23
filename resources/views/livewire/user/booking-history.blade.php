<div class="max-w-7xl mx-auto" id="booking-history-root">

    {{-- TITLE --}}
    <div class="px-4 sm:px-0 my-8">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
            I Tuoi Viaggi
        </h2>
    </div>

    {{-- MESSAGES --}}
    <div x-data="{ message: '' }" @notify.window="message = $event.detail.message;">

        <div x-show="message" x-transition
            class="mx-4 sm:mx-0 mb-6 p-4 rounded-r-xl border border-l-4 bg-white dark:bg-gray-800 border-green-500 text-green-500">
            <div class="flex items-center justify-between font-medium">
                <span x-text="message"></span>
                <button @click="message = ''"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 p-1 rounded-lg transition-colors focus:outline-none"><i
                        class="fa-solid fa-xmark text-xl"></i></button>
            </div>
        </div>

        @if (session()->has('success') || session()->has('cancelled'))
            <div x-data="{ show: true }" x-show="show"
                class="mx-4 sm:mx-0 mb-6 p-4 rounded-r-xl border border-l-4 bg-white dark:bg-gray-800 {{ session()->has('success') ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}">
                <div class="flex items-center justify-between font-medium">
                    <span>{{ session('success') ?? session('cancelled') }}</span>
                    <button @click="show = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 p-1 rounded-lg transition-colors focus:outline-none"><i
                            class="fa-solid fa-xmark text-xl"></i></button>
                </div>
            </div>
        @endif
    </div>

    @if ($bookings->isEmpty())
        <div
            class="mx-4 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl p-6 text-center shadow-sm flex flex-col items-center justify-center">
            <div
                class="w-20 h-20 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-van-shuttle text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-wide mb-2">
                {{ __('Nessun viaggio programmato') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mb-6">
                {{ __('Non hai ancora effettuato nessuna prenotazione.') }}
                <br>
                {{ __('Scegli il camper perfetto per te e inizia a pianificare la tua prossima avventura!') }}
            </p>
        </div>
    @else
        {{-- DESKTOP VIEW --}}
        <div
            class="hidden lg:block bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-center">

                {{-- TABLE COLUMNS --}}
                <thead class="font-black uppercase text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-2 py-4">ID</th>
                        <th class="px-2 py-4">Dettagli</th>
                        <th class="px-2 py-4">Pagamento</th>
                        <th class="px-2 py-4">Documenti</th>
                        <th class="px-2 py-4">Prenotazione</th>
                        <th class="px-2 py-4">Bilancio</th>
                        <th class="px-2 py-4">Azioni</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($bookings as $booking)
                        <tr class="font-black hover:bg-gray-100/50 dark:hover:bg-gray-700/30">

                            {{-- ID --}}
                            <td class="px-2 py-4">
                                <span
                                    class="text-xs text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 py-1 px-1">#{{ $booking->id }}</span>
                            </td>

                            {{-- DETAILS --}}
                            <td class="px-2 py-4 text-sm">
                                <p class="text-amber-500">{{ $booking->camper->name }}</p>
                                <p class="text-gray-700 dark:text-gray-300 ">{{ $booking->start_date->format('d/m/Y') }}
                                    <span class="text-amber-500">➔</span>
                                    {{ $booking->end_date->format('d/m/Y') }}
                                </p>
                            </td>

                            {{-- PAYMENT STATUS --}}
                            <td class="px-2 py-4 text-xs uppercase">
                                @if ($booking->payment_status === 'paid')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Pagato parz.
                                    </span>
                                @elseif ($booking->payment_status === 'fully_paid')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Pagato intero
                                    </span>
                                @elseif ($booking->payment_status === 'penalty_paid')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Penale pagata
                                    </span>
                                @elseif ($booking->payment_status === 'refunded_stripe')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Rimb. Stripe
                                    </span>
                                @elseif ($booking->payment_status === 'refunded_manual')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Rimb. Manuale
                                    </span>
                                @elseif ($booking->payment_status === 'penalty_pending')
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        Penale da pagare
                                    </span>
                                @elseif ($booking->payment_status === 'penalty_verification')
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        Penale in verifica
                                    </span>
                                @elseif ($booking->payment_status === 'unpaid')
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Non pagato
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Errore
                                    </span>
                                @endif
                            </td>

                            {{-- DOCUMENTS STATUS --}}
                            <td class="px-2 py-4 text-xs uppercase">
                                @if ($booking->documents_status === 'uploaded')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Caricati
                                    </span>
                                @elseif ($booking->documents_status === 'pending')
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        In attesa
                                    </span>
                                @elseif ($booking->documents_status === 'not_required')
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Annullati
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Errore
                                    </span>
                                @endif
                            </td>

                            {{-- BOOKING STATUS --}}
                            <td class="px-2 py-4 text-xs uppercase">
                                @if ($booking->status === 'confirmed')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Confermata
                                    </span>
                                @elseif ($booking->status === 'invoiced')
                                    <span
                                        class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                        Fatturata
                                    </span>
                                @elseif ($booking->status === 'pending')
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        In attesa
                                    </span>
                                @elseif ($booking->status === 'cancellation_pending')
                                    <span
                                        class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                        Richiesta Canc.
                                    </span>
                                @elseif ($booking->status === 'expired')
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Scaduta
                                    </span>
                                @elseif ($booking->status === 'cancelled')
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Cancellata
                                    </span>
                                @elseif ($booking->status === 'cancelled_by_admin')
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Annullata
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                        Errore
                                    </span>
                                @endif
                            </td>

                            {{-- BALANCE --}}
                            <td class="px-2 py-4 text-xs uppercase">
                                <div class="flex flex-col items-center gap-1">

                                    {{-- Total --}}
                                    <div
                                        class="flex justify-between w-36 border-b border-gray-100 dark:border-gray-700 pb-1">
                                        <span class="text-gray-400">Totale:</span>
                                        <span
                                            class="text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                                    </div>

                                    {{-- Deposit --}}
                                    <div class="flex justify-between w-36">
                                        <span class="text-gray-400">Acconto:</span>

                                        @if ($booking->down_paid)
                                            <span class="text-green-600">
                                                {{ number_format($booking->down_payment, 2, ',', '.') }}€
                                            </span>
                                        @elseif (str_starts_with($booking->status, 'cancelled') || $booking->status === 'expired')
                                            <span class="text-gray-400">0,00€</span>
                                        @else
                                            <span class="text-amber-500 animate-pulse">
                                                {{ number_format($booking->down_payment, 2, ',', '.') }}€
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Balance --}}
                                    <div class="flex justify-between w-36 pt-1">
                                        <span class="text-gray-400">Saldo:</span>

                                        @if ($booking->balance_paid)
                                            <span class="text-green-600">
                                                {{ number_format($booking->total_price - $booking->down_payment, 2, ',', '.') }}€
                                            </span>
                                        @elseif (str_starts_with($booking->status, 'cancelled') &&
                                                in_array($booking->payment_status, ['penalty_pending', 'penalty_verification']))
                                            <span class="text-amber-500 animate-pulse">
                                                {{ number_format($booking->calculateExpectedRefund()['remaining_penalty'], 2, ',', '.') }}€
                                            </span>
                                        @elseif (str_starts_with($booking->status, 'cancelled') || $booking->status === 'expired')
                                            <span class="text-gray-400">0,00€</span>
                                        @else
                                            <span class="text-amber-500 animate-pulse">
                                                {{ number_format($booking->balance_payment, 2, ',', '.') }}€
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Penalty --}}
                                    @if (
                                        $booking->status === 'cancelled' &&
                                            ($booking->payment_status === 'penalty_pending' || $booking->payment_status === 'penalty_verification'))
                                        <div
                                            class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                            <span class="text-gray-400">Penale:</span>
                                            <span
                                                class="text-amber-500 animate-pulse">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                            </span>
                                        </div>
                                    @elseif (
                                        $booking->status === 'cancelled' &&
                                            !($booking->payment_status === 'penalty_pending' || $booking->payment_status === 'penalty_verification'))
                                        <div
                                            class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                            <span class="text-gray-400">Penale:</span>
                                            <span
                                                class="text-red-600">-{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Refund --}}
                                    @if (str_starts_with($booking->status, 'cancelled') && $booking->calculateExpectedRefund()['refund_amount'] > 0)
                                        <div
                                            class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                            <span class="text-gray-400">Rimborso:</span>
                                            <span
                                                class="text-green-600">{{ number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '.') }}€
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- BUTTONS --}}
                            <td class="px-2 py-4">
                                <div class="flex flex-col gap-2 justify-center uppercase">

                                    {{-- Details --}}
                                    <button type="button" wire:click="openBookingDetails('{{ $booking->id }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="openBookingDetails('{{ $booking->id }}')"
                                        class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 border border-gray-600 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-gray-600">
                                        Dettagli
                                    </button>

                                    {{-- Documents --}}
                                    @if (
                                        $booking->payment_status === 'paid' &&
                                            (!$booking->driver_license_front_path ||
                                                !$booking->driver_license_back_path ||
                                                !$booking->id_card_front_path ||
                                                !$booking->id_card_back_path))
                                        <button type="button"
                                            @click="$dispatch('open-doc-modal', { id: '{{ $booking->id }}' })"
                                            class="bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            Carica Documenti
                                        </button>
                                    @endif

                                    {{-- Pay Penalty --}}
                                    @if (
                                        $booking->status === 'cancelled' &&
                                            $booking->payment_status === 'penalty_pending' &&
                                            ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0) > 0)
                                        <button type="button"
                                            onclick="window.payPenaltyAction('{{ $booking->id }}', {{ ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0) }})"
                                            class="bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            Paga Penale
                                        </button>
                                    @endif

                                    {{-- Request Cancellation --}}
                                    @if (($booking->status === 'confirmed' || $booking->status === 'pending') && $booking->payment_status === 'paid')
                                        <button type="button"
                                            onclick="window.requestUserCancellation('{{ $booking->id }}', {{ $booking->calculateExpectedRefund()['penalty_amount'] ?? 0 }})"
                                            class="bg-gray-100 dark:bg-gray-700 hover:bg-red-600 dark:hover:bg-red-600 border border-red-600 text-black dark:text-white text-xs py-2 px-3 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                                            Annulla Viaggio
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- MOBILE VIEW --}}
        <div class="lg:hidden space-y-4 px-4">
            @foreach ($bookings as $booking)
                <div
                    class="bg-white dark:bg-gray-800 font-black p-5 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">

                    <div class="text-center">

                        {{-- ID --}}
                        <div>
                            <span
                                class="font-black uppercase text-gray-900 dark:text-white text-xl">Prenotazione</span>
                            <span
                                class="font-mono text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 py-1 px-1 rounded text-xl">
                                #{{ $booking->id }}
                            </span>
                        </div>

                        {{-- BOOKING DATES --}}
                        <div
                            class="my-4 p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-xl space-y-2">
                            <div class="flex flex-col justify-center items-center">
                                <p class="text-amber-500">{{ $booking->camper->name }}
                                </p>

                                <p class="text-gray-700 dark:text-gray-300 ">
                                    {{ $booking->start_date->format('d/m/Y') }}
                                    <span class="text-amber-500">➔</span>
                                    {{ $booking->end_date->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="my-4 p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-xl space-y-2 text-xs uppercase">

                        {{-- PAYMENT STATUS --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Pagamento:</span>

                            @if ($booking->payment_status === 'paid')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Pagato parz.
                                </span>
                            @elseif ($booking->payment_status === 'fully_paid')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Pagato intero
                                </span>
                            @elseif ($booking->payment_status === 'penalty_paid')
                                <span
                                    class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Penale pagata
                                </span>
                            @elseif ($booking->payment_status === 'refunded_stripe')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Rimb. Stripe
                                </span>
                            @elseif ($booking->payment_status === 'refunded_manual')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Rimb. Manuale
                                </span>
                            @elseif ($booking->payment_status === 'penalty_pending')
                                <span
                                    class="px-2 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                    Penale da pagare
                                </span>
                            @elseif ($booking->payment_status === 'penalty_verification')
                                <span
                                    class="px-2 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                    Penale in verifica
                                </span>
                            @elseif ($booking->payment_status === 'unpaid')
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Non pagato
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Errore
                                </span>
                            @endif
                        </div>

                        {{-- DOCUMENTS STATUS --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Documenti:</span>

                            @if ($booking->documents_status === 'uploaded')
                                <span
                                    class="px-3 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Caricati
                                </span>
                            @elseif ($booking->documents_status === 'pending')
                                <span
                                    class="px-3 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                    In attesa
                                </span>
                            @elseif ($booking->documents_status === 'not_required')
                                <span
                                    class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Annullati
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Errore
                                </span>
                            @endif
                        </div>

                        {{-- BOOKING STATUS --}}
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Prenotazione:</span>

                            @if ($booking->status === 'confirmed')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Confermata
                                </span>
                            @elseif ($booking->status === 'invoiced')
                                <span
                                    class="px-2 py-1 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                    Fatturata
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span
                                    class="px-2 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                    In attesa
                                </span>
                            @elseif ($booking->status === 'cancellation_pending')
                                <span
                                    class="px-2 py-1 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">Richiesta
                                    Canc.
                                </span>
                            @elseif ($booking->status === 'expired')
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Scaduta
                                </span>
                            @elseif ($booking->status === 'cancelled')
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Cancellata
                                </span>
                            @elseif ($booking->status === 'cancelled_by_admin')
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Annullata
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                    Errore
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- BALANCE --}}
                    <div
                        class="my-4 p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 rounded-xl space-y-2 text-xs uppercase">

                        {{-- Total --}}
                        <div class="flex justify-between">
                            <span class="text-gray-400">Totale:</span>
                            <span
                                class="font-black text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€
                            </span>
                        </div>

                        {{-- Deposit --}}
                        <div class="flex justify-between">
                            <span class="text-gray-400">Acconto:</span>

                            @if ($booking->payment_status === 'unpaid' && ($booking->status === 'expired' || $booking->status === 'cancelled'))
                                <span class="text-gray-400">0,00€</span>
                            @elseif ($booking->payment_status === 'unpaid')
                                <span class="text-amber-500 animate-pulse">
                                    {{ number_format($booking->down_payment, 2, ',', '.') }}€
                                </span>
                            @else
                                <span class="text-green-600">
                                    {{ number_format($booking->down_payment, 2, ',', '.') }}€
                                </span>
                            @endif

                        </div>

                        {{-- Balance --}}
                        <div class="flex justify-between">
                            <span class="text-gray-400">Saldo:</span>

                            @if ($booking->balance_paid)
                                <span class="text-green-600">
                                    {{ number_format($booking->total_price - $booking->down_payment, 2, ',', '.') }}€
                                </span>
                            @elseif (str_starts_with($booking->status, 'cancelled') &&
                                    in_array($booking->payment_status, ['penalty_pending', 'penalty_verification']))
                                <span class="text-amber-500 animate-pulse">
                                    {{ number_format($booking->calculateExpectedRefund()['remaining_penalty'], 2, ',', '.') }}€
                                </span>
                            @elseif (str_starts_with($booking->status, 'cancelled') || $booking->status === 'expired')
                                <span class="text-gray-400">0,00€</span>
                            @else
                                <span class="text-amber-500 animate-pulse">
                                    {{ number_format($booking->balance_payment, 2, ',', '.') }}€
                                </span>
                            @endif
                        </div>

                        {{-- Penalty --}}
                        @if (
                            $booking->status === 'cancelled' &&
                                ($booking->payment_status === 'penalty_pending' || $booking->payment_status === 'penalty_verification'))
                            <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-gray-400">Penale:</span>
                                <span
                                    class="text-amber-500 animate-pulse">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                </span>
                            </div>
                        @elseif (
                            $booking->status === 'cancelled' &&
                                !($booking->payment_status === 'penalty_pending' || $booking->payment_status === 'penalty_verification'))
                            <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-gray-400">Penale:</span>
                                <span
                                    class="text-red-600">-{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                </span>
                            </div>
                        @endif

                        {{-- Refund --}}
                        @if (str_starts_with($booking->status, 'cancelled') && $booking->calculateExpectedRefund()['refund_amount'] > 0)
                            <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-gray-400">Rimborso:</span>
                                <span
                                    class="font-black text-green-600">{{ number_format($booking->calculateExpectedRefund()['refund_amount'], 2, ',', '.') }}€</span>
                            </div>
                        @endif
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex flex-col gap-2 uppercase">

                        {{-- Details --}}
                        <button type="button" wire:click="openBookingDetails('{{ $booking->id }}')"
                            wire:loading.attr="disabled" wire:target="openBookingDetails('{{ $booking->id }}')"
                            class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 border border-gray-600 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-gray-600">
                            Dettagli
                        </button>

                        {{-- Documents --}}
                        @if (
                            $booking->payment_status === 'paid' &&
                                (!$booking->driver_license_front_path ||
                                    !$booking->driver_license_back_path ||
                                    !$booking->id_card_front_path ||
                                    !$booking->id_card_back_path))
                            <button type="button"
                                @click="$dispatch('open-doc-modal', { id: '{{ $booking->id }}' })"
                                class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                                Carica Documenti
                            </button>
                        @endif

                        {{-- Pay Penalty --}}
                        @if (
                            $booking->status === 'cancelled' &&
                                $booking->payment_status === 'penalty_pending' &&
                                ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0) > 0)
                            <button type="button"
                                onclick="window.payPenaltyAction('{{ $booking->id }}', {{ ($booking->calculateExpectedRefund()['penalty_amount'] ?? 0) - ($booking->down_payment ?? 0) }})"
                                class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                                Paga Penale
                            </button>
                        @endif

                        {{-- Request Cancellation --}}
                        @if (($booking->status === 'confirmed' || $booking->status === 'pending') && $booking->payment_status === 'paid')
                            <button type="button"
                                onclick="window.requestUserCancellation('{{ $booking->id }}', {{ $booking->calculateExpectedRefund()['penalty_amount'] ?? 0 }})"
                                class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-red-600 dark:hover:bg-red-600 border border-red-600 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                                Annulla Viaggio
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- PAGING --}}
        <div class="mt-6 px-4">
            {{ $bookings->links() }}
        </div>

        {{-- BOOKING MODAL --}}
        <div x-data="{ open: false, b: {} }" x-init="$watch('open', value => { document.body.classList.toggle('no-scroll', value) })"
            @open-booking-modal.window="open = true; b = Array.isArray($event.detail) ? $event.detail[0] : ($event.detail.detail ? $event.detail.detail : $event.detail)"
            @keydown.escape.window="open = false" x-show="open" class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">

            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm"
                @click="open = false"></div>

            <div class="flex min-h-full items-center justify-center p-3 text-center">
                <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                    x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                    x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                    class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl sm:my-3 w-full sm:max-w-2xl border-2 border-gray-200 dark:border-gray-700">

                    {{-- Header --}}
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 font-black px-6 py-3 border-b border-gray-100 dark:border-gray-700 ">

                        {{-- ID --}}
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl text-gray-900 dark:text-white uppercase tracking-wider">
                                Prenotazione
                                <span class="bg-gray-200 dark:bg-gray-900 py-1 px-1">#<span
                                        x-text="b.id"></span></span>
                            </h3>

                            <button @click="open = false" type="button"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 p-1 rounded-lg transition-colors focus:outline-none"
                                aria-label="Chiudi modale">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        {{-- Booking Created At --}}
                        <div class="pt-1">
                            <p class="text-xs text-gray-400 uppercase tracking-widest">
                                Ricevuta il: <span class="text-gray-700 dark:text-gray-200"
                                    x-text="b.created_at"></span>
                            </p>
                        </div>

                    </div>

                    <div class="p-2 space-y-2">
                        <div class="text-center">

                            <div class="flex flex-col sm:flex-row gap-2">

                                {{-- Customer Info --}}
                                <div class="w-full">
                                    <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Dati
                                        Cliente
                                    </h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/50 font-black rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">

                                        {{-- Name --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">Nome:
                                            </p>
                                            <p class="px-3 py-0.5 font-black text-gray-900 dark:text-white uppercase border border-transparent"
                                                x-text="b.name">
                                            </p>
                                        </div>

                                        {{-- Email --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">Email:
                                            </p>
                                            <a :href="'mailto:' + b.email"
                                                class="px-3 py-0.5 font-black italic text-gray-900 dark:text-white hover:underline block border border-transparent"
                                                x-text="b.email"></a>
                                        </div>

                                        {{-- Phone --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">
                                                Telefono:</p>
                                            <p class="px-3 py-0.5 font-black text-gray-900 dark:text-white border border-transparent"
                                                x-text="b.phone"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="w-full">
                                    <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Stato
                                    </h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900/50 font-black rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">

                                        {{-- Payment --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">Pagamento:
                                            </p>

                                            <div class="uppercase inline-block">
                                                <template x-if="b.payment_status === 'paid'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Pagato
                                                        Parzialmente</p>
                                                </template>
                                                <template x-if="b.payment_status === 'fully_paid'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Pagato
                                                        Intero</p>
                                                </template>
                                                <template x-if="b.payment_status === 'penalty_paid'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Penale
                                                        Pagata
                                                    </p>
                                                </template>
                                                <template x-if="b.payment_status === 'refunded_stripe'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Rimborso
                                                        Stripe</p>
                                                </template>
                                                <template x-if="b.payment_status === 'refunded_manual'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Rimborso
                                                        Manuale</p>
                                                </template>
                                                <template x-if="b.payment_status === 'penalty_pending'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                                        Penale da pagare</p>
                                                </template>
                                                <template x-if="b.payment_status === 'penalty_verification'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                                        Penale in verifica</p>
                                                </template>
                                                <template x-if="b.payment_status === 'unpaid'">
                                                    <p
                                                        class="px-2 py-0.5 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                                        Non pagato</p>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- Documents --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">Documenti:
                                            </p>

                                            <div class="uppercase inline-block">
                                                <template x-if="b.documents_status === 'uploaded'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Caricati
                                                    </p>
                                                </template>
                                                <template x-if="b.documents_status === 'pending'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                                        In
                                                        attesa</p>
                                                </template>
                                                <template x-if="b.documents_status === 'not_required'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                                        Annullati</p>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- Booking --}}
                                        <div class="p-2">
                                            <p class="text-gray-400 uppercase tracking-wider mb-0.5">
                                                Prenotazione:</p>

                                            <div class="uppercase inline-block">
                                                <template x-if="b.status === 'confirmed'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Confermata
                                                    </p>
                                                </template>
                                                <template x-if="b.status === 'invoiced'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-green-500 bg-white dark:bg-gray-900 text-green-500">
                                                        Fatturata
                                                    </p>
                                                </template>
                                                <template x-if="b.status === 'pending'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                                        In
                                                        attesa</p>
                                                </template>
                                                <template x-if="b.status === 'cancellation_pending'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-amber-500 bg-white dark:bg-gray-900 text-amber-500 animate-pulse">
                                                        Richiesta
                                                        Cancellazione</p>
                                                </template>
                                                <template x-if="b.status === 'expired'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                                        Scaduta</p>
                                                </template>
                                                <template x-if="b.status === 'cancelled'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                                        Cancellata</p>
                                                </template>
                                                <template x-if="b.status === 'cancelled_by_admin'">
                                                    <p
                                                        class="px-3 py-0.5 rounded-full border border-red-500 bg-white dark:bg-gray-900 text-red-500">
                                                        Annullata</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Booking Info --}}
                        <div class="text-center">
                            <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Dettagli
                                Noleggio
                            </h4>

                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 font-black rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">

                                {{-- Camper --}}
                                <div class="p-2">
                                    <p class="text-gray-400 uppercase tracking-wider mb-0.5">
                                        Veicolo:</p>
                                    <p class="text-amber-500 uppercase" x-text="b.camper"></p>
                                </div>

                                {{-- Dates --}}
                                <div class="p-2">
                                    <p class="text-gray-400 uppercase tracking-wider mb-0.5">
                                        Periodo:</p>
                                    <p class="font-black text-gray-900 dark:text-white">
                                        <span x-text="b.start"></span>
                                        <span class="text-amber-500 mx-1">➔</span>
                                        <span x-text="b.end"></span>
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- Balance --}}
                        <div class="text-center">
                            <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Bilancio</h4>


                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 font-black p-4 rounded-xl space-y-2.5 border border-gray-100 dark:border-gray-700">

                                {{-- Total --}}
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 uppercase">Totale Noleggio:</span>
                                    <span class="font-black text-gray-900 dark:text-white" x-text="b.total"></span>
                                </div>

                                {{-- Deposit --}}
                                <div
                                    class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-400 uppercase">Acconto:</span>

                                    <template x-if="b.down_paid">
                                        <span class="text-green-600 font-bold" x-text="b.deposit"></span>
                                    </template>

                                    <template x-if="!b.down_paid">
                                        <span
                                            :class="((b && b.status && b.status.startsWith('cancelled')) || b
                                                .status === 'expired') ?
                                            'text-gray-400' : 'text-amber-500 animate-pulse'"
                                            class="font-black"
                                            x-text="((b && b.status && b.status.startsWith('cancelled')) || b.status === 'expired') ? '0,00€' : b.deposit">
                                        </span>
                                    </template>
                                </div>

                                {{-- Balance --}}
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 uppercase">Saldo:</span>

                                    <template x-if="b.balance_paid">
                                        <span class="text-green-600 font-black" x-text="b.balance"></span>
                                    </template>

                                    <template
                                        x-if="(b && b.status && b.status.startsWith('cancelled')) && !b.balance_paid && ['penalty_pending', 'penalty_verification'].includes(b.payment_status)">
                                        <span class="text-amber-500 animate-pulse font-black"
                                            x-text="b.remainingPenalty"></span>
                                    </template>

                                    <template
                                        x-if="((b && b.status && b.status.startsWith('cancelled')) || b.status === 'expired') && !b.balance_paid && !['penalty_pending', 'penalty_verification'].includes(b.payment_status)">
                                        <span class="text-gray-400 font-black">0,00€</span>
                                    </template>

                                    <template
                                        x-if="!(b && b.status && b.status.startsWith('cancelled')) && b.status !== 'expired' && !b.balance_paid">
                                        <span class="text-amber-500 animate-pulse font-black"
                                            x-text="b.balance"></span>
                                    </template>
                                </div>

                                {{-- Penalty --}}
                                <template x-if="b.status === 'cancelled' && b.penaltyRaw > 0">
                                    <div
                                        class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-400 uppercase">Penale applicata:</span>
                                        <span
                                            :class="{
                                                'text-amber-500 animate-pulse': ['penalty_pending',
                                                        'penalty_verification'
                                                    ]
                                                    .includes(b.payment_status),
                                                'text-red-600': !['penalty_pending', 'penalty_verification']
                                                    .includes(b
                                                        .payment_status)
                                            }"
                                            x-text="'-' + b.penalty"></span>
                                    </div>
                                </template>

                                {{-- Refund --}}
                                <template
                                    x-if="(b && b.status && b.status.startsWith('cancelled')) && b.refundRaw > 0">
                                    <div
                                        class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-400 uppercase">Rimborso:</span>
                                        <span class="text-green-600" x-text="b.refund"></span>
                                    </div>
                                </template>

                            </div>

                            {{-- Receipt --}}
                            <div class="space-y-2">
                                {{-- Penalty --}}
                                <template x-if="b.penalty_receipt">
                                    <div class="text-center pt-2">
                                        <a :href="b.penalty_receipt" target="_blank"
                                            class="inline-flex items-center justify-center gap-2 w-full bg-green-50 dark:bg-green-900/20 border border-green-500 text-green-600 dark:text-green-500 font-black text-xs py-3 px-6 rounded-xl uppercase tracking-widest transition shadow-sm hover:bg-green-100 dark:hover:bg-green-900/40">
                                            <i class="fa-solid fa-file-lines text-base"></i>
                                            Visualizza Contabile Penale
                                        </a>
                                    </div>
                                </template>

                                {{-- Refund --}}
                                <template x-if="b.refund_receipt">
                                    <div class="text-center pt-2">
                                        <a :href="b.refund_receipt" target="_blank"
                                            class="inline-flex items-center justify-center gap-2 w-full bg-green-50 dark:bg-green-900/20 border border-green-500 text-green-600 dark:text-green-500 font-black text-xs py-3 px-6 rounded-xl uppercase tracking-widest transition shadow-sm hover:bg-green-100 dark:hover:bg-green-900/40">
                                            <i class="fa-solid fa-file-lines text-base"></i>
                                            Visualizza Contabile Rimborso
                                        </a>
                                    </div>
                                </template>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- DOCUMENTS MODAL --}}
        <div x-data="{ showDocModal: false, selectedBookingId: null }" x-init="const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('open_modal')) {
            let id = urlParams.get('open_modal');
        
            $wire.checkBookingAccess(id).then(isAuthorized => {
                if (isAuthorized) {
                    selectedBookingId = id;
                    showDocModal = true;
                    $dispatch('setBookingId', { id: id });
                    window.history.replaceState({}, document.title, window.location.pathname);
                } else {
                    alert('Prenotazione non trovata o non autorizzata.');
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            });
        }"
            @open-doc-modal.window="showDocModal = true; selectedBookingId = $event.detail.id"
            @close-doc-modal.window="showDocModal = false" @keydown.escape.window="showDocModal = false"
            class="fixed inset-0 z-50 flex justify-center p-3 overflow-y-auto items-start sm:items-center" x-cloak
            x-show="showDocModal">

            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="showDocModal"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" @click="showDocModal = false">
            </div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 w-full sm:max-w-lg border-2 border-gray-200 dark:border-gray-700 shadow-2xl z-10 my-auto"
                x-show="showDocModal" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="font-black flex flex-col justify-center items-center mb-6">
                    <i class="fa-solid fa-id-card text-amber-500 text-5xl mb-3"></i>
                    <h3 class="text-gray-900 dark:text-white uppercase text-center text-2xl">Carica Documenti</h3>
                    <p class="text-md text-gray-400 mt-1 uppercase">Prenotazione
                        <span class="text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 py-0.5 px-1 rounded"
                            x-text="'#' + selectedBookingId"></span>
                    </p>
                </div>

                <div x-init="$watch('showDocModal', value => { if (value) $dispatch('setBookingId', { id: selectedBookingId }) })">
                    @livewire('document-uploader', key('doc-uploader-static'))
                </div>

                <x-secondary-button @click="showDocModal = false; $dispatch('reset-uploader')"
                    class="w-full mt-4 flex justify-center items-center">
                    Chiudi
                </x-secondary-button>
            </div>
        </div>
    @endif
</div>
