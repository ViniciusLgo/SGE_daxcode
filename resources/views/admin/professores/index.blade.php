@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Professores</h4>
            <p class="text-muted mb-0">Cadastre professores e associe disciplinas.</p>
        </div>
        <a href="{{ route('admin.professores.create') }}" class="btn btn-primary">Novo professor</a>
    </div>

    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Busca</label>
            <input type="search" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Nome, e-mail ou especialização">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-outline-primary">Filtrar</button>
        </div>
        @if($search)
            <div class="col-md-auto">
                <a href="{{ route('admin.professores.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Disciplinas</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($professores as $professor)
                        <tr>
                            <td>{{ $professor->nome }}</td>
                            <td>{{ $professor->email }}</td>
                            <td>{{ $professor->telefone ?? '—' }}</td>
                            <td>{{ $professor->disciplinas_count }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.professores.show', $professor) }}" class="btn btn-outline-secondary">Detalhes</a>
                                    <a href="{{ route('admin.professores.edit', $professor) }}" class="btn btn-outline-primary">Editar</a>
                                    <form action="{{ route('admin.professores.destroy', $professor) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este professor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Excluir</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhum professor encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($professores->hasPages())
            <div class="card-footer bg-white">
                {{ $professores->links() }}
            </div>
        @endif
    </div>
@endsection
