@extends('layouts.app')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-people-fill text-primary"></i> Responsáveis</h4>
        <a href="{{ route('admin.responsaveis.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Responsável
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Alunos Vinculados</th>
                    <th class="text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($responsaveis as $r)
                    <tr>
                        <td>{{ $r->nome }}</td>
                        <td>{{ $r->email ?? '—' }}</td>
                        <td>{{ $r->telefone ?? '—' }}</td>
                        <td>{{ $r->alunos_count }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.responsaveis.show', $r->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.responsaveis.edit', $r->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.responsaveis.destroy', $r->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Excluir este responsável?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhum responsável cadastrado.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $responsaveis->links() }}
            </div>
        </div>
    </div>
@endsection
