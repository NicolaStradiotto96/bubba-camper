<x-app-layout>
    <div class="min-h-[calc(100vh-160px)]">

        {{-- TITLE --}}
        <header class="flex flex-col items-center justify-center text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-wider">
                DOMANDE FREQUENTI
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto leading-relaxed px-4">
                {{ __('Risposte rapide a ogni tua curiosità. Tutto ciò che devi sapere per pianificare la tua prossima avventura senza dubbi.') }}
            </p>

            <div class="mt-4 flex justify-center">
                <div class="w-72 md:w-96 h-1 bg-amber-500 rounded-full"></div>
            </div>
        </header>

        <div class="max-w-3xl mx-auto px-4 space-y-4 pt-16">
            @foreach ($faqs as $faq)
                <div x-data="{ open: false }"
                    class="border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800">

                    <button @click="open = !open"
                        class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 dark:text-white">{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition-all ease-in duration-200"
                        x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                        class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                        <p>{!! $faq['a'] !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
