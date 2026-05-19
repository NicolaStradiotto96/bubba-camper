<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

    {{-- STATS --}}
    <div class="px-4 sm:px-0 mb-8">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
            Statistiche
        </h2>
    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8 mx-2">
        @foreach ([['label' => 'Incasso', 'value' => number_format($this->stats['earnings'], 0, ',', '.') . '€', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'], ['label' => 'Totali', 'value' => $this->stats['total'], 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'green'], ['label' => 'Confermate', 'value' => $this->stats['confirmed'], 'icon' => 'M5 13l4 4L19 7', 'color' => 'green'], ['label' => 'In Attesa', 'value' => $this->stats['pending'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber']] as $stat)
            <div
                class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-2xl shadow-sm border border-l-4 border-{{ $stat['color'] }}-500 overflow-hidden">
                <div class="flex items-center">
                    <div class="p-3 bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 rounded-lg relative">
                        <svg class="w-6 h-6 text-{{ $stat['color'] }}-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $stat['icon'] }}"></path>
                        </svg>
                        @if ($stat['label'] === 'In Attesa' && $this->stats['pending'] > 0)
                            <span
                                class="absolute top-2 right-2 flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs xl:text-base font-bold text-gray-500 uppercase tracking-widest">
                            {{ $stat['label'] }}</h3>
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
        class="hidden lg:block bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
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
                                class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-200 dark:bg-gray-900 py-1 px-1">#{{ $booking->id }}</span>
                        </td>

                        {{-- CUSTOMER INFO --}}
                        <td class="px-2 py-4">
                            <p class="text-gray-900 dark:text-white">
                                {{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</p>
                            <p class="text-xs text-gray-500 italic">{{ $booking->customer_email }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->customer_phone }}</p>
                        </td>

                        {{-- BOOKING DATES --}}
                        <td class="px-2 py-4 text-sm">

                            <p class="text-amber-600">{{ $booking->camper->name }}</p>
                            <p class="text-gray-700 dark:text-gray-300 ">{{ $booking->start_date->format('d/m/Y') }}
                                <span class="text-amber-600">➔</span>
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
                        <td class="px-2 py-4 text-xs">
                            <div class="flex flex-col items-center gap-1">

                                {{-- Total --}}
                                <div
                                    class="flex justify-between w-36 border-b border-gray-100 dark:border-gray-700 pb-1">
                                    <span class="text-gray-500 uppercase">Totale:</span>
                                    <span
                                        class="text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                                </div>

                                {{-- Deposit --}}
                                <div class="flex justify-between w-36">
                                    <span class="text-gray-500 uppercase">Acconto:</span>
                                    @if ($booking->payment_status === 'unpaid' && ($booking->status === 'expired' || $booking->status === 'cancelled'))
                                        <span class="text-gray-400">0,00€</span>
                                    @elseif ($booking->payment_status === 'unpaid')
                                        <span class="text-amber-600 animate-pulse italic">
                                            {{ number_format($booking->deposit_amount, 2, ',', '.') }}€</span>
                                    @else
                                        <span
                                            class="text-green-600">{{ number_format($booking->deposit_amount, 2, ',', '.') }}€</span>
                                    @endif
                                </div>

                                {{-- Balance --}}
                                <div class="flex justify-between w-36 pt-1">
                                    <span class="text-gray-500 uppercase">Saldo:</span>

                                    @if ($booking->status === 'cancelled' && $booking->payment_status === 'fully_paid')
                                        <span class="text-green-600">
                                            {{ number_format(max(0, $booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount), 2, ',', '.') }}€</span>
                                    @elseif ($booking->payment_status === 'fully_paid')
                                        <span class="text-green-600">
                                            {{ number_format($booking->balance_amount, 2, ',', '.') }}€</span>
                                    @elseif (
                                        $booking->status === 'cancelled' &&
                                            $booking->calculateExpectedRefund()['penalty_amount'] > $booking->deposit_amount)
                                        <span
                                            class="text-amber-600 animate-pulse uppercase">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'] - $booking->deposit_amount, 2, ',', '.') }}€</span>
                                    @elseif ($booking->status === 'cancelled' || $booking->status === 'expired')
                                        <span class="text-gray-400">0,00€</span>
                                    @else
                                        <span class="text-amber-600 animate-pulse uppercase">
                                            {{ number_format($booking->balance_amount, 2, ',', '.') }}€
                                        </span>
                                    @endif
                                </div>

                                {{-- Refund --}}
                                @if ($booking->refund_amount > 0)
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500 uppercase">Rimborso:</span>
                                        <span
                                            class="text-red-600">-{{ number_format($booking->refund_amount, 2, ',', '.') }}€</span>
                                    </div>
                                @endif

                                {{-- Penalty --}}
                                @if ($booking->status === 'cancelled' && $booking->payment_status === 'no_refund')
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500 uppercase">Penale:</span>
                                        <span
                                            class="text-amber-600 animate-pulse">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€</span>
                                    </div>
                                @elseif ($booking->status === 'cancelled' && $booking->payment_status === 'fully_paid')
                                    <div
                                        class="flex justify-between w-36 pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-500 uppercase">Penale:</span>
                                        <span
                                            class="text-green-600">{{ number_format($booking->calculateExpectedRefund()['penalty_amount'], 2, ',', '.') }}€</span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- BUTTONS --}}
                        <td class="px-2 py-4">
                            <div class="flex flex-col gap-2 justify-center">

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
                                        onclick="confirmHostAction('{{ $booking->id }}', '{{ $booking->customer_first_name }}')"
                                        class="bg-gray-100 dark:bg-gray-700 hover:bg-amber-600 dark:hover:bg-amber-600 border border-amber-600 text-black dark:text-white text-xs py-2 px-6 rounded-xl uppercase tracking-widest shadow-sm">
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

                    {{-- REFUNDCONTROLLER HIDDEN FORM --}}
                    <form id="refund-form-{{ $booking->id }}" action="{{ route('bookings.refund', $booking) }}"
                        method="POST" style="display:none">
                        @csrf
                        <input type="hidden" name="use_stripe" id="stripe-input-{{ $booking->id }}"
                            value="0">
                    </form>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- MOBILE VIEW --}}
    <div class="lg:hidden space-y-4 px-4">
        @foreach ($bookings as $booking)
            <div
                class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span
                            class="text-[10px] font-mono text-gray-700 dark:text-gray-300 uppercase bg-gray-900 py-1 px-1">#{{ $booking->id }}</span>
                        <h3 class="font-bold text-gray-900 dark:text-white uppercase text-sm">
                            {{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</h3>
                        <div class="text-[11px] text-gray-500 italic">{{ $booking->customer_email }}</div>
                        <p class="text-sm text-amber-600 font-bold uppercase mt-1 italic">{{ $booking->camper->name }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-1 items-end">
                        <span
                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $booking->payment_status === 'paid' ? 'Pagato' : 'In attesa' }}
                        </span>
                    </div>
                </div>
                {{-- DETTAGLIO PREZZI MOBILE --}}
                <div class="my-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 uppercase font-bold">Totale Noleggio:</span>
                        <span
                            class="font-black text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 uppercase">Acconto (30%):</span>
                        <span
                            class="font-bold text-green-600">{{ number_format($booking->deposit_amount, 2, ',', '.') }}€</span>
                    </div>
                    @if ($booking->refund_amount > 0)
                        <div class="flex justify-between text-xs pt-2 border-t border-red-200 dark:border-red-900/50">
                            <span class="text-red-500 uppercase font-black">Rimborso effettuato:</span>
                            <span
                                class="font-black text-red-600">-{{ number_format($booking->refund_amount, 2, ',', '.') }}€</span>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2">
                    @if ($booking->status === 'pending' && $booking->payment_status === 'paid')
                        <button type="button"
                            onclick="confirmHostAction('{{ $booking->id }}', '{{ $booking->customer_first_name }}')"
                            class="w-full bg-amber-600 text-white font-black py-3 rounded-xl uppercase tracking-widest text-xs">
                            Conferma Prenotazione
                        </button>
                    @endif

                    @if ($booking->payment_status === 'paid' && $booking->status !== 'cancelled')
                        <button type="button"
                            onclick="confirmRefundAction('{{ $booking->id }}', {{ $booking->calculateExpectedRefund()['refund_amount'] }}, {{ $booking->calculateExpectedRefund()['penalty_percent'] }}, {{ $booking->stripe_payment_id ? 1 : 0 }})"
                            class="inline-flex justify-center items-center w-full rounded-xl shadow-sm px-2 py-3 bg-white dark:bg-gray-700 text-xs font-black uppercase tracking-widest text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 transition-all focus:outline-none">
                            Annulla / Rimborsa
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 px-4">
        {{ $bookings->links() }}
    </div>
</div>
