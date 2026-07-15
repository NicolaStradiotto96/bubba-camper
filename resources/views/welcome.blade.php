<x-app-layout title="Home">

    <div class="relative flex flex-col  items-center min-h-[calc(100vh-80px)]">
        {{-- BACKGROUND --}}
        <div class="absolute inset-0 -top-40 z-0">
            <div class="absolute inset-0 bg-cover bg-top bg-no-repeat shadow-inner"
                style="background-image: url('{{ asset('images/bg.webp') }}')">
            </div>
            <div class="absolute inset-0 bg-black/40 dark:bg-black/60"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-100 dark:to-gray-900">
            </div>
        </div>
        <div class="relative z-10 flex flex-col items-center">
            {{-- LOGO --}}
            <div class="flex justify-center">
                <x-application-logo size="xl" class="px-5" />
            </div>

            {{-- DESCRIPTION --}}
            <header class="text-center px-4">
                <h1 class="text-4xl xl:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight uppercase">
                    Prenota la tua libertà su quattro ruote
                </h1>

                <h2 class="mt-4 text-xl text-gray-800 dark:text-gray-300 max-w-2xl mx-auto">Benvenuto su <strong
                        class="text-amber-500">{{ config('app.name', 'Bubba Camper') }}</strong>.</h2>

                <p class="text-xl text-gray-800 dark:text-gray-300 max-w-2xl mx-auto">
                    Scopri i nostri camper curati nei dettagli e parti per la tua prossima avventura on-the-road.
                </p>
            </header>
        </div>
    </div>

    {{-- REVIEWS --}}
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white uppercase tracking-wider mb-12">
                Cosa dicono i nostri viaggiatori
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700 relative">
                    <div class="flex mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6 min-h-[100px]">
                        "Camper perfetto per le nostre vacanze. Proprietario splendido, disponibile per ogni tua
                        esigenza. Sicuramente consigliato. Grazie Stefano!"
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            B
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Bruno</p>
                            <time datetime="2023-08" class="text-xs text-gray-500">Agosto 2023</time>
                        </div>
                    </div>
                </article>

                <article
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6 min-h-[100px]">
                        "Camper pulito e in ottimo stato. Propietario gentilissimo e disponibile. Lo consiglio
                        vivamente. Noi ripeteremo sicuramente l'esperienza."
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            A
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Alessandra</p>
                            <time datetime="2024-03" class="text-xs text-gray-500">Marzo 2024</time>
                        </div>
                    </div>
                </article>

                <article
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-700">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md"></i>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 italic mb-6 min-h-[100px]">
                        "Ottima esperienza, un rapporto cordiale e premuroso ci ha accompagnato dal primo contatto.
                        Consigliatissimo!"
                    </p>
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                            M
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Matteo</p>
                            <time datetime="2025-04" class="text-xs text-gray-500">Aprile 2025</time>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- INDEX --}}
    <section>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white uppercase tracking-wider mb-12">
                Scopri i nostri camper
            </h2>
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($campers->take(3) as $camper)
                    <div class="w-full md:w-[calc(50%-2rem)] lg:w-[calc(33.333%-2rem)] max-w-sm">
                        <x-card :camper="$camper" />
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <x-primary-anchor href="{{ route('index') }}">
                    Vedi tutti i nostri camper
                </x-primary-anchor>
            </div>
        </div>
    </section>

</x-app-layout>
