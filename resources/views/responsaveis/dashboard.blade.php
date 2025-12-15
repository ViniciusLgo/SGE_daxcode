@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4 class="mb-1">
            👨‍👩‍👧 Painel do Responsável
        </h4>
        <p class="text-muted mb-0">
            Bem-vindo, {{ auth()->user()->name }} — aqui você acompanha seus filhos e registros escolares.
        </p>
    </div>

    @php
        $responsavel = auth()->user()->responsavel;
        $filhos = $responsavel->alunos()->with(['user','turma'])->get();
    @endphp

    {{-- ====================================================== --}}
    {{-- CARDS RESUMO --}}
    {{-- ====================================================== --}}
    <div class="row g-3 mb-4">

        {{-- FILHOS VINCULADOS --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 bg-light">
                <div class="d-flex justify-content-between">
                    <span class="kpi-emoji fs-3">🎓</span>
                </div>
                <div class="kpi-title mt-2 text-dark fw-semibold">Filhos vinculados</div>
                <div class="kpi-number text-primary">
                    {{ $filhos->count() }}
                </div>
                <div class="text-muted small">Total de alunos sob sua responsabilidade</div>
            </div>
        </div>

        {{-- TURMAS --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 bg-light">
                <span class="kpi-emoji fs-3">🏫</span>
                <div class="kpi-title mt-2 fw-semibold text-dark">Turmas</div>
                <div class="kpi-number text-primary">
                    {{ $filhos->pluck('turma_id')->unique()->count() }}
                </div>
                <div class="text-muted small">Total de turmas diferentes</div>
            </div>
        </div>

        {{-- REGISTROS --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 bg-light">
                <span class="kpi-emoji fs-3">📄</span>
                <div class="kpi-title mt-2 fw-semibold text-dark">Registros recentes</div>
                <div class="kpi-number text-primary">
                    {{ \App\Models\AlunoRegistro::whereIn('aluno_id', $filhos->pluck('id'))->count() }}
                </div>
                <div class="text-muted small">Atestados, ocorrências ou documentos</div>
            </div>
        </div>

    </div>

    {{-- ====================================================== --}}
    {{-- LISTA DE FILHOS --}}
    {{-- ====================================================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                👦👧 Filhos vinculados
            </h5>
        </div>

        <div class="card-body p-0">
            @if($filhos->isEmpty())
                <p class="text-muted p-3">Nenhum aluno vinculado.</p>
            @else
                <div class="list-group list-group-flush">

                    @foreach($filhos as $aluno)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $aluno->user->name }}</strong><br>
                                <span class="text-muted small">
                                    Turma: {{ $aluno->turma->nome ?? 'Sem turma' }}
                                </span>
                            </div>

                            <a href="{{ route('admin.alunos.show', $aluno->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Ver detalhes
                            </a>
                        </div>
                    @endforeach

                </div>
            @endif
        </div>
    </div>

@endsection
