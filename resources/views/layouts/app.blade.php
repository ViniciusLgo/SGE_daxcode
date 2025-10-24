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
            --dax-gray: #2C2C2C;
            --dax-light: #F8F9FA;
            --dax-border: #E0E0E0;
            --sidebar-width: 240px;
        }

        body {
            font-family: "Inter", sans-serif;
            background-color: var(--dax-light);
            color: #222;
            transition: all 0.3s ease;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--dax-dark);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #555 transparent;
            z-index: 100;
            transition: 0.3s;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }

        .sidebar-header {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid #333;
            background: var(--dax-gray);
        }

        .sidebar h5 {
            font-weight: 800;
            color: var(--dax-yellow);
            margin: 0;
        }

        .sidebar-section-title {
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            color: #bbb;
            margin: 1rem 1rem 0.4rem;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 1rem;
            color: #ccc;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar a:hover {
            background-color: #333;
            color: var(--dax-yellow);
            transform: translateX(3px);
        }

        .sidebar a.active {
            background-color: var(--dax-yellow);
            color: #000;
            font-weight: 600;
        }

        .sidebar a.active i {
            color: #000;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            padding-left: 1.8rem;
        }

        .submenu.show {
            max-height: 400px;
            padding-bottom: 0.5rem;
        }

        .submenu a {
            font-size: 0.9rem;
            padding: 0.4rem 0.6rem;
            color: #aaa;
        }

        .submenu a:hover {
            color: var(--dax-yellow);
        }

        .sidebar-footer {
            border-top: 1px solid #333;
            padding: 0.8rem 1rem;
            background: var(--dax-gray);
        }

        .sidebar-footer a {
            color: #f66;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* ===== CONTENT ===== */
        .content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: #fff;
            border-radius: 10px;
            border: 1px solid var(--dax-border);
        }

        /* ===== DASHBOARD CARDS ===== */
        .card {
            border-radius: 12px;
            border: 1px solid var(--dax-border);
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: var(--dax-yellow);
            border-color: var(--dax-yellow);
            color: #000;
        }

        .btn-primary:hover {
            background-color: #ffcc33;
            border-color: #ffcc33;
            color: #000;
        }

        /* ===== DARK MODE ===== */
        .dark-mode {
            background-color: var(--dax-dark);
            color: #eee !important;
        }

        .dark-mode .sidebar {
            background-color: #000 !important;
        }

        .dark-mode .navbar {
            background-color: #111 !important;
            color: #eee;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            background: var(--dax-dark);
            color: #fff;
            text-align: center;
            padding: 0.6rem;
            font-size: 0.8rem;
        }

        footer span {
            color: var(--dax-yellow);
            font-weight: 600;
        }
    </style>


</head>

<body>
<div class="sidebar">
    <div>
        <div class="sidebar-header">
            <h5>SGE <span class="text-warning">DaxCode</span></h5>
        </div>

        {{-- ======= Seção Acadêmica ======= --}}
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-academico">
            <i class="bi bi-mortarboard"></i> Acadêmico
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-academico" class="submenu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.alunos.index') }}" class="{{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}">👩‍🎓 Alunos</a>
            <a href="{{ route('admin.professores.index') }}" class="{{ request()->routeIs('admin.professores.*') ? 'active' : '' }}">👨‍🏫 Professores</a>
            <a href="{{ route('admin.disciplinas.index') }}" class="{{ request()->routeIs('admin.disciplinas.*') ? 'active' : '' }}">📘 Disciplinas</a>
            <a href="{{ route('admin.turmas.index') }}" class="{{ request()->routeIs('admin.turmas.*') ? 'active' : '' }}">🏫 Turmas</a>

            <li class="nav-item">
                <a href="{{ route('admin.aluno_registros.index') }}"
                   class="nav-link {{ request()->is('admin/aluno_registros*') ? 'active bg-light text-primary' : 'text-white' }}">
                    <i class="bi bi-folder2-open me-2"></i> 📄 Registros
                </a>
            </li>

        </div>



        {{-- ======= Gestão Pedagógica ======= --}}
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

        {{-- ======= Administrativo ======= --}}
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-admin">
            <i class="bi bi-gear"></i> Administrativo
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-admin" class="submenu">
            <a href="#">💼 Secretaria</a>
            <a href="#">💰 Financeiro</a>
            <a href="{{ route('admin.settings.edit') }}">⚙️ Configurações</a>
        </div>

        {{-- ======= Comunicação ======= --}}
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-comunicacao">
            <i class="bi bi-chat-dots"></i> Comunicação
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-comunicacao" class="submenu">
            <a href="#">📢 Avisos</a>
            <a href="#">🗓️ Agenda</a>
            <a href="#">💬 Chat interno</a>
        </div>

        {{-- ======= Relatórios ======= --}}
        <a href="javascript:void(0)" class="toggle-menu" data-target="#menu-relatorios">
            <i class="bi bi-clipboard-data"></i> Relatórios
            <i class="bi bi-chevron-down ms-auto small"></i>
        </a>
        <div id="menu-relatorios" class="submenu">
            <a href="#">📊 Desempenho</a>
            <a href="#">📄 Exportações</a>
        </div>
    </div>

    {{-- ======= Rodapé da Sidebar ======= --}}
    <div class="sidebar-footer">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> Sair
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</div>

{{-- ======= Conteúdo ======= --}}
<div class="content">
    <nav class="navbar shadow-sm mb-4 rounded d-flex justify-content-between align-items-center px-3">
        <div><span class="navbar-brand mb-0">Painel Administrativo</span></div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name ?? 'Usuário' }}</span>
            <button class="btn btn-sm btn-outline-secondary" id="themeToggle" title="Alternar tema">
                <i class="bi bi-moon"></i>
            </button>
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
    // Tema claro/escuro
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

    // Submenus expansíveis
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
