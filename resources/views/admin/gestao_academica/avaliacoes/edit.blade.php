@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Editar Avaliação</h4>
            <p class="text-muted mb-0">
                Atualização dos dados da avaliação.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route(
          'admin.gestao_academica.avaliacoes.update',
          ['avaliacao' => $avaliacao->id]
            ) }}">
                @csrf
                @method('PUT')


                {{-- Turma --}}
                <div class="mb-3">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select" required>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}"
                                @selected($avaliacao->turma_id == $turma->id)>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Disciplina --}}
                <div class="mb-3">
                    <label class="form-label">Disciplina</label>
                    <select name="disciplina_id" class="form-select" required>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}"
                                @selected($avaliacao->disciplina_id == $disciplina->id)>
                                {{ $disciplina->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Professor --}}
                <div class="mb-3">
                    <label class="form-label">Professor</label>
                    <select name="professor_id" class="form-select" required>
                        @foreach($professores as $professor)
                            <option value="{{ $professor->id }}"
                                @selected($avaliacao->professor_id == $professor->id)>
                                {{ $professor->user->name ?? '—' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Título --}}
                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text"
                           name="titulo"
                           class="form-control"
                           value="{{ $avaliacao->titulo }}"
                           required>
                </div>

                {{-- Tipo --}}
                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['prova','trabalho','atividade','recuperacao'] as $tipo)
                            <option value="{{ $tipo }}"
                                @selected($avaliacao->tipo === $tipo)>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data --}}
                <div class="mb-3">
                    <label class="form-label">Data da Avaliação</label>
                    <input type="date"
                           name="data_avaliacao"
                           class="form-control"
                           value="{{ $avaliacao->data_avaliacao->format('Y-m-d') }}"
                           required>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="aberta" @selected($avaliacao->status === 'aberta')>
                            Aberta
                        </option>
                        <option value="encerrada" @selected($avaliacao->status === 'encerrada')>
                            Encerrada
                        </option>
                    </select>
                </div>

                {{-- Ações --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
                       class="btn btn-light">
                        Cancelar
                    </a>

                    <button class="btn btn-primary">
                        Atualizar Avaliação
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
