<x-app-layout>

    {{-- TITLE --}}
    <header class="flex justify-center mt-5">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">I NOSTRI CAMPER</h1>
    </header>

    {{-- INDEX --}}
    <section class="grid grid-cols-1 gap-6">
        <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
            @foreach ($campers as $camper)
                <x-card :camper="$camper" />
            @endforeach
        </div>
    </section>

</x-app-layout>
