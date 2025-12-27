@props([
    'href' => '#',
    'icon' => 'bi-grid',
    'iconClass' => 'text-dax-green',
])

<a href="{{ $href }}"
    {{ $attributes->merge([
       'class' => 'flex items-center gap-4 p-4 rounded-xl
                  bg-slate-50 dark:bg-slate-900
                  border border-slate-200 dark:border-slate-800
                  hover:-translate-y-1 hover:shadow-md transition'
    ]) }}>
    <i class="bi {{ $icon }} text-2xl {{ $iconClass }}"></i>
    <span class="font-bold text-dax-dark dark:text-dax-light">
        {{ $slot }}
    </span>
</a>
