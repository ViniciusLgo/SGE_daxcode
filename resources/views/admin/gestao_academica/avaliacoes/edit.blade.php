@extends('layouts.app')

@section('content')

    <h4 class="mb-4">Editar Avaliação</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.gestao_academica.avaliacoes.update', $avaliacao) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Título</label>
                        <input type="text"
                               name="titulo"
                               class="form-control"
                               value="{{ $avaliacao->titulo }}"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            @foreach(['Prova','Trabalho','Projeto','Atividade','Atividade Extra'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ $avaliacao->tipo === $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Peso</label>
                        <input type="number"
                               step="0.1"
                               name="peso"
                               value="{{ $avaliacao->peso }}"
                               class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Disciplina</label>
                        <select name="disciplina_id" class="form-select" required>
                            @foreach($disciplinas as $disciplina)
                                <option value="{{ $disciplina->id }}"
                                    {{ $avaliacao->disciplina_id == $disciplina->id ? 'selected' : '' }}>
                                    {{ $disciplina->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Turma</label>
                        <select name="turma_id" class="form-select" required>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}"
                                    {{ $avaliacao->turma_id == $turma->id ? 'selected' : '' }}>
                                    {{ $turma->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Professor</label>
                        <select name="professor_id" class="form-select" required>
                            @foreach($professores as $professor)
                                <option value="{{ $professor->id }}"
                                    {{ $avaliacao->professor_id == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data (opcional)</label>
                        <input type="date"
                               name="data"
                               value="{{ $avaliacao->data?->format('Y-m-d') }}"
                               class="form-control">
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Atualizar
                    </button>

                    <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
                       class="btn btn-outline-secondary">
                        Voltar
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection
