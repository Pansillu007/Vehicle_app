@props(['on'])

<div x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none;"
<<<<<<< HEAD
    {{ $attributes->merge(['class' => 'text-sm text-gray-600']) }}>
=======
    {{ $attributes->merge(['class' => 'text-sm text-gray-600 dark:text-gray-400']) }}>
>>>>>>> ec6237d (Third Week of Assignment small changes)
    {{ $slot->isEmpty() ? 'Saved.' : $slot }}
</div>
