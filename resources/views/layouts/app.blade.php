<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGE DaxCode</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #fff;
            border-right: 1px solid #dee2e6;
        }
        .sidebar a {
            color: #333;
            display: block;
            padding: .75rem 1rem;
            text-decoration: none;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background-color: #0d6efd;
            color: #fff;
        }
        .content {
            margin-left: 220px;
            padding: 2rem;
        }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h5 class="text-center mt-3 mb-4 fw-bold text-primary">SGE DaxCode</h5>
        <a href="{{ url('/dashboard') }}" class="active">🏠 Dashboard</a>
        <a href="#">👩‍🎓 Alunos</a>
        <a href="#">👨‍🏫 Professores</a>
        <a href="#">📚 Disciplinas</a>
        <a href="#">🏫 Turmas</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Sair</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

    {{-- Conteúdo principal --}}
    <div class="content">
        <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand fw-semibold">Painel Administrativo</span>
                <span class="text-muted">{{ Auth::user()->name ?? 'Usuário' }}</span>
            </div>
        </nav>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
