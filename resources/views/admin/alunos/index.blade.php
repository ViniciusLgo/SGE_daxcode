@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-mortarboard-fill text-primary"></i> Alunos</h4>
            <p class="text-muted mb-0">Gerencie os estudantes cadastrados no sistema.</p>
        </div>
    </div>

    {{-- Filtro / Busca --}}
    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="search" class="form-label">Busca</label>
            <input type="search" id="search" name="search" value="{{ $search }}"
                   class="form-control" placeholder="Nome, matrícula ou e-mail">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Filtrar</button>
        </div>
        @if($search)
            <div class="col-md-auto">
                <a href="{{ route('admin.alunos.index') }}" class="btn btn-link text-decoration-none">Limpar</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>E-mail</th>
                    <th>Turma</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($alunos as $aluno)
                    <tr>
                        <td>{{ $aluno->user->name ?? '—' }}</td>
                        <td>{{ $aluno->matricula ?? '—' }}</td>
                        <td>{{ $aluno->user->email ?? '—' }}</td>
                        <td>{{ $aluno->turma->nome ?? '—' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.alunos.show', $aluno) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.alunos.edit', $aluno) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.alunos.destroy', $aluno) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir este aluno?')">
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
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhum aluno encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($alunos->hasPages())
            <div class="card-footer bg-white">
                {{ $alunos->links() }}
            </div>
        @endif
    </div>
@endsection
