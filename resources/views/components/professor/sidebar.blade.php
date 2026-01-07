@php
    $isDashboard = request()->routeIs('professor.dashboard') || request()->routeIs('dashboard.professor');
    $isTurmas = request()->routeIs('professor.turmas.*');
    $isAulas = request()->routeIs('professor.aulas.*');
    $isPresencas = request()->routeIs('professor.presencas.*') || request()->routeIs('professor.aulas.presenca.*');
    $isAvaliacoes = request()->routeIs('professor.gestao_academica.avaliacoes.*');
    $isBoletim = request()->routeIs('professor.boletim.*');
    $isRelatorios = request()->routeIs('professor.relatorios.*');
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64
              bg-white dark:bg-dax-dark
              border-r border-slate-200 dark:border-slate-800
              hidden lg:flex flex-col">

    {{-- LOGO --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-200 dark:border-slate-800">
        <div class="w-10 h-10 rounded-xl bg-dax-yellow text-dax-dark flex items-center justify-center font-black">
            DX
        </div>
        <div>
            <div class="font-black text-dax-dark dark:text-dax-light">SGE DaxCode</div>
            <div class="text-xs text-slate-500">Area do Professor</div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('professor.dashboard') }}"
           class="sidebar-link {{ $isDashboard ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="{{ route('professor.turmas.index') }}"
           class="sidebar-link {{ $isTurmas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-building"></i>
            Minhas Turmas
        </a>

        <a href="{{ route('professor.aulas.index') }}"
           class="sidebar-link {{ $isAulas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            Registro de Aulas
        </a>

        <a href="{{ route('professor.presencas.index') }}"
           class="sidebar-link {{ $isPresencas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clipboard2-check"></i>
            Presencas
        </a>

        <a href="{{ route('professor.gestao_academica.avaliacoes.index') }}"
           class="sidebar-link {{ $isAvaliacoes ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clipboard-check"></i>
            Avaliacoes
        </a>

        <a href="{{ route('professor.boletim.index') }}"
           class="sidebar-link {{ $isBoletim ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-journal-text"></i>
            Boletim (por turma)
        </a>

        <div class="mt-4 border-t border-slate-200 dark:border-slate-800 pt-4">
            <div class="text-xs font-bold uppercase text-slate-400 px-2 mb-2">
                Relatorios
            </div>

            <a href="{{ route('professor.relatorios.carga_horaria.index') }}"
               class="sidebar-link {{ request()->routeIs('professor.relatorios.carga_horaria.*') ? 'sidebar-link-active' : '' }}">
                <i class="bi bi-clock-history"></i>
                Carga Horaria
            </a>

            <a href="{{ route('professor.relatorios.horas.index') }}"
               class="sidebar-link {{ request()->routeIs('professor.relatorios.horas.*') ? 'sidebar-link-active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                Horas
            </a>
        </div>
    </nav>

    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800">
        <a href="{{ route('profile.edit') }}"
           class="sidebar-link">
            <i class="bi bi-person-circle"></i>
            Meu Perfil
        </a>
    </div>
</aside>
