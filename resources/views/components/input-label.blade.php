@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block font-black uppercase text-sm text-gray-700 dark:text-gray-300 text-center']) }}>
    {{ $value ?? $slot }}
</label>
