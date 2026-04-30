<div class="max-w-7xl mx-auto py-10">

    {{-- TITLE --}}
    <div class="px-4 sm:px-0 mb-8">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight text-center">
            I Tuoi Viaggi
        </h2>
    </div>

    {{-- DESKTOP VIEW --}}
    <div class="hidden lg:block bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">ID</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Camper</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Periodo</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Totale</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Pagamento</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase text-gray-500 text-center">Stato</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($bookings as $booking)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-4 text-sm text-center">
                            <span class="font-mono bg-gray-900 py-[2px] px-[5px] text-gray-700 dark:text-gray-300">#{{ $booking->id }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm text-amber-600 font-bold text-center tracking-wide">
                            {{ $booking->camper->name }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="font-medium">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                                <span class="text-amber-500 font-bold">➔</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-gray-900 dark:text-white">
                            {{ number_format($booking->total_price, 2, ',', '.') }}€
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if ($booking->payment_status === 'paid')
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700">Pagata</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-700">Non pagata</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'pending'   => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'expired'   => 'bg-red-50 text-red-500',
                                    'cancelled' => 'bg-gray-100 text-gray-500',
                                    'completed' => 'bg-blue-100 text-blue-700'
                                ];
                                $statusLabels = [
                                    'pending'   => 'In Attesa',
                                    'confirmed' => 'Confermata',
                                    'expired'   => 'Scaduta',
                                    'cancelled' => 'Annullata',
                                    'completed' => 'Concluso'
                                ];
                            @endphp
                            <span class="{{ $statusClasses[$booking->status] ?? 'bg-gray-100' }} px-3 py-1 rounded-full text-[10px] font-black uppercase text-nowrap">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MOBILE VIEW --}}
    <div class="lg:hidden space-y-4 px-2">
        @foreach ($bookings as $booking)
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">#{{ $booking->id }}</p>
                        <h3 class="font-black text-amber-600 uppercase">{{ $booking->camper->name }}</h3>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($booking->total_price, 2, ',', '.') }}€</p>
                    </div>
                    <div class="flex flex-col gap-2 items-end">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $booking->payment_status === 'paid' ? 'Pagata' : 'Da Pagare' }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $statusClasses[$booking->status] ?? '' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </div>
                </div>

                <div class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-600">
                    <div class="flex justify-center items-center text-xs space-x-3">
                        <span class="font-bold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                        <span class="text-amber-500 font-bold">➔</span>
                        <span class="font-bold">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 px-4 sm:px-0">
        {{ $bookings->links() }}
    </div>
</div>