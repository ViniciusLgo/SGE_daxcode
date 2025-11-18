@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">📄 Registros de Alunos</h4>
            <small class="text-muted">Gerencie atestados, declarações e ocorrências dos alunos.</small>
        </div>

        <a href="{{ route('admin.aluno_registros.create') }}" class="btn btn-warning shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Novo Registro
        </a>
    </div>

    {{-- Mensagens --}}
    @include('partials.alerts')

    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}
    <form method="GET" class="row g-2 mb-4">

        <div class="col-md-3">
            <input type="text" name="tipo" class="form-control"
                   placeholder="Tipo (Atestado, Declaração...)"
                   value="{{ request('tipo') }}">
        </div>

        <div class="col-md-2">
            <input type="text" name="categoria" class="form-control"
                   placeholder="Categoria"
                   value="{{ request('categoria') }}">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Status</option>
                @foreach(['pendente','validado','arquivado','expirado'] as $s)
                    <option value="{{ $s }}"
                        {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="turma_id" class="form-select">
                <option value="">Turma</option>
                @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}"
                        {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                        {{ $turma->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 d-grid">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-funnel"></i> Filtrar
            </button>
        </div>
    </form>



    {{-- ========================================================= --}}
    {{-- TABELA --}}
    {{-- ========================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Tipo</th>
                    <th>Categoria</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Turma</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>

                <tbody>
                @forelse($registros as $r)
                    <tr>

                        {{-- ========================= --}}
                        {{-- ALUNO (CORRIGIDO) --}}
                        {{-- ========================= --}}
                        <td>
                            {{ $r->aluno->user->name ?? 'Não informado' }}
                        </td>

                        <td>{{ $r->tipo }}</td>

                        <td>{{ $r->categoria ?? '-' }}</td>

                        {{-- ========================= --}}
                        {{-- DATA FORMATADA --}}
                        {{-- ========================= --}}
                        <td>
                            {{ $r->data_evento ? \Carbon\Carbon::parse($r->data_evento)->format('d/m/Y') : '-' }}
                        </td>

                        {{-- ========================= --}}
                        {{-- STATUS COM BADGE COLORIDA --}}
                        {{-- ========================= --}}
                        <td>
                            <span class="badge
                                @if($r->status == 'pendente') bg-warning text-dark
                                @elseif($r->status == 'validado') bg-success
                                @elseif($r->status == 'arquivado') bg-secondary
                                @else bg-danger @endif">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>

                        {{-- ========================= --}}
                        {{-- TURMA --}}
                        {{-- ========================= --}}
                        <td>{{ $r->turma->nome ?? 'Sem turma' }}</td>

                        {{-- ========================= --}}
                        {{-- AÇÕES --}}
                        {{-- ========================= --}}
                        <td class="text-end">

                            <a href="{{ route('admin.aluno_registros.show', $r->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('admin.aluno_registros.edit', $r->id) }}"
                               class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('admin.aluno_registros.destroy', $r->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Excluir este registro?')">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>

        </div>
    </div>


    {{-- PAGINADOR --}}
    <div class="mt-3">
        {{ $registros->withQueryString()->links() }}
    </div>

@endsection
