@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i> Disciplinas
            </h4>
            <p class="text-muted mb-0">Gerencie as disciplinas e seus vínculos com professores e turmas.</p>
        </div>
        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nova Disciplina
        </a>
    </div>

    {{-- Barra de busca --}}
    <form method="GET" action="{{ route('admin.disciplinas.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar disciplina..."
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
                    <th>Carga Horária</th>
                    <th>Professores</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($disciplinas as $disciplina)
                    <tr>
                        <td>{{ $disciplina->nome }}</td>
                        <td>{{ $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '—' }}</td>
                        <td>
                            @if ($disciplina->professores->count() > 0)
                                @foreach ($disciplina->professores as $prof)
                                    <span class="badge bg-secondary">{{ $prof->nome }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Nenhum</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.disciplinas.show', $disciplina->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.disciplinas.edit', $disciplina->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.disciplinas.destroy', $disciplina->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Deseja excluir esta disciplina?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Nenhuma disciplina cadastrada.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($disciplinas instanceof \Illuminate\Pagination\LengthAwarePaginator && $disciplinas->hasPages())
        <div class="mt-3">
            {{ $disciplinas->links() }}
        </div>
    @endif
@endsection
