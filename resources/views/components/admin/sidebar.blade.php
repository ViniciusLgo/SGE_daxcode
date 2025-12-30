@php
    $isDashboard   = request()->routeIs('admin.dashboard');

    $isUsuarios    = request()->routeIs('admin.usuarios.*');
    $isAlunos      = request()->routeIs('admin.alunos.*');
    $isProfessores = request()->routeIs('admin.professores.*');
    $isResponsaveis= request()->routeIs('admin.responsaveis.*');

    $isDisciplinas = request()->routeIs('admin.disciplinas.*');
    $isTurmas      = request()->routeIs('admin.turmas.*');
    $isVinculos    = request()->routeIs('admin.turmas.disciplinas*');

    $isAvaliacoes  = request()->routeIs('admin.gestao_academica.avaliacoes.*');
    $isBoletim     = request()->routeIs('admin.boletim.*');

    $isSecretaria  = request()->routeIs('admin.secretaria.*');
    $isAtendimentos= request()->routeIs('admin.secretaria.atendimentos.*');
    $isRegistros   = request()->routeIs('admin.aluno_registros.*');

    $isCargaHoraria = request()->routeIs('admin.relatorios.carga_horaria_professores.*');

    $isRelatorios = request()->routeIs('admin.relatorios.*');
    $isEvasao     = request()->routeIs('admin.relatorios.evasao.*');

    $isAulas      = request()->routeIs('admin.aulas.*');
    $isIndicadoresAulas = request()->routeIs('admin.indicadores.aulas.*');


    $isFinanceiro  = request()->routeIs('admin.financeiro.*');
    $isSettings    = request()->routeIs('admin.settings.*');
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
            <div class="text-xs text-slate-500">Painel Administrativo</div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ $isDashboard ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        {{-- USUÁRIOS --}}
        <div class="mt-6 text-xs font-bold uppercase text-slate-400 px-4">Usuários</div>

        <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link {{ $isUsuarios ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-people-fill"></i> Usuários
        </a>

        <a href="{{ route('admin.alunos.index') }}" class="sidebar-link {{ $isAlunos ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-mortarboard-fill"></i> Alunos
        </a>

        <a href="{{ route('admin.professores.index') }}" class="sidebar-link {{ $isProfessores ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-person-video3"></i> Professores
        </a>

        <a href="{{ route('admin.responsaveis.index') }}" class="sidebar-link {{ $isResponsaveis ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-person-heart"></i> Responsáveis
        </a>

        {{-- ACADÊMICO --}}
        <div class="mt-6 text-xs font-bold uppercase text-slate-400 px-4">
            Acadêmico
        </div>

        <a href="{{ route('admin.disciplinas.index') }}"
           class="sidebar-link {{ $isDisciplinas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-book"></i>
            Disciplinas
        </a>

        <a href="{{ route('admin.turmas.index') }}"
           class="sidebar-link {{ $isTurmas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-building"></i>
            Turmas
        </a>

        <a href="{{ route('admin.turmas.index') }}"
           class="sidebar-link {{ $isVinculos ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-diagram-3"></i>
            Disciplinas por Turma
        </a>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="sidebar-link {{ $isAvaliacoes ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clipboard-check"></i>
            Avaliações
        </a>

        {{-- 🔹 REGISTRO DE AULAS --}}
        <a href="{{ route('admin.aulas.index') }}"
           class="sidebar-link {{ $isAulas ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            Registro de Aulas
        </a>

        <a href="{{ route('admin.boletim.index') }}"
           class="sidebar-link {{ $isBoletim ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-journal-text"></i>
            Boletins
        </a>





        {{-- SECRETARIA --}}
        <div class="mt-6 text-xs font-bold uppercase text-slate-400 px-4">Secretaria</div>

        <a href="{{ route('admin.secretaria.dashboard') }}"
           class="sidebar-link {{ $isSecretaria ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-building-gear"></i> Dashboard
        </a>

        <a href="{{ route('admin.secretaria.atendimentos.index') }}"
           class="sidebar-link {{ $isAtendimentos ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-chat-left-text"></i> Atendimentos
        </a>

        <a href="{{ route('admin.aluno_registros.index') }}"
           class="sidebar-link {{ $isRegistros ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Registros de Alunos
        </a>

        {{-- FINANCEIRO --}}
        <div class="mt-6 text-xs font-bold uppercase text-slate-400 px-4">Financeiro</div>

        <a href="{{ route('admin.financeiro.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.financeiro.dashboard') ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-cash-coin"></i> Dashboard
        </a>

        <a href="{{ route('admin.financeiro.despesas.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.financeiro.despesas.*') ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-receipt"></i> Despesas
        </a>

        <a href="{{ route('admin.financeiro.categorias.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.financeiro.categorias.*') ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-tags"></i> Categorias
        </a>

        <a href="{{ route('admin.financeiro.centros.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.financeiro.centros.*') ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-diagram-2"></i> Centros de Custo
        </a>



        {{-- RELATÓRIOS --}}
        <div class="mt-6 text-xs font-bold uppercase text-slate-400 px-4">
            Relatórios
        </div>

        <a href="{{ route('admin.relatorios.evasao.index') }}"
           class="sidebar-link {{ $isEvasao ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-graph-up"></i>
            Evasão Escolar
        </a>

        <a href="{{ route('admin.relatorios.carga_horaria_professores.index') }}"
           class="sidebar-link {{ $isCargaHoraria ? 'sidebar-link-active' : '' }}">
            <i class="bi bi-clock-history"></i>
            Carga Horária (Professores)
        </a>

        <a href="#"
           class="sidebar-link opacity-50 cursor-not-allowed">
            <i class="bi bi-bar-chart"></i>
            Indicadores (em breve)
        </a>

        <a href="#"
           class="sidebar-link opacity-50 cursor-not-allowed">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Exportações (em breve)
        </a>


        {{-- CONFIGURAÇÕES --}}
        <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4">
            <a href="{{ route('admin.settings.edit') }}"
               class="sidebar-link {{ $isSettings ? 'sidebar-link-active' : '' }}">
                <i class="bi bi-gear-fill"></i> Configurações
            </a>
        </div>

    </nav>

    {{-- SAIR --}}
    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full sidebar-link text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                <i class="bi bi-box-arrow-right"></i> Sair
            </button>
        </form>
    </div>
</aside>
