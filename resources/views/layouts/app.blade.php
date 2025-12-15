<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGE DaxCode</title>

    {{-- Bootstrap e Ícones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --dax-yellow: #FFD54F;
            --dax-dark: #1E1E1E;
            --dax-gray: #2B2B2B;
            --dax-light: #F8F9FA;
            --sidebar-width: 250px;
        }

        body {
            font-family: "Inter", sans-serif;
            background: var(--dax-light);
            color: #222;
            transition: 0.3s;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--dax-dark);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            box-shadow: 3px 0 8px rgba(0,0,0,.2);
        }

        .sidebar-header {
            text-align: center;
            padding: 1.4rem 1rem;
            background: #111;
            border-bottom: 1px solid #333;
        }

        .sidebar-header h5 {
            font-weight: 800;
            color: var(--dax-yellow);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            gap: .9rem;
            padding: .65rem 1rem;
            color: #ccc;
            border-radius: 6px;
            text-decoration: none;
            transition: .2s;
        }

        .sidebar a:hover {
            background: #2f2f2f;
            color: var(--dax-yellow);
        }

        .sidebar a.active {
            background: var(--dax-yellow);
            color: #000;
            font-weight: 700;
        }

        .sidebar-section {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #888;
            padding: .7rem 1rem .3rem;
            font-weight: 600;
            border-top: 1px solid #333;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            padding-left: 1.2rem;
            transition: .3s;
        }

        .submenu.show {
            max-height: 600px;
            padding-bottom: .4rem;
        }

        .submenu a {
            font-size: 0.90rem;
        }

        .rotate {
            transform: rotate(180deg);
        }

        /* CONTENT */
        .content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: .7rem 1rem;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: .8rem;
            font-size: .85rem;
            background: var(--dax-dark);
            color: #ccc;
            position: fixed;
            left: var(--sidebar-width);
            bottom: 0;
            width: calc(100% - var(--sidebar-width));
        }

    </style>
</head>

<body>

{{-- ========== SIDEBAR ========== --}}
<div class="sidebar">
    <div>
        <div class="sidebar-header">
            <h5>SGE <span class="text-warning">DaxCode</span></h5>
        </div>

        {{-- ========================================= --}}
        {{-- ACADÊMICO --}}
        {{-- ========================================= --}}
        <div class="sidebar-section">Acadêmico</div>

        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-academico">
            🎓 Acadêmico
            <i class="bi bi-chevron-down ms-auto small
                {{ request()->routeIs('admin.alunos.*') ||
                   request()->routeIs('admin.professores.*') ||
                   request()->routeIs('admin.disciplinas.*') ||
                   request()->routeIs('admin.turmas.*') ||
                   request()->routeIs('admin.aluno_registros.*') ||
                   request()->routeIs('admin.responsaveis.*')
                ? 'rotate' : '' }}">
            </i>
        </a>

        <div id="menu-academico" class="submenu
            {{ request()->routeIs('admin.alunos.*') ||
               request()->routeIs('admin.professores.*') ||
               request()->routeIs('admin.disciplinas.*') ||
               request()->routeIs('admin.turmas.*') ||
               request()->routeIs('admin.aluno_registros.*') ||
               request()->routeIs('admin.responsaveis.*')
               ? 'show' : '' }}">

            <a href="{{ route('admin.alunos.index') }}" class="{{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}">🧑‍🎓 Alunos</a>
            <a href="{{ route('admin.professores.index') }}" class="{{ request()->routeIs('admin.professores.*') ? 'active' : '' }}">🧑‍🏫 Professores</a>
            <a href="{{ route('admin.disciplinas.index') }}" class="{{ request()->routeIs('admin.disciplinas.*') ? 'active' : '' }}">📘 Disciplinas</a>
            <a href="{{ route('admin.turmas.index') }}" class="{{ request()->routeIs('admin.turmas.*') ? 'active' : '' }}">🏫 Turmas</a>
            <a href="{{ route('admin.aluno_registros.index') }}" class="{{ request()->routeIs('admin.aluno_registros.*') ? 'active' : '' }}">📁 Registros</a>
            <a href="{{ route('admin.responsaveis.index') }}" class="{{ request()->routeIs('admin.responsaveis.*') ? 'active' : '' }}">👨‍👩‍👧 Responsáveis</a>
        </div>

        {{-- ========================================= --}}
        {{-- GESTÃO --}}
        {{-- ========================================= --}}
        <div class="sidebar-section">Gestão</div>

        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-gestao">
            📊 Gestão
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>

        <div id="menu-gestao" class="submenu">
            <a href="#">📋 Boletins</a>
            <a href="#">📈 Desempenho</a>
            <a href="#">🧮 Frequência</a>
        </div>

        {{-- ========================================= --}}
        {{-- ADMINISTRATIVO --}}
        {{-- ========================================= --}}
        <div class="sidebar-section">Administrativo</div>

        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-admin">
            ⚙️ Administrativo
            <i class="bi bi-chevron-down ms-auto small
                {{ request()->routeIs('admin.financeiro.*') || request()->routeIs('admin.settings.*') ? 'rotate' : '' }}">
            </i>
        </a>

        <div id="menu-admin" class="submenu
            {{ request()->routeIs('admin.financeiro.*') || request()->routeIs('admin.settings.*') ? 'show' : '' }}">

            {{-- ========================= --}}
            {{-- MENU - SECRETARIA ESCOLAR --}}
            {{-- ========================= --}}
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center
                    {{ request()->is('admin/secretaria*') ? 'active fw-bold' : '' }}"
                    href="{{ route('admin.secretaria.dashboard') }}">
                    <span class="me-2">📁</span>
                    <span>Secretaria</span>
                </a>
            </li>



            {{-- FINANCEIRO --}}
            <a href="javascript:void(0)" class="toggle-menu" data-target="#submenu-financeiro">
                💰 Financeiro
                <i class="bi bi-chevron-down ms-auto small
                    {{ request()->routeIs('admin.financeiro.*') ? 'rotate' : '' }}">
                </i>
            </a>

            <div id="submenu-financeiro" class="submenu
                {{ request()->routeIs('admin.financeiro.*') ? 'show' : '' }}">

                <a href="{{ route('admin.financeiro.dashboard') }}" class="{{ request()->routeIs('admin.financeiro.dashboard') ? 'active' : '' }}">📊 Dashboard</a>

                <a href="{{ route('admin.financeiro.despesas.index') }}" class="{{ request()->routeIs('admin.financeiro.despesas.*') ? 'active' : '' }}">📋 Despesas</a>

                <a href="{{ route('admin.financeiro.categorias.index') }}" class="{{ request()->routeIs('admin.financeiro.categorias.*') ? 'active' : '' }}">🧷 Categorias</a>

                <a href="{{ route('admin.financeiro.centros.index') }}" class="{{ request()->routeIs('admin.financeiro.centros.*') ? 'active' : '' }}">🧩 Centros de Custo</a>

            </div>

            {{-- CONFIG --}}
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">⚙️ Configurações</a>

        </div>

        {{-- ========================================= --}}
        {{-- COMUNICAÇÃO --}}
        {{-- ========================================= --}}
        <div class="sidebar-section">Comunicação</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-comunicacao">
            💬 Comunicação
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-comunicacao" class="submenu">
            <a href="#">📢 Avisos</a>
            <a href="#">🗓️ Agenda</a>
        </div>

        {{-- ========================================= --}}
        {{-- RELATÓRIOS --}}
        {{-- ========================================= --}}
        <div class="sidebar-section">Relatórios</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-relatorios">
            📄 Relatórios
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-relatorios" class="submenu">
            <a href="#">📊 Desempenho</a>
            <a href="#">📎 Exportações</a>
        </div>

    </div> {{-- /top --}}

    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none text-light">
                <i class="bi bi-box-arrow-right"></i> Sair
            </button>
        </form>
    </div>

</div> {{-- /SIDEBAR --}}

{{-- ========== CONTENT ========== --}}
<div class="content">
    <nav class="navbar mb-4 d-flex justify-content-between">
        <strong>Painel Administrativo</strong>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
            </span>
            <button id="themeToggle" class="btn btn-sm btn-outline-secondary"><i class="bi bi-moon"></i></button>
        </div>
    </nav>

    @include('partials.flash')
    @yield('content')
</div>

<footer>
    Projeto <span>DAXCode</span> — v1.0.0 | Desenvolvido por DAX OIL &amp; CAEC
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

    // DARK MODE
    const themeToggle = document.getElementById('themeToggle');
    const icon = themeToggle.querySelector('i');

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        icon.classList.replace('bi-moon', 'bi-brightness-high');
    }

    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        icon.classList.toggle('bi-moon', !isDark);
        icon.classList.toggle('bi-brightness-high', isDark);
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // SUBMENUS (click)
    document.querySelectorAll('.toggle-menu').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.querySelector(btn.dataset.target);
            target.classList.toggle('show');
            btn.querySelector('.bi-chevron-down').classList.toggle('rotate');
        });
    });

</script>
@yield('scripts')
</body>
</html>
