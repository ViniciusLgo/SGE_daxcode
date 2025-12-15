@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Centros de Custo</h4>
            <p class="text-muted mb-0">Agrupe despesas por projeto, turma ou atividade.</p>
        </div>
        <a href="{{ route('admin.financeiro.centros.create') }}" class="btn btn-primary">
            + Novo Centro
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($centros->count() == 0)
        <div class="alert alert-info">
            Nenhum centro de custo cadastrado ainda.
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
                    @foreach($centros as $centro)
                        <tr>
                            <td>{{ $centro->id }}</td>
                            <td>{{ $centro->nome }}</td>
                            <td>{{ $centro->descricao }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.financeiro.centros.edit', $centro) }}" class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <form action="{{ route('admin.financeiro.centros.destroy', $centro) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir este centro de custo?');">
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
