@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Turmas</h4>
            <p class="text-muted mb-0">Organize as turmas disponíveis e acompanhe seus alunos.</p>
        </div>
        <a href="{{ route('admin.turmas.create') }}" class="btn btn-primary">Nova turma</a>
    </div>

    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Busca</label>
            <input type="search" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Nome, turno ou descrição">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        </div>
        @if($search)
            <div class="col-md-auto">
                <a href="{{ route('admin.turmas.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Ano</th>
                        <th>Turno</th>
                        <th>Alunos</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmas as $turma)
                        <tr>
                            <td>{{ $turma->nome }}</td>
                            <td>{{ $turma->ano ?? '—' }}</td>
                            <td>{{ $turma->turno ?? '—' }}</td>
                            <td>{{ $turma->alunos_count }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.turmas.show', $turma) }}" class="btn btn-outline-secondary">Detalhes</a>
                                    <a href="{{ route('admin.turmas.edit', $turma) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('admin.turmas.destroy', $turma) }}" method="POST" onsubmit="return confirm('Excluir esta turma também removerá os alunos vinculados. Deseja continuar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhuma turma encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($turmas->hasPages())
            <div class="card-footer bg-white">
                {{ $turmas->links() }}
            </div>
        @endif
    </div>
@endsection
