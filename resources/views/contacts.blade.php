<x-app-layout>

    {{-- TITLE --}}
    <header class="flex flex-col items-center justify-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-wider">CONTATTI</h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4 text-center">
            {{ __('Scrivici per qualsiasi dubbio o consiglio sulle dotazioni: ti aiuteremo a partire in totale serenità e sicurezza.') }}
        </p>

        <div class="mt-4 flex justify-center">
            <div class="w-72 md:w-96 h-1 bg-amber-500 rounded-full"></div>
        </div>
    </header>

    <section class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto px-4 pt-16">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">Parliamo del tuo
                                viaggio</h2>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                                Hai domande sul camper o vuoi verificare la disponibilità?
                                <br>
                                Siamo pronti ad aiutarti.
                            </p>
                        </div>

                        {{-- WHATASPP AND ADDRESS --}}
                        <div class="space-y-6">
                            <a href="https://wa.me/393347538083"
                                class="flex items-center p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl hover:shadow-md hover:border-green-300 hover:bg-green-200 dark:hover:border-green-500 dark:hover:bg-green-900/40 transition"
                                target="_blank">
                                <div class="bg-green-500 p-3 rounded-lg text-white">
                                    <i class="fa-brands fa-whatsapp text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-green-600 dark:text-green-400 font-bold uppercase">Scrivici
                                        su WhatsApp o chiamaci</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">+39 334 753 8083</p>
                                </div>
                            </a>

                            <a href="https://maps.app.goo.gl/YPc96ugg7UmpMZX49"
                                class="flex items-center p-4 bg-amber-100 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl hover:shadow-md hover:border-amber-300 hover:bg-amber-200 dark:hover:border-amber-500 dark:hover:bg-amber-900/40  transition"
                                target="_blank">
                                <div class="bg-amber-500 p-3 rounded-lg text-white">
                                    <i class="fa-solid fa-location-dot text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-amber-500 font-bold uppercase">Dove siamo
                                    </p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Via Chemin Palma,
                                        2/C - 36065 Mussolente
                                        (VI)</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- MAIL FORM --}}
                    <livewire:forms.contact-form />

                </div>

                {{-- MAP --}}
                <div class="lg:col-span-2 mt-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider text-center mb-4">Mappa e indicazioni</h2>
                    <div
                        class="w-full h-96 rounded-2xl overflow-hidden shadow-lg border border-gray-300 dark:border-gray-700">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2783.8269244852063!2d11.806780776833884!3d45.75461561401408!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4778d9e0cd4a0873%3A0x52cec110268c0360!2sBirreria%20El%20Tocayo!5e0!3m2!1sit!2sit!4v1776084275365!5m2!1sit!2sit"
                            width="100%" height="100%" style="border:0; clip-path: inset(2px);" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            class="dark:opacity-80 dark:contrast-125 rounded-3xl">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>
