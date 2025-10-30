@extends('layouts.app')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4><i class="bi bi-people-fill text-primary"></i> Responsáveis</h4>
        <a href="{{ route('admin.responsaveis.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Responsável
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($responsaveis as $r)
                    <tr>
                        <td>{{ $r->nome }}</td>
                        <td>{{ $r->email ?? '—' }}</td>
                        <td>{{ $r->telefone ?? '—' }}</td>
                        <td>{{ $r->alunos_count }}</td>
                        <td>
                            <a href="{{ route('admin.responsaveis.show', $r->id) }}" class="btn btn-sm btn-primary">Ver</a>
                            <a href="{{ route('admin.responsaveis.edit', $r->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.responsaveis.destroy', $r->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Excluir este responsável?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $responsaveis->links() }}
        </div>
    </div>
@endsection
