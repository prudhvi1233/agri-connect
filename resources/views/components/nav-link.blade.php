@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 rounded-lg bg-emerald-500/20 border-b-2 border-emerald-400 text-sm font-medium leading-5 text-white focus:outline-none transition-all duration-300'
            : 'inline-flex items-center px-3 py-2 rounded-lg border-b-2 border-transparent text-sm font-medium leading-5 text-gray-300 hover:text-white hover:bg-white/5 focus:outline-none focus:text-white focus:bg-white/5 transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
