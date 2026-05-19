<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-[calc(100vh-160px)] flex items-center">
        <div class="w-full md:w-[48rem] max-w-3xl mx-auto px-4 text-center">
            <div
                class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700">

                <div>
                    <livewire:user.payment-reminder />

                    <div class="flex flex-col gap-4 items-center">
                        <p class="text-[11px] text-gray-400 max-w-sm italic">
                            Ricorda: se il tempo scade, la prenotazione verrà annullata automaticamente.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                            <a href="{{ route('dashboard') }}"
                                class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                Vai alla tua Dashboard
                            </a>

                            <a href="{{ route('index') }}"
                                class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                                Torna ai Camper
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
