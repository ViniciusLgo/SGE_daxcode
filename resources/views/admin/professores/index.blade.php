@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-person-badge-fill text-primary"></i> Professores</h4>
            <p class="text-muted mb-0">Gerencie os professores e suas disciplinas vinculadas.</p>
        </div>
        <a href="{{ route('admin.professores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Professor
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
                <a href="{{ route('admin.professores.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
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
                    <th>Disciplinas</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($professores as $prof)
                    <tr>
                        <td>{{ $prof->user->name ?? '—' }}</td>
                        <td>{{ $prof->user->email ?? '—' }}</td>
                        <td>{{ $prof->disciplinas_count ?? 0 }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.professores.show', $prof) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.professores.edit', $prof->id) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.professores.destroy', $prof) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Deseja excluir este professor?')">
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
                        <td colspan="4" class="text-center text-muted py-3">
                            Nenhum professor encontrado.
                        </td>
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
