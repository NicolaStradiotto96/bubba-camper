<x-app-layout>

    {{-- TITLE --}}
    <header class="flex justify-center mt-5">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">PREZZI</h1>
    </header>

    <section class="grid grid-cols-1 gap-6 py-16">

        {{-- GENERAL PRICES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 pb-16 max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-b-4 border-blue-400 p-8 text-center">
                <h3 class="text-xl font-bold uppercase tracking-wider text-blue-400">Bassa Stagione</h3>
                <div class="my-4">
                    <span class="text-5xl font-extrabold dark:text-white">100€</span>
                    <span class="text-gray-500">/gg</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold uppercase">1 Gen - 31 Mar<br>1 Nov - 31
                    Dic</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border-b-4 border-amber-400 p-8 text-center">
                <h3 class="text-xl font-bold uppercase tracking-wider text-amber-600">Media Stagione</h3>
                <div class="my-4">
                    <span class="text-5xl font-extrabold dark:text-white">120€</span>
                    <span class="text-gray-500">/gg</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold uppercase">1 Apr - 30 Giu<br>1 Set - 31
                    Ott</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-b-4 border-red-500 p-8 text-center">
                <h3 class="text-xl font-bold uppercase tracking-wider text-red-500">Alta Stagione</h3>
                <div class="my-4">
                    <span class="text-5xl font-extrabold dark:text-white">140€</span>
                    <span class="text-gray-500">/gg</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold uppercase">1 Luglio - 31 Agosto</p>
            </div>
        </div>

        {{-- KM PRICES --}}
        <div class="mx-4">
            <div
                class="max-w-3xl mx-auto my-7 bg-green-100 dark:bg-green-900/20 border-l-8 border-green-500 p-6 rounded-r-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <div class="ml-6">
                        <h4 class="text-lg font-bold text-green-600 dark:text-green-500 uppercase">Dettagli
                            Chilometraggio</h4>
                        <p class="text-green-500 dark:text-green-400 font-bold">150 KM compresi al giorno.</p>
                        <p class="text-green-500 dark:text-green-400">Per i chilometri eccedenti è prevista una tariffa
                            di <span class="underline decoration-2 text-l font-bold">0,20€/KM</span>.</p>
                    </div>
                </div>
            </div>
        </div>

    </section>

</x-app-layout>
