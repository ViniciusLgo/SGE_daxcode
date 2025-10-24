@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">🎓 Detalhes da Turma</h4>
        <p class="text-muted mb-0">Visualize todas as disciplinas, professores e informações gerais da turma.</p>
    </div>

    {{-- Informações básicas da turma --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold">{{ $turma->nome }}</h5>
            <p class="text-muted mb-1"><strong>Período:</strong> {{ $turma->periodo ?? 'Não informado' }}</p>
            <p class="text-muted mb-1"><strong>Ano Letivo:</strong> {{ $turma->ano_letivo ?? '—' }}</p>
            <p class="text-muted mb-0"><strong>Quantidade de alunos:</strong> {{ $turma->alunos->count() ?? 0 }}</p>
        </div>
    </div>



@endsection
