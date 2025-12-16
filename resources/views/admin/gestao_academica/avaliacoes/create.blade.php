@extends('layouts.app')

@section('content')

    <h4 class="mb-4">Nova Avaliação</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.gestao_academica.avaliacoes.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">Selecione</option>
                            <option>Prova</option>
                            <option>Trabalho</option>
                            <option>Projeto</option>
                            <option>Atividade</option>
                            <option>Atividade Extra</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Peso</label>
                        <input type="number" name="peso" step="0.1" value="1" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Disciplina</label>
                        <select name="disciplina_id" class="form-select" required>
                            @foreach($disciplinas as $disciplina)
                                <option value="{{ $disciplina->id }}">
                                    {{ $disciplina->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Turma</label>
                        <select name="turma_id" class="form-select" required>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}">
                                    {{ $turma->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Professor</label>
                        <select name="professor_id" class="form-select" required>
                            @foreach($professores as $professor)
                                <option value="{{ $professor->id }}">
                                    {{ $professor->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Data da Avaliação (opcional)</label>
                        <input
                            type="date"
                            name="data_avaliacao"
                            class="form-control"
                            value="{{ old('data_avaliacao') }}"
                        >
                    </div>


                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Salvar
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
