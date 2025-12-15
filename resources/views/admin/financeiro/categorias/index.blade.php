@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Categorias de Despesas</h4>
            <p class="text-muted mb-0">Gerencie os tipos de gastos do projeto social.</p>
        </div>
        <a href="{{ route('admin.financeiro.categorias.create') }}" class="btn btn-primary">
            + Nova Categoria
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($categorias->count() == 0)
        <div class="alert alert-info">
            Nenhuma categoria cadastrada ainda. Clique em <strong>+ Nova Categoria</strong> para começar.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th class="text-end">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->nome }}</td>
                            <td>{{ $categoria->descricao }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.financeiro.categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <form action="{{ route('admin.financeiro.categorias.destroy', $categoria) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir esta categoria?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
