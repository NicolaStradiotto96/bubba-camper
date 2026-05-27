<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    {{-- STATS --}}
    <div class="px-4 sm:px-0 mb-8">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
            Statistiche
        </h2>
    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8 mx-2">
        @foreach ([
        [
            'label' => 'Incasso',
            'value' => number_format($this->stats['earnings'], 0, ',', '.') . '€',
            'icon' => 'fa-solid fa-coins',
            'border' => 'border-green-500',
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'text' => 'text-green-500',
        ],
        [
            'label' => 'Totali',
            'value' => $this->stats['total'],
            'icon' => 'fa-solid fa-boxes-stacked',
            'border' => 'border-green-500',
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'text' => 'text-green-500',
        ],
        [
            'label' => 'Confermate',
            'value' => $this->stats['confirmed'],
            'icon' => 'fa-solid fa-circle-check',
            'border' => 'border-green-500',
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'text' => 'text-green-500',
        ],
        [
            'label' => 'In Attesa',
            'value' => $this->stats['pending'],
            'icon' => 'fa-solid fa-clock',
            'border' => 'border-amber-500',
            'bg' => 'bg-amber-50 dark:bg-amber-900/20',
            'text' => 'text-amber-500',
        ],
    ] as $stat)
            <div
                class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-xl shadow-sm border border-l-4 {{ $stat['border'] }} overflow-hidden">
                <div class="flex items-center">
                    <div class="p-3 {{ $stat['bg'] }} rounded-lg relative">
                        <i class="{{ $stat['icon'] }} {{ $stat['text'] }} text-xl"></i>
                        @if ($stat['label'] === 'In Attesa' && $this->stats['pending'] > 0)
                            <span
                                class="absolute top-2 right-2 flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs xl:text-base font-black text-gray-500 uppercase tracking-widest">
                            {{ $stat['label'] }}
                        </h3>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- BOOKINGS --}}
    <div class="px-4 sm:px-0 mb-8">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
            Prenotazioni
        </h2>
    </div>

    {{-- MESSAGES --}}
    @if (session()->has('booked') || session()->has('cancelled'))
        <div x-data="{ show: true }" x-show="show"
            class="mx-4 sm:mx-0 mb-6 p-4 rounded-r-xl border-l-4 {{ session()->has('booked') ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700' }}">
            <div class="flex items-center justify-between font-medium">
                <span>{{ session('booked') ?? session('cancelled') }}</span>
                <button @click="show = false"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none tracking-tight">Chiudi</button>
            </div>
        </div>
    @endif

    {{-- DESKTOP VIEW --}}
    <div
        class="hidden lg:block bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-center">

            {{-- TABLE COLUMNS --}}
            <thead class="text-xs font-black uppercase text-gray-500 bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-2 py-4">ID</th>
                    <th class="px-2 py-4">Cliente</th>
                    <th class="px-2 py-4">Periodo</th>
                    <th class="px-2 py-4">Pagamento</th>
                    <th class="px-2 py-4">Stato</th>
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

                        {{-- CUSTOMER INFO --}}
                        <td class="px-2 py-4">
                            <p class="text-gray-900 dark:text-white uppercase">
                                {{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</p>
                            <p class="text-xs text-gray-500 italic">{{ $booking->customer_email }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->customer_phone }}</p>
                        </td>

                        {{-- BOOKING DATES --}}
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
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Pagato parz.
                                </span>
                            @elseif ($booking->payment_status === 'fully_paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Pagato intero
                                </span>
                            @elseif ($booking->payment_status === 'penalty_paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Penale pagata
                                </span>
                            @elseif ($booking->payment_status === 'refunded_stripe')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Rimb. Stripe
                                </span>
                            @elseif ($booking->payment_status === 'refunded_manual')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Rimb. Manuale
                                </span>
                            @elseif ($booking->payment_status === 'no_refund')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    No Rimborso
                                </span>
                            @elseif ($booking->payment_status === 'unpaid')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                    Non pagato
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                    Errore
                                </span>
                            @endif
                        </td>

                        {{-- BOOKING STATUS --}}
                        <td class="px-2 py-4 text-xs uppercase">
                            @if ($booking->status === 'confirmed')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Confermata
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700">
                                    In attesa
                                </span>
                            @elseif ($booking->status === 'expired')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                    Scaduta
                                </span>
                            @elseif ($booking->status === 'cancelled')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
                                    Cancellata
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">
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
                                    <span class="text-gray-500">Totale:</span>
                                    <span
                                        class="text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                                </div>

                                {{-- Deposit --}}
                                <div class="flex justify-between w-36">
                                    <span class="text-gray-500">Acconto:</span>
                                    @if ($booking->payment_status === 'unpaid' && ($booking->status === 'expired' || $booking->status === 'cancelled'))
                                        <span class="text-gray-400">0,00€</span>
                                    @elseif ($booking->payment_status === 'unpaid')
                                        <span class="text-amber-500 animate-pulse">
                                            {{ number_format($booking->deposit_amount, 2, ',', '.') }}€</span>
                                    @else
                                        <span
                                            class="text-green-600">{{ number_format($booking->deposit_amount, 2, ',', '.') }}€</span>
                                    @endif
                                </div>

                                {{-- Balance --}}
                                <div class="flex justify-between w-36 pt-1">
                                    <span class="text-gray-500">Saldo:</span>

                                    @if (
                                        $booking->status === 'cancelled' &&
                                            $booking->calculateExpectedRefund()['penalty_amount'] > $booking->deposit_amount &&
                                            $booking->payment_status !== 'fully_paid' &&
                                            $booking->payment_status !== 'penalty_paid')
                                        <span class="text-amber-500 animate-pulse">
                                            {{ number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') }}€
                                        </span>
                                    @elseif ($booking->status === 'cancelled' && $booking->payment_status === 'penalty_paid')
                                        <span class="text-green-600">
                                            {{ number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') }}€
                                        </span>
                                    @elseif ($booking->payment_status === 'fully_paid')
                                        <span
                                            class="text-green-600">{{ number_format($booking->balance_amount, 2, ',', '.') }}€
                                        </span>
                                    @elseif ($booking->status === 'cancelled' || $booking->status === 'expired')
                                        <span class="text-gray-400">
                                            0,00€
                                        </span>
                                    @else
                                        <span class="text-amber-500 animate-pulse">
                                            {{ number_format($booking->balance_amount, 2, ',', '.') }}€
                                        </span>
                                    @endif
                                </div>

                                {{-- Penalty --}}
                                @if ($booking->status === 'cancelled' && $booking->payment_status === 'no_refund')
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500">Penale:</span>
                                        <span
                                            class="text-amber-500 animate-pulse">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                        </span>
                                    </div>
                                @elseif ($booking->status === 'cancelled' && $booking->payment_status !== 'no_refund')
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500">Penale:</span>
                                        <span
                                            class="text-green-600">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                                        </span>
                                    </div>
                                @endif

                                {{-- Refund --}}
                                @if ($booking->refund_amount > 0)
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500">Rimborso:</span>
                                        <span
                                            class="text-red-600">-{{ number_format($booking->refund_amount, 2, ',', '.') }}€
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
                                    class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 border border-gray-600 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm disabled:opacity-50">
                                    Dettagli
                                </button>

                                {{-- Complete --}}
                                @if (
                                    ($booking->status === 'confirmed' ||
                                        ($booking->status === 'cancelled' && $booking->payment_status === 'no_refund')) &&
                                        $booking->payment_status !== 'fully_paid')
                                    <button type="button"
                                        onclick="confirmPayment('{{ $booking->id }}', {{ $booking->status === 'cancelled' ? max(0, $booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount) : $booking->balance_amount }})"
                                        class="bg-gray-100 dark:bg-gray-700 hover:bg-green-600 dark:hover:bg-green-600 border border-green-600 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                                        Completa
                                    </button>
                                @endif

                                {{-- Confirm --}}
                                @if ($booking->status === 'pending' && $booking->payment_status === 'paid')
                                    <button type="button"
                                        onclick="confirmHostAction('{{ $booking->id }}', '{{ $booking->customer_first_name }}', '{{ $booking->customer_last_name }}', '{{ $booking->start_date->format('d/m/Y') }}', '{{ $booking->end_date->format('d/m/Y') }}')"
                                        class="bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                                        Conferma
                                    </button>
                                @endif

                                {{-- Cancel --}}
                                @if ($booking->status !== 'cancelled' && $booking->payment_status === 'paid')
                                    <button type="button"
                                        onclick="confirmRefundAction('{{ $booking->id }}', {{ $booking->calculateExpectedRefund()['refund_amount'] }}, {{ $booking->calculateExpectedRefund()['penalty_percent'] }}, {{ $booking->stripe_payment_id ? 1 : 0 }})"
                                        class="bg-gray-100 dark:bg-gray-700 hover:bg-red-600 dark:hover:bg-red-600 border border-red-600 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                                        Annulla
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
                <div class="flex justify-center items-center mb-4">
                    <div class="text-center">

                        {{-- ID --}}
                        <span
                            class="font-mono text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-900 py-1 px-1 rounded">
                            #{{ $booking->id }}
                        </span>

                        <div class="flex justify-center items-center gap-1.5 text-xs uppercase font-black my-3">

                            {{-- PAYMENT STATUS --}}
                            @if ($booking->payment_status === 'paid')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    Pagato parz.
                                </span>
                            @elseif ($booking->payment_status === 'fully_paid')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    Pagato intero
                                </span>
                            @elseif ($booking->payment_status === 'penalty_paid')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">
                                    Penale pagata
                                </span>
                            @elseif ($booking->payment_status === 'refunded_stripe')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    Rimb. Stripe
                                </span>
                            @elseif ($booking->payment_status === 'refunded_manual')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    Rimb. Manuale
                                </span>
                            @elseif ($booking->payment_status === 'no_refund')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    No Rimborso
                                </span>
                            @elseif ($booking->payment_status === 'unpaid')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                    Non pagato
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                    Errore
                                </span>
                            @endif

                            {{-- BOOKING STATUS --}}
                            @if ($booking->status === 'confirmed')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">
                                    Confermata
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                                    In attesa
                                </span>
                            @elseif ($booking->status === 'expired')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                    Scaduta
                                </span>
                            @elseif ($booking->status === 'cancelled')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                    Cancellata
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">
                                    Errore
                                </span>
                            @endif

                        </div>

                        {{-- CUSTOMER INFO --}}
                        <h3 class="text-gray-900 dark:text-white uppercase mt-1">
                            {{ $booking->customer_first_name }} {{ $booking->customer_last_name }}
                        </h3>

                        <p class="text-sm text-gray-500 italic">{{ $booking->customer_email }}</p>

                        <p class="text-sm text-gray-500">{{ $booking->customer_phone }}</p>

                        {{-- BOOKING DATES --}}
                        <p class="text-amber-500 mt-1">{{ $booking->camper->name }}
                        </p>

                        <p class="text-gray-700 dark:text-gray-300 ">{{ $booking->start_date->format('d/m/Y') }}
                            <span class="text-amber-500">➔</span>
                            {{ $booking->end_date->format('d/m/Y') }}
                        </p>
                    </div>


                </div>

                {{-- BALANCE --}}
                <div class="my-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl space-y-2 text-xs uppercase">

                    {{-- Total --}}
                    <div class="flex justify-between">
                        <span class="text-gray-500">Totale:</span>
                        <span
                            class="font-black text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€
                        </span>
                    </div>

                    {{-- Deposit --}}
                    <div class="flex justify-between">
                        <span class="text-gray-500">Acconto:</span>

                        @if ($booking->payment_status === 'unpaid' && ($booking->status === 'expired' || $booking->status === 'cancelled'))
                            <span class="text-gray-400">0,00€</span>
                        @elseif ($booking->payment_status === 'unpaid')
                            <span class="text-amber-500 animate-pulse">
                                {{ number_format($booking->deposit_amount, 2, ',', '.') }}€
                            </span>
                        @else
                            <span class="text-green-600">
                                {{ number_format($booking->deposit_amount, 2, ',', '.') }}€
                            </span>
                        @endif

                    </div>

                    {{-- Balance --}}
                    <div class="flex justify-between">
                        <span class="text-gray-500">Saldo:</span>

                        @if (
                            $booking->status === 'cancelled' &&
                                $booking->calculateExpectedRefund()['penalty_amount'] > $booking->deposit_amount &&
                                $booking->payment_status !== 'fully_paid' &&
                                $booking->payment_status !== 'penalty_paid')
                            <span class="text-amber-500 animate-pulse">
                                {{ number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') }}€
                            </span>
                        @elseif ($booking->status === 'cancelled' && $booking->payment_status === 'penalty_paid')
                            <span class="text-green-600">

                                {{ number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') }}€
                            </span>
                        @elseif ($booking->payment_status === 'fully_paid')
                            <span class="text-green-600">
                                {{ number_format($booking->balance_amount, 2, ',', '.') }}€
                            </span>
                        @elseif ($booking->status === 'cancelled' || $booking->status === 'expired')
                            <span class="text-gray-400">0,00€</span>
                        @else
                            <span class="text-amber-500 animate-pulse">
                                {{ number_format($booking->balance_amount, 2, ',', '.') }}€
                            </span>
                        @endif

                    </div>

                    {{-- Penalty --}}
                    @if ($booking->status === 'cancelled' && $booking->payment_status === 'no_refund')
                        <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Penale:</span>
                            <span
                                class="text-amber-500 animate-pulse">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                            </span>
                        </div>
                    @elseif ($booking->status === 'cancelled' && $booking->payment_status !== 'no_refund')
                        <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Penale:</span>
                            <span
                                class="text-green-600">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€
                            </span>
                        </div>
                    @endif

                    {{-- Refund --}}
                    @if ($booking->refund_amount > 0)
                        <div class="flex justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500">Rimborso:</span>
                            <span
                                class="font-black text-red-600">-{{ number_format($booking->refund_amount, 2, ',', '.') }}€</span>
                        </div>
                    @endif
                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-col gap-2 uppercase">

                    {{-- Details --}}
                    <button type="button" wire:click="openBookingDetails('{{ $booking->id }}')"
                        wire:loading.attr="disabled" wire:target="openBookingDetails('{{ $booking->id }}')"
                        class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 border border-gray-600 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm disabled:opacity-50">
                        Dettagli
                    </button>

                    {{-- Complete --}}
                    @if (
                        ($booking->status === 'confirmed' ||
                            ($booking->status === 'cancelled' && $booking->payment_status === 'no_refund')) &&
                            $booking->payment_status !== 'fully_paid')
                        <button type="button"
                            onclick="confirmPayment('{{ $booking->id }}', {{ $booking->status === 'cancelled' ? max(0, $booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount) : $booking->balance_amount }})"
                            class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-green-600 dark:hover:bg-green-600 border border-green-600 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                            Completa
                        </button>
                    @endif

                    {{-- Confirm --}}
                    @if ($booking->status === 'pending' && $booking->payment_status === 'paid')
                        <button type="button"
                            onclick="confirmHostAction('{{ $booking->id }}', '{{ $booking->customer_first_name }}', '{{ $booking->customer_last_name }}', '{{ $booking->start_date->format('d/m/Y') }}', '{{ $booking->end_date->format('d/m/Y') }}')"
                            class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-amber-500 dark:hover:bg-amber-500 border border-amber-500 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                            Conferma
                        </button>
                    @endif

                    {{-- Cancel --}}
                    @if ($booking->status !== 'cancelled' && $booking->payment_status === 'paid')
                        <button type="button"
                            onclick="confirmRefundAction('{{ $booking->id }}', {{ $booking->calculateExpectedRefund()['refund_amount'] }}, {{ $booking->calculateExpectedRefund()['penalty_percent'] }}, {{ $booking->stripe_payment_id ? 1 : 0 }})"
                            class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-red-600 dark:hover:bg-red-600 border border-red-600 text-black dark:text-white text-xs py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                            Annulla
                        </button>
                    @endif

                </div>
            </div>
        @endforeach
    </div>

    {{-- REFUNDCONTROLLER HIDDEN FORM --}}
    @foreach ($bookings as $booking)
        <form id="refund-form-{{ $booking->id }}" action="{{ route('bookings.refund', $booking) }}" method="POST"
            style="display:none">
            @csrf
            <input type="hidden" name="use_stripe" id="stripe-input-{{ $booking->id }}" value="0">
        </form>
    @endforeach

    {{-- PAGING --}}
    <div class="mt-6 px-4">
        {{ $bookings->links() }}
    </div>

    {{-- MODAL --}}
    <div x-data="{ open: false, b: {} }"
        @open-booking-modal.window="open = true; b = Array.isArray($event.detail) ? $event.detail[0] : ($event.detail.detail ? $event.detail.detail : $event.detail)"
        @keyup.escape.window="open = false"
        x-effect="if (open) { document.body.classList.add('overflow-hidden') } else { document.body.classList.remove('overflow-hidden') }"
        x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @click="open = false"></div>

        <div class="flex min-h-full items-center justify-center p-3 text-center sm:p-0">
            <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
                x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 opacity-0"
                class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl sm:my-3 w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">

                {{-- Header --}}
                <div
                    class="bg-gray-50 dark:bg-gray-700/50 font-black px-6 py-3 border-b border-gray-100 dark:border-gray-700 ">

                    {{-- ID --}}
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl text-gray-900 dark:text-white uppercase tracking-wider">
                            Prenotazione
                            <span class="bg-gray-200 dark:bg-gray-900 py-1 px-1">#<span x-text="b.id"></span></span>
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
                            Ricevuta il: <span class="text-gray-700 dark:text-gray-200" x-text="b.created_at"></span>
                        </p>
                    </div>

                    {{-- ULID --}}
                    <div class="pt-1">
                        <p
                            class="text-[10px] text-gray-400 uppercase tracking-widest selection:bg-amber-500 selection:text-black">
                            ULID: <span class="select-all" x-text="b.ulid"></span>
                        </p>
                    </div>

                </div>

                <div class="p-2 space-y-2">

                    {{-- Customer Info --}}
                    <div class="text-center">
                        <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Dati Cliente</h4>

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 font-black rounded-xl border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">

                            {{-- Name --}}
                            <div class="p-2">
                                <p class="text-gray-400 uppercase tracking-wider mb-0.5">Nome:
                                </p>
                                <p class="font-bold text-gray-900 dark:text-white uppercase" x-text="b.name"></p>
                            </div>

                            {{-- Email --}}
                            <div class="p-2">
                                <p class="text-gray-400 uppercase tracking-wider mb-0.5">Email:
                                </p>
                                <a :href="'mailto:' + b.email"
                                    class="font-bold italic text-gray-900 dark:text-white hover:underline block"
                                    x-text="b.email"></a>
                            </div>

                            {{-- Phone --}}
                            <div class="p-2">
                                <p class="text-gray-400 uppercase tracking-wider mb-0.5">
                                    Telefono:</p>
                                <p class="font-bold text-gray-900 dark:text-white" x-text="b.phone"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Info --}}
                    <div class="text-center">
                        <h4 class="text-lg font-black text-gray-400 uppercase tracking-widest mb-2">Dettagli Noleggio
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
                                <p class="font-bold text-gray-900 dark:text-white">
                                    <span x-text="b.start"></span>
                                    <span class="text-amber-500 mx-1">➔</span>
                                    <span x-text="b.end"></span>
                                </p>
                            </div>

                            {{-- Payment Status --}}
                            <div class="p-2">
                                <p class="text-gray-400 uppercase tracking-wider mb-1">Stato
                                    Pagamento:</p>

                                <div class="uppercase inline-block">
                                    <template x-if="b.payment_status === 'paid'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">Pagato
                                            Parzialmente</p>
                                    </template>
                                    <template x-if="b.payment_status === 'fully_paid'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">Pagato
                                            Intero</p>
                                    </template>
                                    <template x-if="b.payment_status === 'penalty_paid'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">Penale Pagata
                                        </p>
                                    </template>
                                    <template x-if="b.payment_status === 'refunded_stripe'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">Rimborso
                                            Stripe</p>
                                    </template>
                                    <template x-if="b.payment_status === 'refunded_manual'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">Rimborso
                                            Manuale</p>
                                    </template>
                                    <template x-if="b.payment_status === 'no_refund'">
                                        <p class="px-2 py-0.5 rounded-full bg-green-100 text-green-700">No
                                            Rimborso</p>
                                    </template>
                                    <template x-if="b.payment_status === 'unpaid'">
                                        <p class="px-2 py-0.5 rounded-full bg-red-100 text-red-700">Non pagato</p>
                                    </template>
                                </div>
                            </div>

                            {{-- Booking Status --}}
                            <div class="p-2">
                                <p class="text-gray-400 uppercase tracking-wider mb-1">Stato
                                    Prenotazione:</p>

                                <div class="uppercase inline-block">
                                    <template x-if="b.status === 'confirmed'">
                                        <p class="px-3 py-0.5 rounded-full bg-green-100 text-green-700">Confermata</p>
                                    </template>
                                    <template x-if="b.status === 'pending'">
                                        <p class="px-3 py-0.5 rounded-full bg-amber-100 text-amber-700">In
                                            attesa</p>
                                    </template>
                                    <template x-if="b.status === 'expired'">
                                        <p class="px-3 py-0.5 rounded-full bg-red-100 text-red-700">Scaduta</p>
                                    </template>
                                    <template x-if="b.status === 'cancelled'">
                                        <p class="px-3 py-0.5 rounded-full bg-red-100 text-red-700">Cancellata</p>
                                    </template>
                                </div>
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
                                <span class="text-gray-500 uppercase">Totale Noleggio:</span>
                                <span class="font-black text-gray-900 dark:text-white" x-text="b.total"></span>
                            </div>

                            {{-- Deposit --}}
                            <div
                                class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="text-gray-500 uppercase">Acconto (30%):</span>
                                <template
                                    x-if="b.payment_status === 'unpaid' && (b.status === 'expired' || b.status === 'cancelled')">
                                    <span class="text-gray-400">0,00€</span>
                                </template>

                                <template x-if="b.payment_status === 'unpaid' && b.status === 'pending'">
                                    <span class="text-amber-500 animate-pulse font-black" x-text="b.deposit"></span>
                                </template>

                                <template x-if="b.payment_status !== 'unpaid'">
                                    <span class="text-green-600 font-bold" x-text="b.deposit"></span>
                                </template>
                            </div>

                            {{-- Balance --}}
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 uppercase">Saldo Rimanente:</span>

                                <template
                                    x-if="b.status === 'cancelled' && (b.penaltyRaw > b.deposit_amount) && b.payment_status !== 'fully_paid' && b.payment_status !== 'penalty_paid'">
                                    <span class="text-amber-500 animate-pulse font-black" x-text="b.balance"></span>
                                </template>

                                <template x-if="b.status === 'cancelled' && b.payment_status === 'penalty_paid'">
                                    <span class="text-green-600" x-text="b.balance"></span>
                                </template>

                                <template x-if="b.payment_status === 'fully_paid' && b.status !== 'cancelled'">
                                    <span class="text-green-600" x-text="b.balance"></span>
                                </template>

                                <template
                                    x-if="(b.status === 'cancelled' || b.status === 'expired') && b.payment_status !== 'fully_paid' && !(b.penaltyRaw > b.deposit_amount)">
                                    <span class="text-gray-400">0,00€</span>
                                </template>

                                <template
                                    x-if="b.status !== 'cancelled' && b.status !== 'expired' && b.payment_status !== 'fully_paid'">
                                    <span class="text-amber-500 animate-pulse" x-text="b.balance"></span>
                                </template>
                            </div>

                            {{-- Penalty --}}
                            <template x-if="b.status === 'cancelled' && b.penaltyRaw > 0">
                                <div
                                    class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 uppercase">Penale applicata:</span>
                                    <span
                                        :class="{
                                            'text-green-600': b.payment_status !== 'no_refund',
                                            'text-amber-500 animate-pulse': b.payment_status === 'no_refund',
                                        }"
                                        x-text="b.penalty"></span>
                                </div>
                            </template>

                            {{-- Refund --}}
                            <template x-if="b.refundRaw > 0">
                                <div
                                    class="flex justify-between text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-500 uppercase">Rimborso:</span>
                                    <span class="text-red-600" x-text="'-' + b.refund"></span>
                                </div>
                            </template>



                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
