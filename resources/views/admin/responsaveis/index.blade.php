@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-people-fill text-primary"></i> Responsáveis</h4>
            <p class="text-muted mb-0">Gerencie os responsáveis e seus alunos vinculados.</p>
        </div>
        <a href="{{ route('admin.responsaveis.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Responsável
        </a>
    </div>

    {{-- Barra de busca --}}
    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Busca</label>
            <input type="search" id="search" name="search" value="{{ $search ?? '' }}"
                   class="form-control" placeholder="Nome ou e-mail">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>
        @if($search)
            <div class="col-md-auto">
                <a href="{{ route('admin.responsaveis.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Alunos Vinculados</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($responsaveis as $r)
                    <tr>
                        <td>{{ $r->user->name ?? '—' }}</td>
                        <td>{{ $r->user->email ?? '—' }}</td>
                        <td>{{ $r->telefone ?? '—' }}</td>
                        <td>{{ $r->alunos_count ?? 0 }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.responsaveis.show', $r) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.responsaveis.edit', $r) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.responsaveis.destroy', $r) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Excluir este responsável?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhum responsável cadastrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($responsaveis->hasPages())
            <div class="card-footer bg-white">
                {{ $responsaveis->links() }}
            </div>
        @endif
    </div>
@endsection
