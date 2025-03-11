@props(['type' => 'success'])

@php
    $classes = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-400 text-blue-700'
    ][$type];
@endphp

<div {{ $attributes->merge(['class' => "{$classes} px-4 py-3 rounded relative border"]) }} role="alert">
    <span class="block sm:inline">{{ $slot }}</span>
</div>
