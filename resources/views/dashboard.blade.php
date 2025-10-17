@extends('layouts.app')

@section('content')
    <style>
        /* —————— ESTILO GERAL DO DASHBOARD —————— */
        .dash-title h4 { letter-spacing: .3px; }
        .card-kpi {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,.06);
            transition: transform .15s ease, box-shadow .15s ease;
            min-height: 160px;
        }
        .card-kpi:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,.08); }
        .kpi-emoji { font-size: 42px; line-height: 1; }
        .kpi-title {
            font-weight: 800; font-size: .78rem; letter-spacing: .8px;
            color: #0d6efd; text-transform: uppercase;
        }
        .kpi-number { font-weight: 900; font-size: 2.25rem; }
        .kpi-foot { color: #6c757d; font-size: .85rem; }

        /* Seções mock/placeholder pintadas de azul forte ~ “a ligar futuramente” */
        .mock {
            background: #0d6efd !important; /* azul forte bootstrap primary */
            color: #fff !important;
            border: none;
        }
        .mock .card-title,
        .mock .muted { color: rgba(255,255,255,.9) !important; }
        .mock .badge { background: rgba(255,255,255,.15); }
        .mock a, .mock .link { color:#fff; text-decoration: underline; }
        .mock .placeholder-note{
            font-size:.85rem; letter-spacing:.3px; opacity:.9;
            display:inline-flex; align-items:center; gap:.4rem;
        }

        .mini-avatar { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,.08); }

        .section-block { border: 0; border-radius: 14px; box-shadow: 0 6px 20px rgba(0,0,0,.06); }
        .section-header { font-weight: 800; }

        /* Chips/atalhos */
        .quick-link {
            display:flex; align-items:center; gap:.6rem;
            padding:.7rem 1rem; border-radius: 12px; border:1px solid #e9ecef;
            font-weight:600; color:#212529; text-decoration:none;
            transition: all .15s ease;
            background:#fff;
        }
        .quick-link:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.06); }
        .quick-link .ico { font-size: 1.1rem; color:#0d6efd; }

        .mini-metric { display:flex; align-items:center; gap:.8rem; }
        .mini-metric .dot { width:10px; height:10px; border-radius:50%; }
        .dot-green{ background:#22c55e; } .dot-yellow{ background:#f59e0b; } .dot-red{ background:#ef4444; } .dot-blue{ background:#0d6efd; }

        /* Chart containers */
        .chart-card { min-height: 320px; }
        .chart-box { height: 240px; }
        .chart-badge { font-size:.75rem; }
    </style>

    <div class="dash-title mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Painel Administrativo</h4>
            <p class="text-muted mb-0">Bem-vindo, {{ auth()->user()->name ?? 'admin' }} 👋 — panorama geral do SGE.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
        <span class="badge text-bg-light border">
            <i class="bi bi-hdd-network me-1"></i> v{{ $settings->versao_sistema ?? '1.0.0' }}
        </span>
            <span class="badge text-bg-light border">
            <i class="bi bi-clock me-1"></i> Atualizado agora
        </span>
        </div>
    </div>

    {{-- ===================== KPIs REAIS (DADOS EXISTENTES) ===================== --}}
    @php
        // Estes counts devem vir do DashboardController. Usamos fallback para evitar erro.
        $totalAlunos = $alunosCount ?? \App\Models\Aluno::count();
        $totalProfessores = $professoresCount ?? \App\Models\Professor::count();
        $totalDisciplinas = $disciplinasCount ?? \App\Models\Disciplina::count();
        $totalTurmas = $turmasCount ?? \App\Models\Turma::count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-emoji">🎓</span>
                    <span class="badge text-bg-light chart-badge"><i class="bi bi-people"></i> cadastrados</span>
                </div>
                <div class="kpi-title mt-2">ALUNOS</div>
                <div class="kpi-number">{{ $totalAlunos }}</div>
                <div class="kpi-foot">Últimas matrículas no menu de Alunos</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-emoji">🧑‍🏫</span>
                    <span class="badge text-bg-light chart-badge"><i class="bi bi-person-workspace"></i> ativos</span>
                </div>
                <div class="kpi-title mt-2">PROFESSORES</div>
                <div class="kpi-number">{{ $totalProfessores }}</div>
                <div class="kpi-foot">Vincule professores às disciplinas</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-emoji">📚</span>
                    <span class="badge text-bg-light chart-badge"><i class="bi bi-journal-text"></i> registradas</span>
                </div>
                <div class="kpi-title mt-2">DISCIPLINAS</div>
                <div class="kpi-number">{{ $totalDisciplinas }}</div>
                <div class="kpi-foot">Gestão completa no menu Disciplinas</div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card card-kpi p-3">
                <div class="d-flex justify-content-between">
                    <span class="kpi-emoji">🏫</span>
                    <span class="badge text-bg-light chart-badge"><i class="bi bi-building"></i> em andamento</span>
                </div>
                <div class="kpi-title mt-2">TURMAS</div>
                <div class="kpi-number">{{ $totalTurmas }}</div>
                <div class="kpi-foot">Crie turmas e vincule alunos</div>
            </div>
        </div>
    </div>

    {{-- ===================== LINKS RÁPIDOS (REAIS) ===================== --}}
    <div class="card section-block p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="section-header mb-0">Atalhos rápidos</h6>
            <span class="text-muted small">Acesso imediato às ações mais comuns</span>
        </div>
        <div class="mt-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.alunos.create') }}" class="quick-link">
                <i class="bi bi-person-plus ico"></i> Cadastrar aluno
            </a>
            <a href="{{ route('admin.professores.create') }}" class="quick-link">
                <i class="bi bi-person-video3 ico"></i> Cadastrar professor
            </a>
            <a href="{{ route('admin.disciplinas.create') }}" class="quick-link">
                <i class="bi bi-bookmark-plus ico"></i> Nova disciplina
            </a>
            <a href="{{ route('admin.turmas.create') }}" class="quick-link">
                <i class="bi bi-building-add ico"></i> Criar turma
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="quick-link">
                <i class="bi bi-gear-wide-connected ico"></i> Configurações do sistema
            </a>
        </div>
    </div>

    {{-- ===================== INDICADORES & GRÁFICOS (MOCK = AZUL FORTE) ===================== --}}
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card section-block chart-card mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🍕 Alunos por Turma</h6>
                    <span class="placeholder-note"><i class="bi bi-bezier2"></i> placeholder</span>
                </div>
                <div class="chart-box">
                    <canvas id="pieAlunosTurma"></canvas>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <div class="mini-metric"><span class="dot dot-blue"></span><span>Turma A</span></div>
                    <div class="mini-metric"><span class="dot dot-yellow"></span><span>Turma B</span></div>
                    <div class="mini-metric"><span class="dot dot-green"></span><span>Turma C</span></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card section-block chart-card mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">📊 Professores por Disciplina</h6>
                    <span class="placeholder-note"><i class="bi bi-bezier2"></i> placeholder</span>
                </div>
                <div class="chart-box">
                    <canvas id="barProfPorDisc"></canvas>
                </div>
                <span class="muted small">Distribuição estimada (mock)</span>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card section-block chart-card mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">📈 Evolução de Matrículas (ano)</h6>
                    <span class="placeholder-note"><i class="bi bi-bezier2"></i> placeholder</span>
                </div>
                <div class="chart-box">
                    <canvas id="lineMatriculas"></canvas>
                </div>
                <span class="muted small">Série temporal ilustrativa</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card section-block chart-card mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🎯 Ocupação das Turmas (Gauge)</h6>
                    <span class="placeholder-note"><i class="bi bi-bezier2"></i> placeholder</span>
                </div>
                <div class="chart-box">
                    <canvas id="gaugeOcupacao"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <div class="mini-metric"><span class="dot dot-green"></span><span>Ótimo</span></div>
                    <div class="mini-metric"><span class="dot dot-yellow"></span><span>Atenção</span></div>
                    <div class="mini-metric"><span class="dot dot-red"></span><span>Crítico</span></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card section-block chart-card mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🔥 Frequência Mensal (Heatmap simples)</h6>
                    <span class="placeholder-note"><i class="bi bi-bezier2"></i> placeholder</span>
                </div>
                <div class="chart-box d-flex align-items-center justify-content-center">
                    {{-- Simulação de “heatmap” com blocos --}}
                    <div class="d-grid" style="grid-template-columns: repeat(10, 1fr); gap:6px; width:100%;">
                        @for($i=0;$i<50;$i++)
                            @php
                                $alpha = [0.25, .35, .45, .6, .75][rand(0,4)];
                            @endphp
                            <div style="height:16px; background: rgba(255,255,255,{{ $alpha }}); border-radius:4px;"></div>
                        @endfor
                    </div>
                </div>
                <span class="muted small">Representação ilustrativa (cores variando por presença)</span>
            </div>
        </div>
    </div>

    {{-- ===================== ATIVIDADES / NOTÍCIAS ===================== --}}
    <div class="row g-3 mt-1">

        {{-- Agenda / Próximos Eventos (mock) --}}
        <div class="col-xl-4">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">📅 Agenda / Próximos eventos</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>

                <ul class="list-group list-group-flush mt-2">
                    <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center">
                        Conselho de classe <span class="badge">25/10</span>
                    </li>
                    <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center">
                        Entrega de boletins <span class="badge">30/10</span>
                    </li>
                    <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center">
                        Semana da ciência <span class="badge">05/11</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Avisos rápidos (mock) --}}
        <div class="col-xl-4">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">📌 Avisos rápidos</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>
                <ul class="mt-2 mb-0">
                    <li>Professor João adicionou notas em <strong>Matemática</strong>.</li>
                    <li>3 documentos aguardam validação.</li>
                    <li>2 turmas sem horário definido.</li>
                </ul>
            </div>
        </div>

        {{-- Alunos recém-matriculados (se houver reais) --}}
        <div class="col-xl-4">
            <div class="card section-block p-3 @if(empty($recentAlunos) || $recentAlunos->count()===0) mock @endif">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🧍‍♂️ Alunos recém-matriculados</h6>
                    <span class="placeholder-note @if(!isset($recentAlunos) || $recentAlunos->count()==0) d-inline-flex @else d-none @endif">
                    <i class="bi bi-lightning-charge"></i> conectar fonte
                </span>
                </div>

                @if(isset($recentAlunos) && $recentAlunos->count())
                    <ul class="list-group list-group-flush mt-2">
                        @foreach($recentAlunos as $a)
                            <li class="list-group-item d-flex align-items-center">
                                <img class="mini-avatar me-2" src="{{ $a->foto_perfil ? asset('storage/'.$a->foto_perfil) : asset('images/default-avatar.png') }}" alt="avatar">
                                <div>
                                    <div class="fw-semibold">{{ $a->nome }}</div>
                                    <div class="text-muted small">{{ $a->turma->nome ?? 'Sem turma' }}</div>
                                </div>
                                <span class="ms-auto badge text-bg-light border">{{ $a->created_at->format('d/m') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="mt-2">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img class="mini-avatar" src="https://i.pravatar.cc/100?img=12"><div>Maria Alves — 3ºA</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img class="mini-avatar" src="https://i.pravatar.cc/100?img=31"><div>Pedro Lima — 2ºB</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <img class="mini-avatar" src="https://i.pravatar.cc/100?img=5"><div>Carla Souza — 1ºC</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===================== DOCUMENTOS / TAREFAS / INSIGHTS ===================== --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">📄 Documentos pendentes</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>
                <ul class="mt-2 mb-0">
                    <li>RG — <strong>João</strong> (aguardando validação)</li>
                    <li>Histórico — <strong>Carla</strong> (aguardando)</li>
                    <li>Comprovante — <strong>Pedro</strong> (aguardando)</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🧠 Insights automáticos</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>
                <ul class="mt-2 mb-0">
                    <li>Turma <strong>3ºA</strong> tem <strong>2 alunos</strong> sem documentos completos.</li>
                    <li>Disciplina <strong>Matemática</strong> sem professor vinculado em <strong>1 turma</strong>.</li>
                    <li>Taxa de ocupação média estimada: <strong>92%</strong>.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===================== NOTIFICAÇÕES / CALENDÁRIO (MOCK) ===================== --}}
    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🔔 Notificações (tempo real)</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>
                <div class="mt-2 d-flex flex-column gap-2">
                    <div><i class="bi bi-dot"></i> Novo upload de documento — <strong>RG</strong> (Ana)</div>
                    <div><i class="bi bi-dot"></i> Professor <strong>Marcos</strong> entrou às 14:02</div>
                    <div><i class="bi bi-dot"></i> Turma <strong>2ºB</strong> atualizou horário</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card section-block mock p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="card-title mb-0">🗓️ Calendário semanal</h6>
                    <span class="placeholder-note"><i class="bi bi-lightning-charge"></i> em breve</span>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between text-white">
                        <span class="badge">Seg</span><span class="badge">Ter</span><span class="badge">Qua</span>
                        <span class="badge">Qui</span><span class="badge">Sex</span>
                    </div>
                    <div class="mt-2 small">• Reunião coordenação (Qua, 10h) — • Aula extra (Sex, 15h)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SCRIPTS (Chart.js para os placeholders) ===================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            // Evita erro caso canvas não exista (Blade caches etc)
            const $ = (sel) => document.querySelector(sel);

            // Pizza — Alunos por turma (mock)
            if ($('#pieAlunosTurma')) {
                new Chart($('#pieAlunosTurma'), {
                    type: 'pie',
                    data: {
                        labels: ['Turma A','Turma B','Turma C'],
                        datasets: [{ data: [42, 28, 30] }]
                    },
                    options: { plugins:{ legend:{ labels:{ color:'#fff' }}}}
                });
            }

            // Barras — Professores por disciplina (mock)
            if ($('#barProfPorDisc')) {
                new Chart($('#barProfPorDisc'), {
                    type: 'bar',
                    data: {
                        labels: ['Matemática','Português','História','Física','Química'],
                        datasets: [{ data: [4,6,3,2,3] }]
                    },
                    options: {
                        scales:{
                            x:{ ticks:{ color:'#fff' }, grid:{ color:'rgba(255,255,255,.2)'} },
                            y:{ ticks:{ color:'#fff' }, grid:{ color:'rgba(255,255,255,.2)'} }
                        },
                        plugins:{ legend:{ display:false } }
                    }
                });
            }

            // Linha — Evolução de matrículas (mock)
            if ($('#lineMatriculas')) {
                new Chart($('#lineMatriculas'), {
                    type: 'line',
                    data: {
                        labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out'],
                        datasets: [{ data: [20,24,27,30,45,50,55,60,63,70], tension:.35 }]
                    },
                    options: {
                        scales:{
                            x:{ ticks:{ color:'#fff' }, grid:{ color:'rgba(255,255,255,.15)'} },
                            y:{ ticks:{ color:'#fff' }, grid:{ color:'rgba(255,255,255,.15)'} }
                        },
                        plugins:{ legend:{ display:false } }
                    }
                });
            }

            // Gauge simples com doughnut (mock)
            if ($('#gaugeOcupacao')) {
                new Chart($('#gaugeOcupacao'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Ocupação','Livre'],
                        datasets: [{ data: [72, 28] }]
                    },
                    options: {
                        circumference: 180,
                        rotation: -90,
                        cutout: '70%',
                        plugins:{ legend:{ labels:{ color:'#fff' } } }
                    }
                });
            }
        })();
    </script>
@endsection
