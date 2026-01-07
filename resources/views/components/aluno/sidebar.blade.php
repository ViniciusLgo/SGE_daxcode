@php
    $isDashboard = request()->routeIs('aluno.dashboard') || request()->routeIs('dashboard.aluno');
    $isTurma = request()->routeIs('aluno.turma.*');
    $isAulas = request()->routeIs('aluno.aulas.*');
    $isPresencas = request()->routeIs('aluno.presencas.*');
    $isAvaliacoes = request()->routeIs('aluno.avaliacoes.*');
    $isBoletim = request()->routeIs('aluno.boletim.*');
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64
              bg-white dark:bg-dax-dark
              border-r border-slate-200 dark:border-slate-800
              hidden lg:flex flex-col">

    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-200 dark:border-slate-800">
        <div class="w-10 h-10 rounded-xl bg-dax-yellow text-dax-dark flex items-center justify-center font-black">
            DX
        </div>
        <div>
            <div class="font-black text-dax-dark dark:text-dax-light">SGE DaxCode</div>
            <div class="text-xs text-slate-500">Area do Aluno</div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('aluno.dashboard') }}"
           class="sidebar-link {{ $isDashboard ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="{{ route('aluno.turma.show') }}"
           class="sidebar-link {{ $isTurma ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-building"></i>
            Minha Turma
        </a>

        <a href="{{ route('aluno.aulas.index') }}"
           class="sidebar-link {{ $isAulas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            Aulas
        </a>

        <a href="{{ route('aluno.presencas.index') }}"
           class="sidebar-link {{ $isPresencas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clipboard2-check"></i>
            Presencas
        </a>

        <a href="{{ route('aluno.avaliacoes.index') }}"
           class="sidebar-link {{ $isAvaliacoes ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clipboard-check"></i>
            Avaliacoes
        </a>

        <a href="{{ route('aluno.boletim.index') }}"
           class="sidebar-link {{ $isBoletim ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-journal-text"></i>
            Meu Boletim
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800">
        <a href="{{ route('profile.edit') }}"
           class="sidebar-link">
            <i class="bi bi-person-circle"></i>
            Meu Perfil
        </a>
    </div>
</aside>
