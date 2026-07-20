@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full px-4 py-2 text-center text-sm leading-5 text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-800/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:text-amber-800 dark:focus:text-amber-200 focus:bg-amber-200 dark:focus:bg-amber-900 focus:border-amber-700 dark:focus:border-amber-300 transition duration-150 ease-in-out uppercase tracking-wider'
            : 'block w-full px-4 py-2 text-center text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out uppercase tracking-wider';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
