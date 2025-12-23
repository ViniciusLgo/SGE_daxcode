@extends('layouts.app')

@section('content')

    {{-- ===============================
        ESTILOS LOCAIS DO DASHBOARD
        =============================== --}}
    <style>
        .counter {
            font-size: 2rem;
            font-weight: bold;
            transition: all .3s ease;
        }

        .card-kpi {
            border-radius: 12px;
            transition: transform .2s ease;
            cursor: pointer;
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

        .status-concluido {
            background: #d1ffd6;
            color: #0b7a1c;
        }

        .status-pendente {
            background: #fff3cd;
            color: #946200;
        }
    </style>

    {{-- ===============================
        CABEÇALHO
        =============================== --}}
    <div class="mb-4">
        <h4 class="mb-1">Secretaria Escolar</h4>
        <p class="text-muted mb-0">
            Painel administrativo com visão geral das matrículas, documentos e atendimentos.
        </p>
    </div>

    {{-- ===============================
        CARDS DE INDICADORES
        =============================== --}}
    <div class="row g-3 mb-4">

        {{-- Alunos --}}
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
                    Total de alunos no sistema.
                </p>
            </div>
        </div>

        {{-- Turmas --}}
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

        {{-- Pendências --}}
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

        {{-- Atendimentos (ATALHO) --}}
        <div class="col-md-3">
            <a href="{{ route('admin.secretaria.atendimentos.index') }}"
               class="text-decoration-none text-dark">
                <div class="card card-kpi shadow-sm border-0 p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Solicitações do Dia</span>
                            <div class="counter mt-1">{{ count($atendimentosRecentes) }}</div>
                        </div>
                        <div class="fs-2">💬</div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Clique para gerenciar atendimentos.
                    </p>
                </div>
            </a>
        </div>

    </div>

    {{-- ===============================
        ATENDIMENTOS RECENTES
        =============================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">📌 Atendimentos Recentes</h5>

                <a href="{{ route('admin.secretaria.atendimentos.index') }}"
                   class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>

            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Aluno / Responsável</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>
                @forelse($atendimentosRecentes as $a)
                    <tr>
                        <td>{{ $a->tipo ?? $a['tipo'] }}</td>

                        {{-- Correção do bug do objeto --}}
                        <td>
                            @if(is_object($a) && $a->aluno)
                                {{ $a->aluno->user->name ?? '—' }}
                            @else
                                {{ $a['aluno'] ?? '—' }}
                            @endif
                        </td>

                        <td>
                            {{ is_object($a) ? $a->data_atendimento->format('d/m/Y') : $a['data'] }}
                        </td>

                        <td>
                            @php
                                $status = is_object($a) ? $a->status : $a['status'];
                            @endphp

                            @if($status === 'Concluído' || $status === 'concluido')
                                <span class="status-pill status-concluido">Concluído</span>
                            @else
                                <span class="status-pill status-pendente">Pendente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Nenhum atendimento recente.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>

@endsection
