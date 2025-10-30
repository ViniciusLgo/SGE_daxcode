@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="bi bi-person-badge-fill text-primary"></i> Professores</h4>
        <a href="{{ route('admin.professores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Professor
        </a>
    </div>

    {{-- Barra de busca --}}
    <form method="GET" action="{{ route('admin.professores.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou e-mail"
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
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
                        <td>{{ $prof->nome }}</td>
                        <td>{{ $prof->email }}</td>
                        <td>{{ $prof->disciplinas_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.professores.show', $prof->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.professores.edit', $prof->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.professores.destroy', $prof->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Deseja excluir este professor?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Nenhum professor encontrado.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $professores->links() }}
    </div>
@endsection
