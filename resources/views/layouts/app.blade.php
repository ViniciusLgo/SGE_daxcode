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
            display: flex;
            flex-direction: column;
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
    @php
        $navigation = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'pattern' => 'dashboard'],
            ['label' => 'Alunos', 'route' => 'admin.alunos.index', 'pattern' => 'admin.alunos.*'],
            ['label' => 'Professores', 'route' => 'admin.professores.index', 'pattern' => 'admin.professores.*'],
            ['label' => 'Disciplinas', 'route' => 'admin.disciplinas.index', 'pattern' => 'admin.disciplinas.*'],
            ['label' => 'Turmas', 'route' => 'admin.turmas.index', 'pattern' => 'admin.turmas.*'],
        ];
    @endphp

    {{-- Sidebar --}}
    <div class="sidebar">
        <h5 class="text-center mt-3 mb-4 fw-bold text-primary">SGE DaxCode</h5>
        @foreach($navigation as $item)
            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['pattern']) ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
        <a href="#" class="mt-auto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Sair
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>

    {{-- Conteudo principal --}}
    <div class="content">
        <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand fw-semibold">Painel Administrativo</span>
                <span class="text-muted">{{ auth()->user()->name ?? 'Usuario' }}</span>
            </div>
        </nav>

        @include('partials.flash')

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
