<x-app-layout>

    {{-- LOGO --}}
    <div class="flex justify-center mt-5">
        <x-application-logo size="large" class="px-5" />
    </div>

    {{-- DESCRIPTION --}}
    <section class="text-center mt-8 px-4">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">
            Prenota la tua libertà su quattro ruote
        </h1>
        <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Benvenuto su <strong>Bubba Camper</strong>.
            <br>
            Scopri i nostri camper curati nei dettagli e parti per la tua prossima avventura on-the-road.
        </p>
    </section>

    {{-- REVIEWS --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white uppercase tracking-wider mb-12">
                Cosa dicono i nostri viaggiatori
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6">
                        "Esperienza fantastica! Il camper di Stefano è tenuto benissimo ed è super accessoriato. Abbiamo
                        girato la Toscana senza un problema. Consigliatissimo!"
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            M
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Marco Rossi</p>
                            <p class="text-xs text-gray-500">Agosto 2025</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6">
                        "Stefano è una persona gentilissima e super disponibile. Ci ha spiegato tutto nei minimi
                        dettagli. Il camper è comodissimo anche per 4 persone."
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            G
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Giulia Bianchi</p>
                            <p class="text-xs text-gray-500">Luglio 2025</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6">
                        "Prenotazione facile e veloce. Bubba Camper è una garanzia. Pulizia impeccabile e kit cucina
                        completo. Lo noleggeremo sicuramente di nuovo!"
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            L
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Luca Verdi</p>
                            <p class="text-xs text-gray-500">Giugno 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- INDEX --}}
    <section class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 pb-16 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white uppercase tracking-wider mb-12">
                Scopri i nostri camper
            </h2>
            @foreach ($campers as $camper)
                <x-card :camper="$camper" />
            @endforeach
        </div>
    </section>

</x-app-layout>
