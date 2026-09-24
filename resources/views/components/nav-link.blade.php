@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-lg bg-teal-700 px-3 py-2 text-sm font-semibold leading-5 text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-300 transition duration-150 ease-in-out'
            : 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium leading-5 text-slate-600 hover:bg-slate-100 hover:text-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
