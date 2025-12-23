@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Nova Avaliação</h4>
            <p class="text-muted mb-0">
                Cadastro de avaliação por turma, disciplina e professor.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('admin.gestao_academica.avaliacoes.store') }}" method="POST">
                @csrf

                {{-- Turma --}}
                <div class="mb-3">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select" required>
                        <option value="">Selecione</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}">
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Disciplina --}}
                <div class="mb-3">
                    <label class="form-label">Disciplina</label>
                    <select name="disciplina_id" class="form-select" required>
                        <option value="">Selecione</option>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}">
                                {{ $disciplina->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Professor --}}
                <div class="mb-3">
                    <label class="form-label">Professor</label>
                    <select name="professor_id" class="form-select" required>
                        <option value="">Selecione</option>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}">
                                {{ $professor->user->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Título --}}
                <div class="mb-3">
                    <label class="form-label">Título da Avaliação</label>
                    <input type="text"
                           name="titulo"
                           class="form-control"
                           placeholder="Ex: Prova Bimestral"
                           required>
                </div>

                {{-- Tipo --}}
                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="prova">Prova</option>
                        <option value="trabalho">Trabalho</option>
                        <option value="atividade">Atividade</option>
                        <option value="recuperacao">Recuperação</option>
                    </select>
                </div>

                {{-- Data --}}
                <div class="mb-4">
                    <label class="form-label">Data da Avaliação</label>
                    <input type="date"
                           name="data_avaliacao"
                           class="form-control"
                           required>
                </div>

                {{-- Ações --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
                       class="btn btn-light">
                        Cancelar
                    </a>

                    <button class="btn btn-primary">
                        Salvar Avaliação
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
