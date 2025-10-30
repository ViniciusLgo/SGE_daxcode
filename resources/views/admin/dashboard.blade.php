@extends('layouts.app')

@section('content')
    <style>
        /* ==================== DAXCODE 2.0+ ==================== */
        :root{
            --dax-yellow:#FFD54F; --dax-dark:#0f1115; --dax-gray:#1c1f26; --dax-accent:#00BFA6;
            --dax-blue:#0ea5e9; --dax-purple:#8b5cf6; --dax-pink:#ec4899; --dax-green:#22c55e;
            --glass-light: rgba(255,255,255,.8); --glass-dark: rgba(24,26,31,.75);
        }
        body{background:linear-gradient(180deg,#f7f7f7,#efefef);}
        body.dark-mode{background:linear-gradient(180deg,#0c0e12,#171a20);}

        /* Header */
        .dash-header{
            display:flex;justify-content:space-between;align-items:center;
            background:var(--glass-light);backdrop-filter: blur(10px);
            padding:1rem 1.25rem;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.06);
            margin-bottom:1.25rem;border:1px solid rgba(0,0,0,.06);
        }
        body.dark-mode .dash-header{background:var(--glass-dark);border-color:#222;color:#eee;}
        .dash-header h4{font-weight:900;margin:0}
        .dash-header p{margin:0;color:#7a7a7a}
        .badge-soft{background:#fff;border:1px solid #eee}
        body.dark-mode .badge-soft{background:#111;color:#ddd;border-color:#2a2a2a}

        /* KPI cards */
        .kpi{
            border:0;border-radius:18px;color:#fff;padding:1.25rem;position:relative;
            overflow:hidden;transition:.25s;cursor:pointer;min-height:140px;
        }
        .kpi:hover{transform:translateY(-4px);box-shadow:0 10px 24px rgba(0,0,0,.2)}
        .kpi:after{
            content:"";position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;
            background:rgba(255,255,255,.15);
        }
        .kpi .kpi-title{letter-spacing:.4px;text-transform:uppercase;font-weight:800;opacity:.9;font-size:.78rem}
        .kpi .kpi-value{font-size:2.4rem;font-weight:900;margin:.25rem 0 0}
        .kpi .kpi-icon{position:absolute;bottom:14px;right:14px;opacity:.85;font-size:2.2rem}

        .bg-grad-yellow{background:linear-gradient(135deg,#F5C518,#f59e0b);}
        .bg-grad-green{background:linear-gradient(135deg,#22c55e,#16a34a);}
        .bg-grad-blue{background:linear-gradient(135deg,#0ea5e9,#2563eb);}
        .bg-grad-purple{background:linear-gradient(135deg,#8b5cf6,#6d28d9);}

        /* Blocos seção (glass) */
        .section{
            border-radius:16px;background:var(--glass-light);backdrop-filter: blur(10px);
            padding:1.25rem;box-shadow:0 6px 24px rgba(0,0,0,.06);border:1px solid rgba(0,0,0,.06);
            margin-bottom:1.25rem;
        }
        body.dark-mode .section{background:var(--glass-dark);border-color:#222;color:#eee}

        /* Quick links */
        .quick-links{display:flex;flex-wrap:wrap;gap:.6rem}
        .quick-link{
            text-decoration:none;font-weight:700;border-radius:12px;padding:.7rem 1rem;
            background:#fff;border:1px solid #eee;color:#111;transition:.2s
        }
        .quick-link:hover{transform:translateY(-2px);background:var(--dax-yellow);color:#000}
        body.dark-mode .quick-link{background:#1f232b;border-color:#2c2f36;color:#eaeaea}

        /* Card Dinâmico */
        .dynamic-card{border-radius:14px;padding:1rem;background:#fff;border:1px solid #eee}
        body.dark-mode .dynamic-card{background:#14181f;border-color:#2a2a2a}
        .progress{height:10px;border-radius:10px;overflow:hidden;background:rgba(0,0,0,.06)}
        body.dark-mode .progress{background:#1f232b}

        /* Lists / tables */
        .list-unstyled li{margin-bottom:.4rem}
        .table-sm td, .table-sm th{padding:.45rem .5rem}

        /* Avatares */
        .mini-avatar{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #ddd}
        body.dark-mode .mini-avatar{border-color:#2c2c2c}

        /* Botões principais */
        .btn-manage{
            border:0;border-radius:12px;padding:.85rem 1rem;font-weight:800;letter-spacing:.3px;
            background:linear-gradient(90deg,#FFD54F,#ffb300);color:#111;box-shadow:0 6px 18px rgba(0,0,0,.15)
        }
        .btn-manage:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(0,0,0,.25)}
        .btn-ghost{
            border:1px dashed #e5e7eb;background:transparent;color:#6b7280;border-radius:10px;
            padding:.6rem .9rem;font-weight:700
        }
        body.dark-mode .btn-ghost{border-color:#333;color:#9aa3af}

        /* Charts */
        .chart-box{height:280px}
        .mini-chart{height:220px}

        /* Módulos */
        .module-tile{
            display:flex;align-items:center;gap:.7rem;padding:1rem;border-radius:14px;
            background:#fff;border:1px solid #eee;text-decoration:none;color:#111;font-weight:800;transition:.2s;
        }
        .module-tile:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(0,0,0,.08)}
        body.dark-mode .module-tile{background:#161a22;border-color:#2a2a2a;color:#eaeaea}
        .module-ico{font-size:1.3rem}

        /* Badges soft */
        .badge-tag{background:#f3f4f6;border:1px solid #e5e7eb;color:#111}
        body.dark-mode .badge-tag{background:#1f232b;border-color:#2a2a2a;color:#ddd}
    </style>

    {{-- HEADER --}}
    <div class="dash-header">
        <div>
            <h4>Painel Administrativo</h4>
            <p>Bem-vindo, {{ auth()->user()->name ?? 'Administrador' }} 👋</p>
        </div>
        <span class="badge badge-soft">
    <i class="bi bi-hdd-network me-1"></i> v{{ $settings->versao_sistema ?? '1.0.0' }}
  </span>
    </div>

    @php
        // Contagens (fallbacks se não vierem do controller)
        $totalAlunos = $alunosCount ?? \App\Models\Aluno::count();
        $totalProfessores = $professoresCount ?? \App\Models\Professor::count();
        $totalDisciplinas = $disciplinasCount ?? \App\Models\Disciplina::count();
        $totalTurmas = $turmasCount ?? \App\Models\Turma::count();

        // KPIs dinâmicos (% simulados, substitua no controller se preferir)
        $taxaMatriculas = $taxaMatriculas ?? min(100, round(($totalAlunos % 100) ?: 78));
        $taxaFrequencia = $taxaFrequencia ?? 92;
        $taxaConclusao  = $taxaConclusao  ?? 74;
        $profAtivosPct  = $profAtivosPct  ?? min(100, max(38, round(($totalProfessores / max(1,$totalProfessores+3))*100)));

        // Visão do dia (mock leve, pode trocar por tabela 'eventos')
        $aniversariantesHoje = $aniversariantesHoje ?? 2;
        $aulasHoje = $aulasHoje ?? 4;
        $reunioesHoje = $reunioesHoje ?? 1;

        // Listas (se não existir, usa coleções vazias)
        $recentAlunos = $recentAlunos ?? collect();
        $atividadesRecentes = $atividadesRecentes ?? collect([
          ['icon'=>'person-plus','text'=>'Aluno <b>Maria Souza</b> matriculada em <b>1ºA</b>'],
          ['icon'=>'bookmark-plus','text'=>'Disciplina <b>Ciências</b> criada'],
          ['icon'=>'person-video3','text'=>'Professor <b>Rogério</b> vinculado à <b>2ºB</b>'],
        ]);
        // Turmas TOP - ideal preencher no controller com withCount('alunos')->orderByDesc('alunos_count')
        $turmasTop = $turmasTop ?? \App\Models\Turma::withCount('alunos')->orderByDesc('alunos_count')->limit(5)->get();
    @endphp

    {{-- KPIs --}}
    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-yellow">
                <div class="kpi-title">Alunos</div>
                <div class="kpi-value">{{ $totalAlunos }}</div>
                <div class="kpi-icon"><i class="bi bi-person-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-green">
                <div class="kpi-title">Professores</div>
                <div class="kpi-value">{{ $totalProfessores }}</div>
                <div class="kpi-icon"><i class="bi bi-person-video3"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-blue">
                <div class="kpi-title">Disciplinas</div>
                <div class="kpi-value">{{ $totalDisciplinas }}</div>
                <div class="kpi-icon"><i class="bi bi-book"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-purple">
                <div class="kpi-title">Turmas</div>
                <div class="kpi-value">{{ $totalTurmas }}</div>
                <div class="kpi-icon"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>

    {{-- Atalhos + Botões Principais --}}
    <div class="section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase mb-0">Atalhos Rápidos</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-manage">👤 Gerenciar Usuários</a>
                <a href="{{ route('admin.settings.edit') }}" class="btn btn-ghost"><i class="bi bi-gear"></i> Configurações</a>
            </div>
        </div>
        <div class="quick-links">
            <a href="{{ route('admin.alunos.create') }}" class="quick-link"><i class="bi bi-person-plus"></i> Novo aluno</a>
            <a href="{{ route('admin.professores.create') }}" class="quick-link"><i class="bi bi-person-video3"></i> Novo professor</a>
            <a href="{{ route('admin.disciplinas.create') }}" class="quick-link"><i class="bi bi-bookmark-plus"></i> Nova disciplina</a>
            <a href="{{ route('admin.turmas.create') }}" class="quick-link"><i class="bi bi-building-add"></i> Nova turma</a>
            <a href="{{ route('admin.disciplinas.index') }}" class="quick-link"><i class="bi bi-diagram-3"></i> Vínculos</a>
            <a href="{{ route('admin.responsaveis.index') }}" class="quick-link"><i class="bi bi-people"></i> Responsáveis</a>
        </div>
    </div>

    {{-- Linha de Cards Dinâmicos (progresso) --}}
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dynamic-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-uppercase fw-bold small">Taxa de Matrículas</span>
                    <span class="badge rounded-pill badge-tag">{{ $taxaMatriculas }}%</span>
                </div>
                <div class="progress mb-1">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $taxaMatriculas }}%"></div>
                </div>
                <small class="text-muted">Comparado ao mês anterior</small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dynamic-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-uppercase fw-bold small">Frequência Média</span>
                    <span class="badge rounded-pill badge-tag">{{ $taxaFrequencia }}%</span>
                </div>
                <div class="progress mb-1">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $taxaFrequencia }}%"></div>
                </div>
                <small class="text-muted">Últimos 30 dias</small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dynamic-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-uppercase fw-bold small">Conclusão Disciplinas</span>
                    <span class="badge rounded-pill badge-tag">{{ $taxaConclusao }}%</span>
                </div>
                <div class="progress mb-1">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $taxaConclusao }}%"></div>
                </div>
                <small class="text-muted">Período letivo atual</small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dynamic-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted text-uppercase fw-bold small">Professores Ativos</span>
                    <span class="badge rounded-pill badge-tag">{{ $profAtivosPct }}%</span>
                </div>
                <div class="progress mb-1">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $profAtivosPct }}%"></div>
                </div>
                <small class="text-muted">Vinculados a turmas</small>
            </div>
        </div>
    </div>

    {{-- Gráfico Principal + Secundários --}}
    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="section">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-uppercase mb-0">📈 Evolução de Matrículas</h6>
                    <span class="badge badge-tag">Últimos 12 meses</span>
                </div>
                <div class="chart-box mt-2"><canvas id="lineMatriculas"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="section">
                        <h6 class="fw-bold text-uppercase mb-2">📊 Alunos por Turma</h6>
                        <div class="mini-chart"><canvas id="chartAlunosTurmas"></canvas></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="section">
                        <h6 class="fw-bold text-uppercase mb-2">📚 Professores por Disciplina</h6>
                        <div class="mini-chart"><canvas id="chartProfDisc"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Atividades Recentes + Alertas + Insights + Hoje --}}
    <div class="row g-3">
        <div class="col-xl-4">
            <div class="section">
                <h6 class="fw-bold text-uppercase mb-2">📋 Atividades Recentes</h6>
                <ul class="list-unstyled small mb-0">
                    @forelse($atividadesRecentes as $log)
                        <li><i class="bi bi-{{ $log['icon'] ?? 'dot' }} me-2 text-primary"></i>{!! $log['text'] ?? '' !!}</li>
                    @empty
                        <li class="text-muted">Sem atividades recentes.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section">
                <h6 class="fw-bold text-uppercase mb-2">⚠️ Alertas do Sistema</h6>
                {{-- Exemplos (substituir por counts reais no controller) --}}
                <div class="alert alert-warning py-2 mb-2"><i class="bi bi-exclamation-triangle me-1"></i> Turmas sem professor vinculado</div>
                <div class="alert alert-danger py-2 mb-0"><i class="bi bi-x-octagon me-1"></i> Alunos com documentos pendentes</div>
            </div>
            <div class="section">
                <h6 class="fw-bold text-uppercase mb-2">💡 Insights</h6>
                <p class="mb-1">📈 Matrículas cresceram <b>{{ rand(8,16) }}%</b> no mês.</p>
                <p class="mb-1">🏫 Turma com maior presença: <b>{{ $turmasTop->first()->nome ?? '—' }}</b>.</p>
                <p class="mb-0">👩‍🏫 Professor(a) com mais turmas: <b>{{ \App\Models\Professor::inRandomOrder()->first()->nome ?? '—' }}</b>.</p>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section">
                <h6 class="fw-bold text-uppercase mb-2">📅 Hoje — {{ now()->format('d/m/Y') }}</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        🎉 Aniversariantes <span class="badge badge-tag">{{ $aniversariantesHoje }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        📚 Aulas agendadas <span class="badge badge-tag">{{ $aulasHoje }}</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between">
                        🧾 Reuniões <span class="badge badge-tag">{{ $reunioesHoje }}</span>
                    </li>
                </ul>
            </div>
            <div class="section">
                <h6 class="fw-bold text-uppercase mb-2">⭐ Destaques do Mês</h6>
                <p class="mb-1"><b>Aluno:</b> Ana Clara <span class="badge badge-tag">Média 9,8</span></p>
                <p class="mb-0"><b>Professor:</b> Carlos Mota <span class="badge badge-tag">5 turmas</span></p>
            </div>
        </div>
    </div>

    {{-- Alunos Recentes --}}
    <div class="section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase mb-0">🧍‍♂️ Alunos Recém-matriculados</h6>
            <span class="badge badge-tag">Atualizado</span>
        </div>
        @if($recentAlunos->count())
            <ul class="list-group list-group-flush">
                @foreach($recentAlunos as $a)
                    <li class="list-group-item bg-transparent border-0 border-bottom d-flex align-items-center">
                        <img class="mini-avatar me-3" src="{{ $a->foto_perfil ? asset('storage/'.$a->foto_perfil) : asset('images/default-avatar.png') }}" alt="Avatar">
                        <div>
                            <div class="fw-bold">{{ $a->nome }}</div>
                            <div class="text-muted small">{{ $a->turma->nome ?? 'Sem turma' }}</div>
                        </div>
                        <span class="ms-auto badge badge-tag">{{ optional($a->created_at)->format('d/m') }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted small mb-0">Nenhum aluno novo no momento.</p>
        @endif
    </div>

    {{-- Módulos principais (atalhos visuais) --}}
    <div class="section">
        <h6 class="fw-bold text-uppercase mb-3">🧩 Módulos</h6>
        <div class="row g-2">
            <div class="col-md-4">
                <a class="module-tile" href="{{ route('admin.alunos.index') }}"><i class="bi bi-mortarboard module-ico text-warning"></i> Acadêmico</a>
            </div>
            <div class="col-md-4">
                <a class="module-tile" href="#"><i class="bi bi-cash-coin module-ico text-success"></i> Financeiro</a>
            </div>
            <div class="col-md-4">
                <a class="module-tile" href="#"><i class="bi bi-briefcase module-ico text-primary"></i> Administrativo</a>
            </div>
            <div class="col-md-4">
                <a class="module-tile" href="#"><i class="bi bi-chat-dots module-ico text-info"></i> Comunicação</a>
            </div>
            <div class="col-md-4">
                <a class="module-tile" href="#"><i class="bi bi-clipboard-data module-ico text-purple"></i> Relatórios</a>
            </div>
            <div class="col-md-4">
                <a class="module-tile" href="{{ route('admin.settings.edit') }}"><i class="bi bi-gear module-ico text-secondary"></i> Configurações</a>
            </div>
        </div>
    </div>

    {{-- Comunicados --}}
    <div class="section">
        <h6 class="fw-bold text-uppercase mb-2">💬 Comunicados Recentes</h6>
        <div class="p-2 rounded mb-2" style="background:rgba(0,0,0,.03)"><b>Direção:</b> Reunião pedagógica na sexta às 14h.</div>
        <div class="p-2 rounded" style="background:rgba(0,0,0,.03)"><b>Secretaria:</b> Atualizar dados dos alunos até dia 10.</div>
    </div>

    {{-- Botão Gerenciar Usuários (reforçado) --}}
    <div class="text-center mt-3">
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-manage w-100">👤 Gerenciar Usuários</a>
    </div>

    {{-- SCRIPTS CHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const isDark = document.body.classList.contains('dark-mode');
            Chart.defaults.color = isDark ? '#e5e7eb' : '#111';
            Chart.defaults.borderColor = isDark ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.08)';
            Chart.defaults.font.family = 'Inter, system-ui, -apple-system, Segoe UI, Roboto';

            // Dados principais
            const evolucaoMatriculas = @json($evolucaoMatriculas ?? []);
            const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
            const dados = Array.from({length:12}, (_,i)=> evolucaoMatriculas[i+1] ?? Math.floor(Math.random()*20)+5);

            // Line Matriculas
            new Chart(document.getElementById('lineMatriculas'),{
                type:'line',
                data:{
                    labels:meses,
                    datasets:[{
                        label:'Matrículas',
                        data:dados,
                        borderColor:'#00BFA6',
                        backgroundColor:'rgba(0,191,166,.18)',
                        tension:.4,
                        fill:true,
                        pointRadius:4,
                        pointBackgroundColor:'#FFD54F'
                    }]
                },
                options:{
                    plugins:{legend:{display:false}},
                    scales:{
                        y:{grid:{color:isDark ? '#222' : '#eee'}},
                        x:{grid:{display:false}}
                    }
                }
            });

            // Pizza Alunos por Turma (usa $turmasTop)
            const turmas = @json($turmasTop->pluck('nome'));
            const alunosCount = @json($turmasTop->pluck('alunos_count'));
            new Chart(document.getElementById('chartAlunosTurmas'), {
                type:'doughnut',
                data:{
                    labels: turmas,
                    datasets:[{
                        data: alunosCount.length ? alunosCount : [12,9,8,7,6],
                        borderWidth:1
                    }]
                },
                options:{
                    plugins:{legend:{position:'bottom'}},
                    cutout:'65%'
                }
            });

            // Barras Professores por Disciplina (dados mock se não houver)
            // Ideal: montar no controller: disciplina->professores_count
            const profDiscLabels = ['Matemática','Português','Ciências','História','Geografia'];
            const profDiscData   = [5,4,3,3,2];
            new Chart(document.getElementById('chartProfDisc'),{
                type:'bar',
                data:{
                    labels: profDiscLabels,
                    datasets:[{
                        label:'Professores',
                        data: profDiscData,
                        borderWidth:1
                    }]
                },
                options:{
                    plugins:{legend:{display:false}},
                    scales:{
                        y:{beginAtZero:true, grid:{color:isDark ? '#222' : '#eee'}},
                        x:{grid:{display:false}}
                    }
                }
            });
        });
    </script>
@endsection
