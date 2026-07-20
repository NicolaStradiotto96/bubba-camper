@props(['on'])

<div x-data="{ shown: false, timeout: null }" x-init="@this.on('{{ $on }}', () => {
    clearTimeout(timeout);
    shown = true;
    timeout = setTimeout(() => { shown = false }, 2000);
})" x-show="shown" x-transition:leave="transition ease-in duration-1000"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"
    {{ $attributes->merge(['class' => 'text-sm text-green-500 dark:text-green-500']) }}>
    {{ $slot->isEmpty() ? __('Saved.') : $slot }}
</div>
