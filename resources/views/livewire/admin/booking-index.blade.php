<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 mx-2">

        {{-- Card: Earnings --}}
        <div class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-2xl shadow-sm border border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Incasso</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">
                        {{ number_format($this->stats['earnings'], 0, ',', '.') }}€</p>
                </div>
            </div>
        </div>

        {{-- Card: Bookings --}}
        <div class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-2xl shadow-sm border border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Totali</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $this->stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Confirmed Bookings --}}
        <div class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-2xl shadow-sm border border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Confermate</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $this->stats['confirmed'] }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Pending --}}
        <div class="bg-white dark:bg-gray-800 py-5 px-3 flex rounded-2xl shadow-sm border border-l-4 border-amber-500">
            <div class="flex items-center">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg relative">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @if ($this->stats['pending'] > 0)
                        <span class="absolute top-2 right-2 flex h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </div>
                <div class="ml-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">In Attesa</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $this->stats['pending'] }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- TITLE --}}
    <div class="px-4 sm:px-0 flex justify-between items-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight">
            Prenotazioni <span class="text-amber-600">{{ config('app.name', 'Bubba Camper') }}</span>
        </h1>
    </div>

    @if (session()->has('booked') || session()->has('cancelled'))
        <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="relative mx-4 sm:mx-0 mb-6 p-4 shadow-sm rounded-r-xl font-medium border-l-4 
            {{ session()->has('booked') ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700' }}">
            <div class="flex items-center justify-between">
                <span>
                    {{ session('booked') ?? session('cancelled') }}
                </span>

                <button @click="show = false"
                    class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- DESKTOP VIEW --}}
    <div
        class="hidden md:block bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Cliente</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Camper</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500">Periodo</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Pagamento</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Stato</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-right">Azioni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($bookings as $booking)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <span class="text-xs font-mono text-gray-400">#{{ $booking->id }}</span>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $booking->customer_first_name }}
                                {{ $booking->customer_last_name }}</div>
                            <div class="text-xs text-gray-500 italic">{{ $booking->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{ $booking->camper->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                            <div class="flex items-center space-x-2">
                                <span
                                    class="font-medium text-nowrap">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                                <span class="text-gray-400">➔</span>
                                <span
                                    class="font-medium text-nowrap">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($booking->payment_status === 'paid')
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700">Pagata</span>
                            @else
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-700">Non
                                    pagata</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($booking->status === 'pending')
                                <span
                                    class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase text-nowrap">In
                                    attesa</span>
                            @elseif ($booking->status === 'expired')
                                <span
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase text-nowrap">Scaduta</span>
                            @elseif ($booking->status === 'cancelled')
                                <span
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase text-nowrap">Annullata</span>
                            @else
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase text-nowrap">Confermata</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if ($booking->status === 'pending' && $booking->payment_status === 'paid')
                                    <button wire:click="confirmBooking({{ $booking->id }})"
                                        wire:confirm="Vuoi confermare questo noleggio?"
                                        class="bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-bold py-2 px-3 rounded-lg transition-all uppercase shadow-sm">
                                        Conferma
                                    </button>
                                @endif

                                @if ($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <button wire:click="cancelBooking({{ $booking->id }})"
                                        wire:confirm="Sei sicuro di voler annullare questa prenotazione? L'utente non potrà più procedere."
                                        class="bg-white dark:bg-gray-700 border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 hover:bg-red-50 text-[10px] font-bold py-2 px-3 rounded-lg transition-all uppercase shadow-sm">
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
    <div class="md:hidden space-y-4 px-4">
        @foreach ($bookings as $booking)
            <div
                class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">Prenotazione
                            #{{ $booking->id }}</p>
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $booking->customer_first_name }}
                            {{ $booking->customer_last_name }}</h3>
                        <p class="text-sm text-amber-600 font-semibold">{{ $booking->camper->name }}</p>
                    </div>
                    <div class="flex flex-col gap-2 items-end">
                        @if ($booking->payment_status === 'paid')
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-green-100 text-green-700">Pagata</span>
                        @else
                            <span
                                class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-red-100 text-red-700">Non
                                pagata</span>
                        @endif
                        @if ($booking->status === 'pending')
                            <span
                                class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">In
                                attesa</span>
                        @elseif ($booking->status === 'expired')
                            <span
                                class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">Scaduta</span>
                        @elseif ($booking->status === 'cancelled')
                            <span
                                class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">Annullata</span>
                        @else
                            <span
                                class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">Confermata</span>
                        @endif
                    </div>
                </div>

                <div
                    class="text-sm text-gray-700 dark:text-gray-300 mb-4 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-600">
                    <div class="flex justify-between items-center text-xs">
                        <span
                            class="font-bold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                        <span class="text-gray-400">➔</span>
                        <span
                            class="font-bold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    @if ($booking->status === 'pending' && $booking->payment_status === 'paid')
                        <button wire:click="confirmBooking({{ $booking->id }})"
                            class="w-full bg-amber-600 text-white font-bold py-3 rounded-xl uppercase tracking-widest text-xs shadow-lg">
                            Conferma Prenotazione
                        </button>
                    @endif

                    @if ($booking->status === 'pending' || $booking->status === 'confirmed')
                        <button wire:click="cancelBooking({{ $booking->id }})"
                            wire:confirm="Annullare la prenotazione?"
                            class="w-full bg-gray-100 dark:bg-gray-700 text-red-600 dark:text-red-400 font-bold py-3 rounded-xl uppercase tracking-widest text-xs">
                            Annulla Prenotazione
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 px-4 sm:px-0">
        {{ $bookings->links() }}
    </div>
</div>
