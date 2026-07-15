<x-app-layout title="FAQ">
    <div class="min-h-[calc(100vh-160px)] pb-20">

        {{-- TITLE --}}
        <header class="flex flex-col items-center justify-center text-center pt-12">
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
                    class="group border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 shadow-sm transition-all duration-300 focus-within:ring-2 focus-within:ring-amber-500">

                    <button @click="open = !open"
                        class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                        <span class="font-bold text-gray-900 dark:text-white pr-4">{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse
                        class="bg-white dark:bg-gray-900 rounded-b-xl border-t border-gray-100 dark:border-gray-700">

                        <div class="px-6 py-6 text-gray-600 dark:text-gray-400">
                            <p>{{ $faq['a'] }}</p>

                            @if (isset($faq['link']))
                                <div class="mt-4">
                                    <a href="{{ asset('storage/' . $faq['link']['url']) }}" target="_blank"
                                        title="{{ $faq['link']['title'] ?? 'Apri documento' }}"
                                        class="inline-flex items-center text-amber-500 font-semibold hover:underline hover:text-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 px-1 transition">
                                        <i class="fa-solid fa-file-pdf mr-2"></i>
                                        {{ $faq['link']['text'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- SEO --}}
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)
                ->map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['q'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => strip_tags($faq['a']),
                        ],
                    ];
                })
                ->toArray(),
        ];
    @endphp

    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
</x-app-layout>
