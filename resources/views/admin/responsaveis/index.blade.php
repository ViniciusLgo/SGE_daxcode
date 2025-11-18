@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-people-fill text-primary"></i> Responsáveis
            </h4>
            <p class="text-muted mb-0">Gerencie os responsáveis e seus alunos vinculados.</p>
        </div>
    </div>

    {{-- =============================== --}}
    {{-- BUSCA / FILTRO --}}
    {{-- =============================== --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <label class="form-label">Buscar por nome ou e-mail</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Ex: Maria, João, email@...">
        </div>

        <div class="col-md-2 d-grid">
            <button class="btn btn-outline-primary">
                <i class="bi bi-search"></i> Filtrar
            </button>
        </div>

        @if(request('search'))
            <div class="col-md-2 d-grid">
                <a href="{{ route('admin.responsaveis.index') }}"
                   class="btn btn-outline-secondary">
                    Limpar
                </a>
            </div>
        @endif
    </form>

    {{-- =============================== --}}
    {{-- TABELA --}}
    {{-- =============================== --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>Responsável</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Alunos vinculados</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>

                <tbody>
                @forelse($responsaveis as $r)
                    <tr>

                        {{-- Nome do usuário --}}
                        <td class="fw-semibold">
                            {{ $r->user->name }}
                        </td>

                        {{-- Email do usuário --}}
                        <td>{{ $r->user->email }}</td>

                        {{-- Telefone --}}
                        <td>{{ $r->telefone ?? '—' }}</td>

                        {{-- Contador de alunos --}}
                        <td>
                            <span class="badge bg-primary">
                                {{ $r->alunos_count }} aluno(s)
                            </span>
                        </td>

                        {{-- Ações --}}
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">

                                <a href="{{ route('admin.responsaveis.show', $r) }}"
                                   class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('admin.responsaveis.edit', $r) }}"
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('admin.responsaveis.destroy', $r) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Excluir este responsável?')">
                                    @csrf @method('DELETE')

                                    <button class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhum responsável encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>

        {{-- Paginação --}}
        @if($responsaveis->hasPages())
            <div class="card-footer bg-white">
                {{ $responsaveis->links() }}
            </div>
        @endif

    </div>

@endsection
