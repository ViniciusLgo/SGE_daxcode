@props([
    'title' => 'Painel',
    'subtitle' => null,
    'version' => '1.0.0',
])

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6
            bg-white/80 dark:bg-dax-dark/70 backdrop-blur
            border border-slate-200 dark:border-slate-800
            rounded-2xl p-5 shadow-sm">

    <div>
        <h2 class="text-xl font-black text-dax-dark dark:text-dax-light">
            {{ $title }}
        </h2>

        @if($subtitle)
            <p class="text-slate-500 dark:text-slate-400">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                 bg-dax-yellow text-dax-dark font-bold text-sm shadow">
        <i class="bi bi-hdd-network"></i>
        v{{ $version }}
    </span>
</div>
