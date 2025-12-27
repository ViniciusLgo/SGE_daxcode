@props([
    'title' => 'Item',
    'value' => 0,
    'icon'  => 'bi-grid',
    'variant' => 'green', // green | dark | yellow | slate
])

@php
    $variants = [
        'green' => 'bg-gradient-to-br from-dax-green to-dax-greenSoft text-white',
        'dark'  => 'bg-gradient-to-br from-dax-dark to-slate-800 text-white',
        'yellow'=> 'bg-gradient-to-br from-dax-yellow to-amber-400 text-dax-dark',
        'slate' => 'bg-gradient-to-br from-slate-700 to-slate-900 text-white',
    ];

    $bg = $variants[$variant] ?? $variants['green'];
@endphp

<div class="relative overflow-hidden rounded-2xl p-5 shadow-lg hover:-translate-y-1 transition {{ $bg }}">
    <div class="text-xs uppercase font-extrabold tracking-wide opacity-90">
        {{ $title }}
    </div>

    <div class="text-4xl font-black mt-1">
        {{ $value }}
    </div>

    <i class="bi {{ $icon }} absolute bottom-4 right-4 text-3xl opacity-80"></i>
</div>
