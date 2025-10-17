@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Disciplinas</h4>
            <p class="text-muted mb-0">Catálogo de disciplinas vinculadas aos professores.</p>
        </div>
        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-primary">Nova disciplina</a>
    </div>

    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Busca</label>
            <input type="search" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Nome da disciplina ou professor">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        </div>
        @if($search)
            <div class="col-md-auto">
                <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Professor</th>
                        <th>Carga horária</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disciplinas as $disciplina)
                        <tr>
                            <td>{{ $disciplina->nome }}</td>
                            <td>{{ $disciplina->professor->nome ?? '—' }}</td>
                            <td>{{ $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '—' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.disciplinas.show', $disciplina) }}" class="btn btn-outline-secondary">Detalhes</a>
                                    <a href="{{ route('admin.disciplinas.edit', $disciplina) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('admin.disciplinas.destroy', $disciplina) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir esta disciplina?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Nenhuma disciplina encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($disciplinas->hasPages())
            <div class="card-footer bg-white">
                {{ $disciplinas->links() }}
            </div>
        @endif
    </div>
@endsection
