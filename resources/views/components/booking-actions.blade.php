<div class="max-w-7xl mx-auto mb-10">
    <div
        class="p-6 md:p-8 bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 mx-4">

        <div class="px-4 sm:px-0 mb-6">
            <h2
                class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white uppercase tracking-tight text-center">
                Operatività
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $actions = [
                    [
                        'route' => 'camper.create',
                        'icon' => 'fa-van-shuttle',
                        'label' => 'Nuovo Camper',
                        'color' => 'amber',
                    ],
                    [
                        'route' => 'booking.create',
                        'icon' => 'fa-calendar-plus',
                        'label' => 'Nuova Prenotazione',
                        'color' => 'amber',
                    ],
                    [
                        'route' => 'maintenance',
                        'icon' => 'fa-screwdriver-wrench',
                        'label' => 'Manutenzione',
                        'color' => 'amber',
                    ],
                    [
                        'route' => 'damage.index',
                        'icon' => 'fa-exclamation-triangle',
                        'label' => 'Gestione Danni',
                        'color' => 'amber',
                    ],
                ];
            @endphp

            @foreach ($actions as $action)
                <a href="{{ route($action['route']) }}" wire:navigate
                    class="group flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-{{ $action['color'] }}-500 dark:hover:border-{{ $action['color'] }}-500 hover:bg-white dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 transition shadow-sm hover:shadow-md">

                    <div
                        class="w-12 h-12 mb-4 flex items-center justify-center rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-inner group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid {{ $action['icon'] }} text-xl text-{{ $action['color'] }}-500"></i>
                    </div>

                    <span
                        class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors uppercase">
                        {{ $action['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
