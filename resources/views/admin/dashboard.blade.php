@extends('layouts.app')

@section('content')
    <style>
        /* ==================== ESTILO DAXCODE DASHBOARD ==================== */
        :root {
            --dax-yellow: #F5C518;
            --dax-dark: #1e1e1e;
            --dax-light: #ffffff;
            --text-muted: #6c757d;
            --shadow-light: 0 6px 20px rgba(0,0,0,.06);
            --shadow-dark: 0 6px 20px rgba(255,255,255,.05);
        }

        body.dark-mode {
            --dax-light: #121212;
            --text-muted: #bbb;
        }

        .dash-title h4 { letter-spacing: .3px; }

        .card-kpi {
            border: 0;
            border-radius: 16px;
            box-shadow: var(--shadow-light);
            transition: all .2s ease;
            min-height: 140px;
            background: var(--dax-light);
        }
        body.dark-mode .card-kpi {
            box-shadow: var(--shadow-dark);
            color: #eee;
        }

        .card-kpi:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .kpi-emoji { font-size: 38px; }
        .kpi-title { font-weight: 800; font-size: .78rem; color: var(--text-muted); text-transform: uppercase; }
        .kpi-number { font-weight: 900; font-size: 2.3rem; color: #111; }
        .kpi-foot { color: var(--text-muted); font-size: .85rem; }
        body.dark-mode .kpi-number { color: var(--dax-yellow); }

        .section-block {
            border: 0;
            border-radius: 14px;
            box-shadow: var(--shadow-light);
            background: var(--dax-light);
        }
        body.dark-mode .section-block {
            background: #1e1e1e;
            box-shadow: var(--shadow-dark);
            color: #f1f1f1;
        }

        .section-header { font-weight: 800; }

        .quick-link {
            display:flex; align-items:center; gap:.6rem;
            padding:.7rem 1rem; border-radius: 12px;
            border:1px solid #e9ecef; background:#fff;
            font-weight:600; color:#212529; text-decoration:none;
            transition: all .15s ease;
        }
        .quick-link:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.06); }
        .quick-link .ico { font-size: 1.1rem; color: var(--dax-yellow); }

        body.dark-mode .quick-link {
            background: #2a2a2a;
            color: #eee;
            border-color: #333;
        }
        body.dark-mode .quick-link:hover {
            box-shadow: 0 6px 12px rgba(255,255,255,.08);
        }

        .chart-box { height: 260px; }

        .mini-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            object-fit:cover; border:2px solid #eee;
        }
        body.dark-mode .mini-avatar { border-color: #444; }
    </style>

    <div class="dash-title mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Painel Administrativo</h4>
            <p class="text-muted mb-0">Bem-vindo, {{ auth()->user()->name ?? 'admin' }} 👋</p>
        </div>
        <div>
        <span class="badge text-bg-light border">
            <i class="bi bi-hdd-network me-1"></i> v{{ $settings->versao_sistema ?? '1.0.0' }}
        </span>
        </div>
    </div>

    @php
        $totalAlunos = $alunosCount ?? \App\Models\Aluno::count();
        $totalProfessores = $professoresCount ?? \App\Models\Professor::count();
        $totalDisciplinas = $disciplinasCount ?? \App\Models\Disciplina::count();
        $totalTurmas = $turmasCount ?? \App\Models\Turma::count();
    @endphp

    {{-- ==================== KPIs ==================== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🎓</span></div>
                <div class="kpi-title mt-2">ALUNOS</div>
                <div class="kpi-number">{{ $totalAlunos }}</div>
                <div class="kpi-foot">Matrículas registradas</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🧑‍🏫</span></div>
                <div class="kpi-title mt-2">PROFESSORES</div>
                <div class="kpi-number">{{ $totalProfessores }}</div>
                <div class="kpi-foot">Ativos no sistema</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">📚</span></div>
                <div class="kpi-title mt-2">DISCIPLINAS</div>
                <div class="kpi-number">{{ $totalDisciplinas }}</div>
                <div class="kpi-foot">Em funcionamento</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🏫</span></div>
                <div class="kpi-title mt-2">TURMAS</div>
                <div class="kpi-number">{{ $totalTurmas }}</div>
                <div class="kpi-foot">Ativas no período</div>
            </div>
        </div>
    </div>

    {{-- ==================== ATALHOS ==================== --}}
    <div class="card section-block p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="section-header mb-0">Atalhos rápidos</h6>
            <span class="text-muted small">Ações administrativas</span>
        </div>
        <div class="mt-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.alunos.create') }}" class="quick-link"><i class="bi bi-person-plus ico"></i> Novo aluno</a>
            <a href="{{ route('admin.professores.create') }}" class="quick-link"><i class="bi bi-person-video3 ico"></i> Novo professor</a>
            <a href="{{ route('admin.disciplinas.create') }}" class="quick-link"><i class="bi bi-bookmark-plus ico"></i> Nova disciplina</a>
            <a href="{{ route('admin.turmas.create') }}" class="quick-link"><i class="bi bi-building-add ico"></i> Nova turma</a>
            <a href="{{ route('admin.settings.edit') }}" class="quick-link"><i class="bi bi-gear ico"></i> Configurações</a>
        </div>
    </div>

    {{-- ==================== GRÁFICO PRINCIPAL ==================== --}}
    <div class="card section-block p-3 mb-4">
        <div class="d-flex justify-content-between">
            <h6 class="section-header mb-0">📈 Evolução de Matrículas</h6>
            <span class="text-muted small">Últimos meses</span>
        </div>
        <div class="chart-box mt-2">
            <canvas id="lineMatriculas"></canvas>
        </div>
    </div>

    {{-- ==================== ALUNOS RECENTES ==================== --}}
    <div class="card section-block p-3">
        <div class="d-flex justify-content-between">
            <h6 class="section-header mb-0">🧍‍♂️ Alunos recém-matriculados</h6>
            <span class="text-muted small">Atualizado</span>
        </div>
        @if(isset($recentAlunos) && $recentAlunos->count())
            <ul class="list-group list-group-flush mt-2">
                @foreach($recentAlunos as $a)
                    <li class="list-group-item d-flex align-items-center bg-transparent">
                        <img class="mini-avatar me-2" src="{{ $a->foto_perfil ? asset('storage/'.$a->foto_perfil) : asset('images/default-avatar.png') }}">
                        <div>
                            <div class="fw-semibold">{{ $a->nome }}</div>
                            <div class="text-muted small">{{ $a->turma->nome ?? 'Sem turma' }}</div>
                        </div>
                        <span class="ms-auto badge text-bg-light border">{{ $a->created_at->format('d/m') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-2 text-muted small">Nenhum aluno novo no momento.</p>
        @endif
    </div>

    {{-- ==================== SCRIPT CHART.JS ==================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.body.classList.contains('dark-mode');

            Chart.defaults.color = isDark ? '#f5f5f5' : '#111';
            Chart.defaults.borderColor = isDark ? 'rgba(255,255,255,.1)' : 'rgba(0,0,0,.1)';
            Chart.defaults.font.family = 'Inter, sans-serif';

            const evolucaoMatriculas = @json($evolucaoMatriculas ?? []);
            const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
            const dados = Array.from({length:12}, (_,i)=> evolucaoMatriculas[i+1] ?? 0);

            const ctx = document.getElementById('lineMatriculas');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Matrículas',
                        data: dados,
                        borderColor: isDark ? '#F5C518' : '#111',
                        backgroundColor: isDark ? 'rgba(245,197,24,.25)' : 'rgba(17,17,17,.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: isDark ? '#fff' : '#000'
                    }]
                },
                options: { plugins:{ legend:{ display:false } } }
            });
        });
    </script>
@endsection
