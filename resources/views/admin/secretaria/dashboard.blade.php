@extends('layouts.app')

@section('content')

    <style>
        /* Animação suave de crescimento nos números */
        .counter {
            font-size: 2rem;
            font-weight: bold;
            transition: all .4s ease-in-out;
        }

        .card-kpi {
            border-radius: 12px;
            transition: transform .2s;
        }

        .card-kpi:hover {
            transform: scale(1.02);
        }

        .status-pill {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-concluido { background: #d1ffd6; color: #0b7a1c; }
        .status-pendente { background: #fff3cd; color: #946200; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Secretaria Escolar</h4>
            <p class="text-muted mb-0">
                Painel administrativo com visão geral das matrículas, documentos e atendimentos.
            </p>
        </div>
    </div>

    {{-- ================================ --}}
    {{--        CARDS PRINCIPAIS          --}}
    {{-- ================================ --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card card-kpi shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted small">Alunos Registrados</span>
                        <div class="counter mt-1">{{ $totalAlunos }}</div>
                    </div>
                    <div class="fs-2">🎓</div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Todos os alunos registrados no sistema.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-kpi shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted small">Turmas Ativas</span>
                        <div class="counter mt-1">{{ $totalTurmas }}</div>
                    </div>
                    <div class="fs-2">📚</div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Total de turmas cadastradas.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-kpi shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted small">Pendências Documentais</span>
                        <div class="counter mt-1">{{ $pendencias }}</div>
                    </div>
                    <div class="fs-2">📄</div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Alunos com documentação incompleta.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-kpi shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted small">Solicitações do Dia</span>
                        <div class="counter mt-1">{{ count($atendimentosRecentes) }}</div>
                    </div>
                    <div class="fs-2">💬</div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Atendimentos registrados recentemente.
                </p>
            </div>
        </div>

    </div>

    {{-- ================================ --}}
    {{--     ATENDIMENTOS RECENTES        --}}
    {{-- ================================ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">📌 Atendimentos Recentes</h5>

            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Aluno/Responsável</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>
                @foreach($atendimentosRecentes as $a)
                    <tr>
                        <td>{{ $a['tipo'] }}</td>
                        <td>{{ $a['aluno'] }}</td>
                        <td>{{ $a['data'] }}</td>
                        <td>
                            @if($a['status'] === 'Concluído')
                                <span class="status-pill status-concluido">Concluído</span>
                            @else
                                <span class="status-pill status-pendente">Pendente</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <p class="text-muted small mt-2 mb-0">Futuramente essa tabela será alimentada pelo módulo de "Atendimentos da Secretaria".</p>
        </div>
    </div>

    {{-- ================================ --}}
    {{--      PENDÊNCIAS DOCUMENTAIS      --}}
    {{-- ================================ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">📄 Pendências Documentais</h5>

            <p class="text-muted mb-0">
                Quando implementarmos a checagem automática de documentos obrigatórios,
                essa área vai listar os alunos com pendências.
            </p>
        </div>
    </div>

    {{-- ================================ --}}
    {{--         GRÁFICOS (Chart.js)       --}}
    {{-- ================================ --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-3">📊 Visão Geral (Gráficos)</h5>

            <canvas id="graficoSecretaria" height="120"></canvas>

            <p class="text-muted small mt-2">
                O gráfico será ativado quando você quiser implementar. O espaço já está pronto.
            </p>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Placeholder para futuro gráfico
        const ctx = document.getElementById('graficoSecretaria');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Alunos', 'Turmas', 'Pendências'],
                datasets: [{
                    label: 'Indicadores',
                    data: [{{ $totalAlunos }}, {{ $totalTurmas }}, {{ $pendencias }}],
                }]
            }
        });
    </script>
@endsection
