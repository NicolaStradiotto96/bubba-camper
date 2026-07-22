<x-app-layout title="Home">

    <div class="relative flex flex-col  items-center min-h-[calc(100vh-80px)]">
        {{-- BACKGROUND --}}
        <div class="absolute inset-0 -top-40 z-0">
            <div class="absolute inset-0 bg-cover bg-top bg-no-repeat shadow-inner"
                style="background-image: url('{{ asset('images/bg.webp') }}')">
            </div>
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-100 dark:to-gray-900">
            </div>
        </div>
        <div class="relative z-10 flex flex-col items-center" x-data="{
            showLogo: false,
            text: '',
            fullText: 'Prenota la tua libertà su quattro ruote',
            currentIndex: 0,
            showBottomText: false,
            hasStarted: false,
        
            startAnimation() {
                if (this.hasStarted) return;
                this.hasStarted = true;
        
                setTimeout(() => {
                    this.showLogo = true;
                }, 100);
        
                let interval = setInterval(() => {
                    if (this.currentIndex < this.fullText.length) {
                        this.text += this.fullText[this.currentIndex];
                        this.currentIndex++;
                    } else {
                        clearInterval(interval);
                        this.showBottomText = true;
                    }
                }, 55);
            },
        
            init() {
                if (document.visibilityState === 'visible') {
                    this.startAnimation();
                }
        
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        this.startAnimation();
                    }
                });
            }
        }">

            <div class="flex justify-center transition-all duration-1000 transform" x-cloak
                :class="showLogo ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-90 -translate-y-5'">
                <x-application-logo size="xl" class="px-5 hover:scale-105 transition-transform duration-300" />
            </div>

            <header class="text-center px-4">
                <h1 class="text-4xl xl:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight uppercase"
                    x-text="text">
                </h1>

                <div x-cloak class="transition-all duration-1000 transform"
                    :class="showBottomText ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">

                    <h2 class="mt-4 text-xl text-gray-800 dark:text-gray-300 max-w-2xl mx-auto">
                        Benvenuto su <strong class="text-amber-500">{{ config('app.name', 'Bubba Camper') }}</strong>.
                    </h2>

                    <p class="mt-2 text-xl text-gray-800 dark:text-gray-300 max-w-2xl mx-auto">
                        Scopri i nostri camper curati nei dettagli e parti per la tua prossima avventura on-the-road.
                    </p>
                </div>
            </header>
        </div>
    </div>

    {{-- REVIEWS --}}
    <section class="pb-16 opacity-0 translate-y-10 transition-all duration-1000 transform" x-data="{
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    } else {
                        entry.target.classList.remove('opacity-100', 'translate-y-0');
                        entry.target.classList.add('opacity-0', 'translate-y-10');
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(this.$el);
        }
    }">
        <div class="max-w-7xl mx-auto px-4">
            <h2
                class="text-2xl xl:text-3xl font-extrabold text-center text-gray-900 dark:text-white uppercase tracking-widest mb-12 flex items-center justify-center gap-4">
                <span class="hidden sm:block h-px w-12 bg-amber-500"></span>
                Cosa dicono i nostri viaggiatori
                <span class="hidden sm:block h-px w-12 bg-amber-500"></span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article
                    class="group bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-sm border border-gray-300 dark:border-gray-700 relative transition duration-300 hover:-translate-y-2">
                    <div class="flex mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md inline-block transition-transform duration-500 group-hover:rotate-90"></i>
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
                    class="group bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-sm border border-gray-300 dark:border-gray-700 relative transition duration-300 hover:-translate-y-2">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md inline-block transition-transform duration-500 group-hover:rotate-90"></i>
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
                    class="group bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-sm border border-gray-300 dark:border-gray-700 relative transition duration-300 hover:-translate-y-2">
                    <div class="flex text-amber-400 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star text-amber-400 text-md inline-block transition-transform duration-500 group-hover:rotate-90"></i>
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
    <section class="opacity-0 translate-y-10 transition-all duration-1000 transform" x-data="{
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                    } else {
                        entry.target.classList.remove('opacity-100', 'translate-y-0');
                        entry.target.classList.add('opacity-0', 'translate-y-10');
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(this.$el);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                class="text-2xl xl:text-3xl font-extrabold text-center text-gray-900 dark:text-white uppercase tracking-widest mb-12 flex items-center justify-center gap-4">
                <span class="hidden sm:block h-px w-12 bg-amber-500"></span>
                Scopri i nostri camper
                <span class="hidden sm:block h-px w-12 bg-amber-500"></span>
            </h2>
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($campers->take(3) as $camper)
                    <div class="w-full md:w-[calc(50%-2rem)] lg:w-[calc(33.333%-2rem)] max-w-sm">
                        <x-card :camper="$camper" />
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <x-primary-anchor href="{{ route('index') }}" x-data="{ loading: false }"
                    @click="if(loading) { $event.preventDefault(); } else { loading = true; }"
                    x-bind:class="loading ? 'opacity-50 cursor-wait' : ''">
                    Vedi tutti i nostri camper
                </x-primary-anchor>
            </div>
        </div>
    </section>

</x-app-layout>
