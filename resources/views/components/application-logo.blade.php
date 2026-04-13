@props([
    'size' => 'small',
])

@php
    $sizes = [
        'small' => 'h-16 w-auto',
        'medium' => 'h-48 w-auto',
        'large' => 'h-auto max-h-[45vh] w-auto',
    ];

    $classes = $sizes[$size] ?? $sizes['small'];
@endphp

<div {{ $attributes->merge(['class' => 'relative flex justify-center ' . $classes]) }}>
    <img src="{{ asset('logo.png') }}"
        class="absolute h-full w-auto transition-opacity duration-500 ease-in-out opacity-100 dark:opacity-0"
        alt="Logo Camper">

    <img src="{{ asset('logo-dark.png') }}"
        class="h-full w-auto transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100" alt="Logo Camper">
</div>
