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
        /* ==================== TEMA PRINCIPAL DAXCODE ==================== */
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

        /* ==================== SIDEBAR ==================== */
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
            scrollbar-width: thin;
            scrollbar-color: #444 transparent;
            box-shadow: 3px 0 8px rgba(0,0,0,.2);
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }

        .sidebar-header {
            text-align: center;
            padding: 1.4rem 1rem;
            background: #111;
            border-bottom: 1px solid #333;
            letter-spacing: .5px;
        }

        .sidebar-header h5 {
            font-weight: 800;
            color: var(--dax-yellow);
            margin: 0;
        }

        /* LINKS */
        .sidebar a {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .65rem 1rem;
            color: #bbb;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: .2s ease;
        }

        .sidebar a:hover {
            background: #2f2f2f;
            color: var(--dax-yellow);
            transform: translateX(3px);
        }

        .sidebar a.active {
            background: var(--dax-yellow);
            color: #000;
            font-weight: 700;
        }

        /* SUBMENUS */
        .submenu {
            max-height: 0;
            overflow: hidden;
            padding-left: 1.6rem;
            transition: all .3s ease;
        }

        .submenu.show {
            max-height: 500px;
            padding-bottom: .4rem;
        }

        .submenu a {
            font-size: 0.92rem;
            color: #aaa;
        }

        .submenu a:hover {
            color: var(--dax-yellow);
        }

        /* SEPARADOR ENTRE SEÇÕES */
        .sidebar-section {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #888;
            padding: .7rem 1rem .3rem;
            font-weight: 600;
            border-top: 1px solid #333;
            letter-spacing: .5px;
        }

        /* FOOTER */
        .sidebar-footer {
            padding: .8rem 1rem;
            background: #111;
            border-top: 1px solid #333;
        }

        .sidebar-footer button {
            width: 100%;
            text-align: left;
            color: #ccc;
        }
        .sidebar-footer button:hover {
            color: var(--dax-yellow);
        }

        /* ==================== CONTEÚDO PRINCIPAL ==================== */
        .content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            background: var(--dax-light);
        }

        /* ==================== NAVBAR SUPERIOR ==================== */
        .navbar {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: .7rem 1rem;
        }

        .navbar .btn-outline-secondary {
            border-color: #ccc;
        }

        /* ==================== DARK MODE ==================== */
        body.dark-mode {
            background: #121212;
            color: #f1f1f1;
        }

        body.dark-mode .navbar {
            background: #1f1f1f;
            border-color: #333;
        }

        body.dark-mode .sidebar {
            background: #0d0d0d;
        }

        body.dark-mode .sidebar a {
            color: #bbb;
        }

        body.dark-mode .sidebar a:hover {
            background: #1f1f1f;
            color: var(--dax-yellow);
        }

        body.dark-mode .sidebar-footer {
            background: #111;
        }

        /* ==================== FOOTER ==================== */
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

        footer span {
            color: var(--dax-yellow);
            font-weight: 600;
        }

        /* ==================== ÍCONE ROTATIVO DO MENU ==================== */
        .rotate {
            transform: rotate(180deg);
            transition: .3s;
        }
    </style>
</head>

<body>
{{-- ==================== SIDEBAR ==================== --}}
<div class="sidebar">
    <div>
        <div class="sidebar-header">
            <h5>SGE <span class="text-warning">DaxCode</span></h5>
        </div>

        {{-- ======= SEÇÃO: ACADÊMICO ======= --}}
        <div class="sidebar-section">Acadêmico</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-academico">
            <i class="bi bi-mortarboard"></i> Acadêmico
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-academico" class="submenu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.alunos.index') }}" class="{{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}">🎓 Alunos</a>
            <a href="{{ route('admin.professores.index') }}" class="{{ request()->routeIs('admin.professores.*') ? 'active' : '' }}">🧑‍🏫 Professores</a>
            <a href="{{ route('admin.disciplinas.index') }}" class="{{ request()->routeIs('admin.disciplinas.*') ? 'active' : '' }}">📘 Disciplinas</a>
            <a href="{{ route('admin.turmas.index') }}" class="{{ request()->routeIs('admin.turmas.*') ? 'active' : '' }}">🏫 Turmas</a>
            <a href="{{ route('admin.aluno_registros.index') }}" class="{{ request()->is('admin/aluno_registros*') ? 'active' : '' }}">📁 Registros</a>
            <a href="{{ route('admin.responsaveis.index') }}" class="{{ request()->routeIs('admin.responsaveis.*') ? 'active' : '' }}">👨‍👩‍👧 Responsáveis</a>
        </div>

        {{-- ======= SEÇÃO: GESTÃO ======= --}}
        <div class="sidebar-section">Gestão</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-gestao">
            <i class="bi bi-bar-chart"></i> Gestão
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-gestao" class="submenu">
            <a href="#">📋 Boletins e Avaliações</a>
            <a href="#">📈 Desempenho</a>
            <a href="#">🧮 Frequência</a>
            <a href="#">🧠 Planejamento</a>
        </div>

        {{-- ======= SEÇÃO: ADMINISTRATIVO ======= --}}
        <div class="sidebar-section">Administrativo</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-admin">
            <i class="bi bi-gear"></i> Administrativo
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-admin" class="submenu">
            <a href="#">💼 Secretaria</a>
            <a href="#">💰 Financeiro</a>
            <a href="{{ route('admin.settings.edit') }}">⚙️ Configurações</a>
        </div>

        {{-- ======= SEÇÃO: COMUNICAÇÃO ======= --}}
        <div class="sidebar-section">Comunicação</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-comunicacao">
            <i class="bi bi-chat-dots"></i> Comunicação
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-comunicacao" class="submenu">
            <a href="#">📢 Avisos</a>
            <a href="#">🗓️ Agenda</a>
            <a href="#">💬 Chat interno</a>
        </div>

        {{-- ======= SEÇÃO: RELATÓRIOS ======= --}}
        <div class="sidebar-section">Relatórios</div>
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-relatorios">
            <i class="bi bi-clipboard-data"></i> Relatórios
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-relatorios" class="submenu">
            <a href="#">📊 Desempenho</a>
            <a href="#">📄 Exportações</a>
        </div>
    </div>

    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none">
                <i class="bi bi-box-arrow-right"></i> Sair
            </button>
        </form>
    </div>
</div>

{{-- ==================== CONTEÚDO PRINCIPAL ==================== --}}
<div class="content">
    <nav class="navbar mb-4 d-flex justify-content-between align-items-center">
        <div><strong>Painel Administrativo</strong></div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'Usuário' }}
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
    // ======= Tema Claro/Escuro =======
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

    // ======= Submenus =======
    document.querySelectorAll('.toggle-menu').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.querySelector(btn.dataset.target);
            target.classList.toggle('show');
            btn.querySelector('.bi-chevron-down').classList.toggle('rotate');
        });
    });
</script>
</body>
</html>
