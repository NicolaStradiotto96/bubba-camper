<x-app-layout>

    {{-- TITLE --}}
    <div class="flex justify-center mt-5">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">CONTATTI</h2>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto px-4 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase">Parliamo del tuo
                                viaggio</h2>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                                Hai domande sul camper o vuoi verificare la disponibilità?
                                <br>
                                Stefano è pronto ad aiutarti.
                            </p>
                        </div>

                        {{-- WHATASPP AND ADDRESS --}}
                        <div class="space-y-6">
                            <a href="https://wa.me/393347538083"
                                class="flex items-center p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl hover:shadow-md hover:border-green-300 hover:bg-green-200 dark:hover:border-green-500 dark:hover:bg-green-900/40 transition"
                                target="_blank">
                                <div class="bg-green-500 p-3 rounded-lg text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.81 0.986 3.848 1.503 5.71 1.503h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-green-600 dark:text-green-400 font-bold uppercase">Scrivici
                                        su WhatsApp</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">+39 334 753 8083</p>
                                </div>
                            </a>

                            <a href="https://maps.app.goo.gl/YPc96ugg7UmpMZX49"
                                class="flex items-center p-4 bg-amber-100 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl hover:shadow-md hover:border-amber-300 hover:bg-amber-200 dark:hover:border-amber-500 dark:hover:bg-amber-900/40  transition"
                                target="_blank">
                                <div class="bg-amber-500 p-3 rounded-lg text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-amber-600 dark:text-amber-400 font-bold uppercase">Dove siamo
                                    </p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Via Chemin Palma,
                                        2/C - 36065 Mussolente
                                        (VI)</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- MAIL FORM --}}
                    <div
                        class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                        <div class="mb-6 text-center">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 uppercase tracking-widest">
                                Contattaci via email
                            </h2>
                        </div>
                        <form action="#" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Name --}}
                                <div>
                                    <x-input-label for="name" :value="__('Nome')" />
                                    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                                        required />
                                </div>
                                {{-- Email --}}
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                                        required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Start date --}}
                                <div>
                                    <x-input-label for="start_date" :value="__('Data Inizio')" />
                                    <x-text-input id="start_date" name="start_date" type="date"
                                        class="block mt-1 w-full" />
                                </div>
                                {{-- End date --}}
                                <div>
                                    <x-input-label for="end_date" :value="__('Data Fine')" />
                                    <x-text-input id="end_date" name="end_date" type="date"
                                        class="block mt-1 w-full" />
                                </div>
                            </div>

                            {{-- Message --}}
                            <div>
                                <x-input-label for="message" :value="__('Il tuo messaggio')" />
                                <textarea id="message" name="message" rows="4"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amber-500 dark:focus:border-amber-600 focus:ring-amber-500 dark:focus:ring-amber-600 rounded-md shadow-sm"
                                    placeholder="Parlaci del tuo itinerario..."></textarea>
                            </div>

                            {{-- Bottone --}}
                            <div class="flex justify-center">
                                <x-primary-button
                                    class="w-full justify-center bg-amber-600 hover:bg-amber-700 active:bg-amber-800">
                                    {{ __('Invia richiesta') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- MAP --}}
                    <div class="lg:col-span-2 mt-8">
                        <div
                            class="w-full h-96 rounded-2xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2783.8269244852063!2d11.806780776833884!3d45.75461561401408!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4778d9e0cd4a0873%3A0x52cec110268c0360!2sBirreria%20El%20Tocayo!5e0!3m2!1sit!2sit!4v1776084275365!5m2!1sit!2sit"
                                width="100%" height="100%" style="border:0; clip-path: inset(2px);"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                class="dark:opacity-80 dark:contrast-125 rounded-3xl">
                            </iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
