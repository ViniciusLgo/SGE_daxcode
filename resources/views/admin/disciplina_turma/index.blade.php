@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">📚 Disciplinas por Turma</h4>
        <p class="text-muted mb-0">Gerencie os vínculos entre turmas, disciplinas e professores.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <a href="{{ route('admin.disciplina_turma.create') }}" class="btn btn-primary mb-3">
                ➕ Novo Vínculo
            </a>

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                <tr>
                    <th>Turma</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th>Ano Letivo</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vinculos as $v)
                    <tr>
                        <td>{{ $v->turma->nome ?? '—' }}</td>
                        <td>{{ $v->disciplina->nome ?? '—' }}</td>
                        <td>{{ $v->professor->nome ?? '—' }}</td>
                        <td>{{ $v->ano_letivo ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.disciplina_turma.edit', $v->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <form action="{{ route('admin.disciplina_turma.destroy', $v->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir vínculo?')">🗑️ Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $vinculos->links() }}
        </div>
    </div>
@endsection
