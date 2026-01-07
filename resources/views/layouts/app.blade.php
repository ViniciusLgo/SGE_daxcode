<!DOCTYPE html>
<html lang="pt-BR" class="min-h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SGE DaxCode') }}</title>

    {{-- Icones --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Dark mode: evita flash --}}
    <script>
        (function () {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') document.documentElement.classList.add('dark');
            if (theme === 'light') document.documentElement.classList.remove('dark');
        })();
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

</head>

<body class="min-h-full bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">

<div class="min-h-screen flex">

    {{-- SIDEBAR ADMIN (DAX) --}}
    @if(auth()->check() && auth()->user()->tipo === 'professor')
        <x-professor.sidebar />
    @elseif(auth()->check() && auth()->user()->tipo === 'aluno')
        <x-aluno.sidebar />
    @else
        <x-admin.sidebar />
    @endif

    {{-- AREA PRINCIPAL --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-40
                       bg-white/80 dark:bg-slate-950/70
                       backdrop-blur
                       border-b border-slate-200 dark:border-slate-800">

            <div class="px-4 sm:px-6 py-3
                        flex items-center justify-between">

                {{-- Branding --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl
                                bg-dax-yellow text-dax-dark
                                flex items-center justify-center
                                font-black">
                        DX
                    </div>

                    @php
                        $panelLabel = match(auth()->user()->tipo ?? 'admin') {
                            'professor' => 'Area do Professor',
                            'aluno' => 'Area do Aluno',
                            'responsavel' => 'Area do Responsavel',
                            default => 'Painel Administrativo',
                        };
                    @endphp
                    <div class="leading-tight hidden sm:block">
                        <div class="font-black text-dax-dark dark:text-dax-light">
                            SGE DaxCode
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $panelLabel }}
                        </div>
                    </div>
                </div>

                {{-- Acoes --}}
                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="toggleTheme()"
                            class="px-3 py-2 rounded-xl font-bold
                                   bg-slate-100 hover:bg-slate-200
                                   dark:bg-slate-800 dark:hover:bg-slate-700
                                   border border-slate-200 dark:border-slate-700">

                        <span class="inline-flex items-center gap-2">
                            <i class="bi bi-moon-stars"></i>
                            <span class="text-sm hidden sm:inline">Tema</span>
                        </span>
                    </button>
                </div>
            </div>
        </header>

        {{-- CONTEUDO --}}
        <main class="flex-1
                     lg:ml-64
                     px-4 sm:px-6 py-6">

            @yield('content')
            @yield('scripts')


        </main>

    </div>
</div>

{{-- Script Dark Mode --}}
<script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');

        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
</script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>
