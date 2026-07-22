<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="xl:grid xl:grid-cols-3 xl:gap-8">

            <div class="space-y-4 xl:col-span-1 flex flex-col justify-center items-center text-center">
                <div class="space-y-1">
                    <div class="flex items-center justify-center space-x-2">
                        <x-application-logo size="medium" />
                        <div itemscope itemtype="https://schema.org/LocalBusiness">
                            <p class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">
                                {{ config('app.name', 'Bubba Camper') }}
                            </p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tighter">
                                di Stradiotto Stefano
                            </p>
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-widest"
                                itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                                <span itemprop="streetAddress">Via Chemin Palma 2/C</span>, <br> <span
                                    itemprop="postalCode">36065</span> <span itemprop="addressLocality">Mussolente
                                    (VI)</span>
                            </p>
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                P.IVA <span class="select-all">02403740240</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:grid md:grid-cols-3 gap-8 mt-7 xl:mt-0 xl:col-span-2">
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        Navigazione
                    </h3>
                    <ul class="mt-4 space-y-4 text-center">
                        <li><a href="{{ route('welcome') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Home</a>
                        </li>
                        <li><a href="{{ route('index') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Noleggio</a>
                        </li>
                        <li><a href="{{ route('prices') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Prezzi</a>
                        </li>
                    </ul>
                </div>
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        Supporto
                    </h3>
                    <ul class="mt-4 space-y-4 text-center">
                        <li><a href="{{ route('contacts') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Contatti</a>
                        </li>
                        <li><a href="{{ route('terms') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">Termini e Condizioni</a></li>
                        <li><a href="{{ route('faq') }}"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">FAQ</a>
                        </li>
                    </ul>
                </div>
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        INFO
                    </h3>
                    <ul class="mt-4 space-y-4 text-base text-gray-500 dark:text-gray-400 text-center">
                        <li>
                            <p class="text-base px-1 text-gray-500">
                                <i class="fa-solid fa-clock text-amber-500"></i>
                                <span class="font-bold text-gray-500 dark:text-gray-400">Lun - Dom:</span>
                                <time datetime="10:00-13:00/16:00-20:00">10-13 / 16-20</time>
                            </p>
                        </li>
                        <li>
                            <a href="https://wa.me/393347538083" target="_blank" rel="noopener noreferrer"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                <i class="fa-solid fa-phone text-amber-500"></i>
                                <span itemprop="telephone">+39 334 753 8083</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ config('app.admin_email') }}" target="_blank"
                                class="text-base px-1 text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                <i class="fa-solid fa-envelope text-amber-500"></i>
                                {{ config('app.admin_email') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div
            class="mt-6 border-t border-gray-200 dark:border-gray-800 pt-6 flex flex-col md:flex-row justify-center items-center">
            <p class="text-base text-gray-400 text-center">
                &copy; {{ date('Y') }} {{ config('app.name', 'Bubba Camper') }}. Tutti i diritti riservati.
            </p>
        </div>
    </div>

    {{-- SEO --}}
    @php
        $autoRentalSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'AutoRental',
            'name' => config('app.name', 'Bubba Camper'),
            'image' => asset('logo.svg'),
            'description' =>
                'Noleggio camper curati nei dettagli. Prenota la tua avventura on-the-road con Bubba Camper.',
            'url' => url('/'),
            'telephone' => '+393347538083',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Via Chemin Palma, 2/C',
                'addressLocality' => 'Mussolente',
                'postalCode' => '36065',
                'addressCountry' => 'IT',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 45.754548,
                'longitude' => 11.809399,
            ],
            'openingHours' => 'Mo-Su 10:00-13:00, 16:00-20:00',
        ];
    @endphp

    <script type="application/ld+json">
    {!! json_encode($autoRentalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</footer>
