@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

<x-app-layout title="Completa Prenotazione">

    <div class="bg-gray-50 dark:bg-gray-900 flex items-center justify-center min-h-[calc(100vh-160px)]">
        <div class="max-w-5xl mx-auto px-4 w-full">

            <div class="mb-4">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase text-center">
                    Completa la tua prenotazione
                </h1>
            </div>

            <div class="flex items-center justify-center lg:justify-start">
                <a href="{{ route('show', $camper) }}"
                    onclick="if (history.length > 1) { event.preventDefault(); history.back(); }"
                    class="text-sm font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider group mb-5 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                    <i
                        class="fa-solid fa-arrow-left mr-1.5 transition duration-300 group-hover:-translate-x-1"></i>
                    {{ __('Torna indietro') }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center justify-center w-full">

                <div class="lg:col-span-2 w-full">
                    <livewire:forms.booking-form :camper="$camper" />
                </div>

                <div class="lg:col-span-1 w-full flex flex-col">
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-[2rem] shadow-sm border border-gray-300 dark:border-gray-700 w-full flex flex-col">

                        <div
                            class="w-full h-64 rounded-[2rem] overflow-hidden mb-4 bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 flex-shrink-0">
                            <img src="{{ asset('storage/' . $camper->image_path) }}"
                                alt="Foto del camper {{ $camper->name }}" loading="lazy"
                                class="w-full h-full object-cover object-center block">
                        </div>

                        <div class="space-y-1 text-center w-full">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Stai prenotando:</p>
                            <h3 class="font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                {{ $camper->name }}</h3>
                            <p class="text-gray-400 text-xs font-semibold mt-1">
                                <i class="fa-solid fa-circle-check text-amber-500 mr-0.5"></i>
                                Pagamento sicuro
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
