<x-app-layout title="Contatti">

    {{-- TITLE --}}
    <header class="flex flex-col items-center justify-center text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-wider">
            CONTATTI</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4">
            {{ __('Scrivici per qualsiasi dubbio o consiglio sulle dotazioni: ti aiuteremo a partire in totale serenità e sicurezza.') }}
        </p>

        <div class="mt-4 flex justify-center">
            <div class="w-72 md:w-96 h-1 bg-amber-500 rounded-full"></div>
        </div>
    </header>

    <section class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <div class="space-y-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                            Parliamo del tuo
                            viaggio</h2>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                            Hai domande sul camper o vuoi verificare la disponibilità?
                            <br>
                            Siamo pronti ad aiutarti.
                        </p>
                    </div>

                    {{-- WHATASPP --}}
                    <div class="space-y-6">
                        <a href="https://wa.me/393347538083"
                            class="flex items-center p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl hover:shadow-md hover:border-green-300 hover:bg-green-200 dark:hover:border-green-500 dark:hover:bg-green-900/40 focus:outline-none focus:ring-2 focus:ring-amber-500 transition"
                            target="_blank">
                            <div class="bg-green-500 p-3 rounded-2xl text-white">
                                <i class="fa-brands fa-whatsapp text-2xl"></i>
                            </div>
                            <div class="ml-4 w-full">
                                <p class="text-sm text-green-600 dark:text-green-400 font-bold uppercase">Scrivici
                                    su WhatsApp o chiamaci</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white" itemprop="telephone">+39
                                    334 753 8083</p>
                            </div>
                        </a>

                        {{-- OPENING HOURS --}}
                        <div
                            class="flex items-start p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl">
                            <div class="bg-amber-500 p-3 rounded-2xl text-white flex-shrink-0">
                                <i class="fa-solid fa-clock text-2xl"></i>
                            </div>
                            <div class="ml-4 w-full">
                                <p class="text-sm text-amber-500 font-bold uppercase">Orari
                                    di apertura e ritiro</p>

                                <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300 font-semibold">
                                    <time class="flex justify-between pb-1">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Lun -
                                            Dom:</span>
                                        <time class="text-lg font-semibold text-gray-900 dark:text-white"
                                            datetime="10:00-13:00/16:00-20:00">
                                            10:00 - 13:00 / 16:00 - 20:00
                                        </time>
                                    </time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MAIL FORM --}}
                <livewire:forms.contact-form />

            </div>

            {{-- MAP --}}
            <div class="mt-10">
                <h2
                    class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider text-center mb-4">
                    La nostra sede</h2>
                <div
                    class="w-full h-96 rounded-[2rem] overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700">
                    <iframe title="Mappa {{ config('app.name', 'Bubba Camper') }}"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d847.6341657294789!2d11.809442363842784!3d45.754668480412846!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4778d9d5a701c64b%3A0xfafed0b8469e8ad6!2sBubba%20Camper!5e1!3m2!1sit!2sit!4v1783519360897!5m2!1sit!2sit"
                        width="100%" height="100%" allowfullscreen=""
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        class="border-0">
                    </iframe>
                </div>
            </div>

        </div>
    </section>

</x-app-layout>
