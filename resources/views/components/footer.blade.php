<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="xl:grid xl:grid-cols-3 xl:gap-8">

            <div class="space-y-4 xl:col-span-1 flex flex-col items-center text-center">
                <div class="space-y-1">
                    <div class="flex items-center justify-center space-x-2">
                        <x-application-logo size="small" />
                        <div>
                            <p class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">
                                {{ config('app.name', 'Bubba Camper') }}
                            </p>
                            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                P.IVA 02403740240
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Descrizione --}}
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed max-w-xs">
                    Rendi le tue vacanze indimenticabili con il nostro camper di famiglia.
                    Comfort, libertà e avventura a portata di click.
                </p>
            </div>

            <div class="md:grid md:grid-cols-3 gap-8 mt-7 xl:mt-0 xl:col-span-2">
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        Navigazione
                    </h3>
                    <ul class="mt-4 space-y-4 text-center">
                        <li><a href="{{ route('welcome') }}" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">Home</a>
                        </li>
                        <li><a href="{{ route('index') }}" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">Noleggio</a>
                        </li>
                        <li><a href="{{ route('prices') }}" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">Prezzi</a>
                        </li>
                    </ul>
                </div>
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        Supporto
                    </h3>
                    <ul class="mt-4 space-y-4 text-center">
                        <li><a href="{{ route('contacts') }}" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">Contatti</a>
                        </li>
                        <li><a href="{{ route('faq') }}" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">FAQ</a>
                        </li>
                        <li><a href="#" wire:navigate
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">Privacy
                                Policy</a></li>
                    </ul>
                </div>
                <div class="mt-7 md:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider text-center">
                        INFO
                    </h3>
                    <ul class="mt-4 space-y-4 text-base text-gray-500 dark:text-gray-400 text-center">
                        <li>
                            <p class="text-base text-gray-500">
                                <i class="fa-solid fa-clock text-amber-500"></i>
                                <span class="font-bold text-gray-500 dark:text-gray-400">Lun - Dom:</span> 10-13 / 16-20
                            </p>
                        </li>
                        <li>
                            <a href="https://wa.me/393347538083" target="_blank"
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <i class="fa-solid fa-phone text-amber-500"></i>
                                +39 334 753 8083</a>
                        </li>
                        <li>
                            <a href="mailto:info@bubbacamper.com" target="_blank"
                                class="text-base text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <i class="fa-solid fa-envelope text-amber-500"></i>
                                info@bubbacamper.com</a>
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
</footer>
