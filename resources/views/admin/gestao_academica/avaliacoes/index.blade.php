@extends('layouts.app')

@section('content')

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Avaliações</h4>
            <p class="text-muted mb-0">
                Gestão de avaliações por turma e disciplina.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.create') }}"
           class="btn btn-primary">
            ➕ Nova Avaliação
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Turma</th>
                    <th>Disciplina</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>

                <tbody>
                @forelse($avaliacoes as $avaliacao)
                    <tr>
                        <td>{{ $avaliacao->titulo }}</td>
                        <td>{{ $avaliacao->turma->nome ?? '—' }}</td>
                        <td>{{ $avaliacao->disciplina->nome ?? '—' }}</td>

                        <td>
                        <span class="badge bg-info text-dark">
                            {{ ucfirst($avaliacao->tipo) }}
                        </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($avaliacao->data_avaliacao)->format('d/m/Y') }}
                        </td>

                        <td>
                            @if($avaliacao->status === 'encerrada')
                                <span class="badge bg-secondary">Encerrada</span>
                            @else
                                <span class="badge bg-success">Aberta</span>
                            @endif
                        </td>

                        <td class="text-end">

                            {{-- Editar --}}
                            <a href="{{ route('admin.gestao_academica.avaliacoes.edit', $avaliacao) }}"
                               class="btn btn-sm btn-outline-primary">
                                Editar
                            </a>

                            {{-- Resultados --}}
                            <a href="{{ route('admin.gestao_academica.avaliacoes.resultados.index', $avaliacao) }}"
                               class="btn btn-sm {{ $avaliacao->status === 'aberta'
                                ? 'btn-outline-success'
                                : 'btn-outline-secondary' }}">
                                {{ $avaliacao->status === 'aberta'
                                    ? 'Lançar Resultados'
                                    : 'Ver Resultados' }}
                            </a>

                            {{-- 🔓 Reabrir --}}
                            @if($avaliacao->status === 'encerrada')
                                <form action="{{ route('admin.gestao_academica.avaliacoes.reabrir', $avaliacao) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-outline-success">
                                        Reabrir
                                    </button>
                                </form>
                            @endif

                            {{-- Encerrar --}}
                            @if($avaliacao->status === 'aberta')
                                <form action="{{ route('admin.gestao_academica.avaliacoes.encerrar', $avaliacao) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="return confirm('Deseja encerrar esta avaliação?')">
                                        Encerrar
                                    </button>
                                </form>
                            @endif

                            {{-- Excluir --}}
                            <form action="{{ route('admin.gestao_academica.avaliacoes.destroy', $avaliacao) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Deseja excluir esta avaliação?')">
                                    Excluir
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Nenhuma avaliação cadastrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $avaliacoes->links() }}

        </div>
    </div>

@endsection
