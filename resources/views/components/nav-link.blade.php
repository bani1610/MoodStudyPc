@props(['active'])

@php
// Logika untuk class link yang sedang aktif (misal: halaman Dashboard)
$activeClasses = 'inline-flex items-center px-1 pt-1 border-b-2 border-white text-sm font-medium leading-5 text-white focus:outline-none focus:border-gray-100 transition duration-150 ease-in-out';

// Logika untuk class link yang tidak aktif
$inactiveClasses = 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-200 hover:text-white hover:border-gray-100 focus:outline-none focus:text-white focus:border-gray-100 transition duration-150 ease-in-out';

$classes = ($active ?? false) ? $activeClasses : $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
