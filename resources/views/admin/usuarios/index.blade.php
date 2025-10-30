@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-people-fill text-dark"></i> Usuários do Sistema</h4>
        <p class="text-muted mb-0">Gerencie os acessos e perfis dos usuários.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>Lista de Usuários</span>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Novo Usuário
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($usuarios as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($u->tipo) }}</span></td>
                        <td class="text-end">
                            <form action="{{ route('admin.usuarios.destroy', $u->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir este usuário?')">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Nenhum usuário encontrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
