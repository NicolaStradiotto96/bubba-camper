<x-app-layout>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 flex items-center min-h-[calc(100vh-160px)]">
        <div class="max-w-5xl mx-auto px-4">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Completa la tua prenotazione</h1>
                    <p class="text-gray-600 dark:text-gray-400">Stai prenotando: <span
                            class="font-bold text-amber-600">{{ $camper->name }}</span></p>
                </div>
                <a href="{{ route('index') }}" class="text-sm text-gray-500 hover:underline">Modifica
                    scelta</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2">
                    <livewire:forms.booking-form :camper="$camper" />
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $camper->image_path) }}"
                            class="rounded-xl mb-4 w-full h-40 object-cover">
                        <div class="space-y-2">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $camper->name }}</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                Assistenza stradale inclusa
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                Sanificazione inclusa
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
