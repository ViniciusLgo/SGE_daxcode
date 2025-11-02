@extends('layouts.app')

@section('content')
    {{-- Cabeçalho da página --}}
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-people-fill text-dark"></i> Usuários do Sistema</h4>
        <p class="text-muted mb-0">Gerencie os acessos e perfis dos usuários.</p>
    </div>

    {{-- Card principal da listagem --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>Lista de Usuários</span>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Novo Usuário
            </a>
        </div>

        <div class="card-body">
            {{-- Mensagem de sucesso ao salvar/excluir --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabela de listagem --}}
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

                        {{-- Mostra o tipo formatado (usa accessor tipo_label do model) --}}
                        <td>
                            <span class="badge bg-secondary">{{ $u->tipo_label }}</span>
                        </td>

                        {{-- Botões de ação: editar e excluir --}}
                        <td class="text-end">
                            <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>

                            <form action="{{ route('admin.usuarios.destroy', $u->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhum usuário encontrado.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
