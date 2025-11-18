@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Gerenciar Disciplinas — {{ $turma->nome }}</h4>
            <p class="text-muted mb-0">Adicione disciplinas e vincule professores à turma.</p>
        </div>

        <a href="{{ route('admin.turmas.show', $turma->id) }}" class="btn btn-outline-secondary">
            Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Disciplinas vinculadas</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarDisciplina">
                Adicionar Disciplina
            </button>
        </div>

        <div class="card-body">

            @forelse($turma->disciplinaTurmas as $vinculo)
                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">
                                <strong>{{ $vinculo->disciplina->nome }}</strong>
                            </h6>

                            <small class="text-muted d-block">
                                Ano letivo: {{ $vinculo->ano_letivo ?? '—' }}
                            </small>

                            <small class="text-muted d-block">
                                Observação: {{ $vinculo->observacao ?? '—' }}
                            </small>
                        </div>

                        <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluirDisciplina{{ $vinculo->id }}">
                            Remover
                        </button>
                    </div>

                    <div class="mt-3">
                        <strong>Professores:</strong>

                        @if($vinculo->professores->isEmpty())
                            <p class="text-muted mb-1">Nenhum professor vinculado.</p>
                        @else
                            <ul class="mb-2">
                                @foreach($vinculo->professores as $prof)
                                    <li class="d-flex justify-content-between align-items-center">
                                        <div>
                                            {{ $prof->user->name }}
                                            <small class="text-muted">({{ $prof->user->email }})</small>
                                        </div>

                                        <form method="POST"
                                              action="{{ route('admin.turmas.disciplinas.professores.destroy', [
                                                  $turma->id,
                                                  $vinculo->id,
                                                  $prof->id
                                              ]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Remover</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAdicionarProfessor{{ $vinculo->id }}">
                            Adicionar Professor
                        </button>
                    </div>

                </div>

                <!-- MODAL REMOVER DISCIPLINA -->
                <div class="modal fade" id="modalExcluirDisciplina{{ $vinculo->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST"
                              action="{{ route('admin.turmas.disciplinas.destroy', [$turma->id, $vinculo->id]) }}"
                              class="modal-content">
                            @csrf
                            @method('DELETE')

                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar Remoção</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                Tem certeza que deseja remover a disciplina
                                <strong>{{ $vinculo->disciplina->nome }}</strong> da turma?
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Remover</button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- MODAL ADICIONAR PROFESSOR -->
                <div class="modal fade" id="modalAdicionarProfessor{{ $vinculo->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST"
                              action="{{ route('admin.turmas.disciplinas.professores.store', [$turma->id, $vinculo->id]) }}"
                              class="modal-content">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Adicionar Professor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <label class="form-label">Professor</label>
                                <select name="professor_id" class="form-select" required>
                                    <option value="">Selecione...</option>

                                    @foreach($professores as $prof)
                                        <option value="{{ $prof->id }}">
                                            {{ $prof->user->name }} — {{ $prof->user->email }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>

                        </form>
                    </div>
                </div>

            @empty
                <p class="text-center text-muted py-3">Nenhuma disciplina vinculada.</p>
            @endforelse

        </div>
    </div>


    <!-- MODAL ADICIONAR DISCIPLINA -->
    <div class="modal fade" id="modalAdicionarDisciplina" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST"
                  action="{{ route('admin.turmas.disciplinas.store', $turma->id) }}"
                  class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Disciplina à Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">Disciplina</label>
                    <select name="disciplina_id" class="form-select" required>
                        <option value="">Selecione...</option>

                        @foreach($todasDisciplinas as $d)
                            <option value="{{ $d->id }}">{{ $d->nome }}</option>
                        @endforeach
                    </select>

                    <label class="form-label mt-3">Ano Letivo</label>
                    <input type="text" name="ano_letivo" class="form-control" value="{{ date('Y') }}">

                    <label class="form-label mt-3">Observação</label>
                    <textarea name="observacao" class="form-control" rows="2"></textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>

            </form>
        </div>
    </div>

@endsection
