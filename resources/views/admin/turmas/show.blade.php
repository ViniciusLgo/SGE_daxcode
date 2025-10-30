@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ $turma->nome }}</h4>
            <p class="text-muted mb-0">Detalhes da turma e alunos vinculados.</p>
        </div>
        <a href="{{ route('admin.turmas.index') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Informações da turma</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $turma->nome }}</dd>

                        <dt class="col-sm-5">Ano</dt>
                        <dd class="col-sm-7">{{ $turma->ano ?? '—' }}</dd>

                        <dt class="col-sm-5">Turno</dt>
                        <dd class="col-sm-7">{{ $turma->turno ?? '—' }}</dd>

                        <dt class="col-sm-5">Criada em</dt>
                        <dd class="col-sm-7">{{ $turma->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5">Atualizada em</dt>
                        <dd class="col-sm-7">{{ $turma->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>

                    <div class="mt-3">
                        <h6>Descrição</h6>
                        <p class="mb-0 text-muted">{{ $turma->descricao ?: 'Nenhuma descrição informada.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Alunos ({{ $alunos->total() }})</h5>
                        <a href="{{ route('admin.alunos.create', ['turma_id' => $turma->id]) }}" class="btn btn-sm btn-primary">Adicionar aluno</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Matrícula</th>
                                    <th>E-mail</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alunos as $aluno)
                                    <tr>
                                        <td>{{ $aluno->nome }}</td>
                                        <td>{{ $aluno->matricula }}</td>
                                        <td>{{ $aluno->email }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.alunos.show', $aluno) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Nenhum aluno cadastrado nesta turma.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($alunos->hasPages())
                    <div class="card-footer bg-white">
                        {{ $alunos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
