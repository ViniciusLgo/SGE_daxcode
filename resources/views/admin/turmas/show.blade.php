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

    {{-- Disciplinas e Professores --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📘 Disciplinas e Professores</h5>
            <a href="{{ route('admin.disciplina_turma.index') }}" class="btn btn-sm btn-outline-light">⚙️ Gerenciar Vínculos</a>
        </div>

        <div class="card-body">
            @if(!$turma->disciplinas || $turma->disciplinas->isEmpty())
                <p class="text-muted">Nenhuma disciplina vinculada a esta turma.</p>
            @else
                <div class="row">
                    @foreach($turma->disciplinas as $disciplina)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="border rounded-3 p-3 h-100 shadow-sm bg-light">
                                <h6 class="fw-bold mb-2">{{ $disciplina->nome }}</h6>

                                @php
                                    $vinculo = \App\Models\DisciplinaTurma::where('turma_id', $turma->id)
                                        ->where('disciplina_id', $disciplina->id)
                                        ->first();
                                @endphp

                                {{-- Professores --}}
                                @if($vinculo && $vinculo->professores->count())
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach($vinculo->professores as $prof)
                                            <span class="badge bg-primary px-3 py-2">
                                            👨‍🏫 {{ $prof->nome }}
                                        </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small mt-2">Nenhum professor vinculado.</p>
                                @endif

                                {{-- Ano letivo e observações --}}
                                @if($vinculo && $vinculo->ano_letivo)
                                    <p class="text-muted mt-3 mb-0 small">
                                        📅 <strong>Ano:</strong> {{ $vinculo->ano_letivo }}
                                    </p>
                                @endif

                                @if($vinculo && $vinculo->observacao)
                                    <p class="text-muted small mb-0">📝 {{ $vinculo->observacao }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
