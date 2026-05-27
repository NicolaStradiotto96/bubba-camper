<x-app-layout>

    <div class="min-h-[calc(100vh-160px)]">
    {{-- TITLE --}}
    <header class="flex flex-col items-center justify-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-wider">PREZZI
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4 text-center">
            {{ __('Tariffe chiare, senza sorprese. Ogni noleggio include tutto il necessario per partire in totale serenità.') }}
        </p>

        <div class="mt-4 flex justify-center">
            <div class="w-72 md:w-96 h-1 bg-amber-500 rounded-full"></div>
        </div>
    </header>

    <section class="grid grid-cols-1 gap-6 pt-16">

        {{-- GENERAL PRICES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 pb-16 max-w-7xl mx-auto">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-b-6 border-blue-400 p-14 text-center">
                <h3 class="text-xl font-bold uppercase tracking-wider text-blue-400">Bassa Stagione</h3>
                <div class="my-4">
                    <span class="text-5xl font-extrabold dark:text-white">100€</span>
                    <span class="text-gray-500">/gg</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold uppercase">1 Gen - 31 Mar<br>1 Nov - 31
                    Dic</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl shadow-xl border-2 border-b-6 border-yellow-500 p-14 text-center">
                <h3 class="text-xl font-bold uppercase tracking-wider text-yellow-500">Media Stagione</h3>
                <div class="my-4">
                    <span class="text-5xl font-extrabold dark:text-white">120€</span>
                    <span class="text-gray-500">/gg</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold uppercase">1 Apr - 30 Giu<br>1 Set - 31
                    Ott</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-b-6 border-red-500 p-14 text-center">
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
                class="max-w-3xl mx-auto mt-6 bg-green-100 dark:bg-green-900/20 border-l-8 border-green-500 p-6 rounded-r-xl">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-map text-green-500 text-3xl"></i>
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

    </div>

</x-app-layout>
