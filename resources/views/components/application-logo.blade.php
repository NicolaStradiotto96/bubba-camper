@props([
    'size' => 'small',
])

@php
    $sizes = [
        'small' => 'h-16 w-auto',
        'medium' => 'h-32 w-auto',
        'large' => 'h-56 w-auto',
        'xl' => 'h-[50rem] max-h-[45vh] sm:max-h-[60vh] w-auto',
    ];

    $classes = $sizes[$size] ?? $sizes['small'];
@endphp

<div {{ $attributes->merge(['class' => 'relative flex justify-center ' . $classes]) }}>
    <img src="{{ Vite::asset('resources/images/logo.svg') }}"
        class="absolute h-full w-auto transition-opacity duration-500 ease-in-out opacity-100 dark:opacity-0" alt="Logo {{ config('app.name', 'Bubba Camper') }}">

    <img src="{{ Vite::asset('resources/images/logo-dark.svg') }}"
        class="h-full w-auto transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100" alt="Logo {{ config('app.name', 'Bubba Camper') }}">
</div>
