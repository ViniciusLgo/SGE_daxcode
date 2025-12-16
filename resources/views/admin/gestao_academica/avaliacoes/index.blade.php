@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Avaliações</h4>
            <p class="text-muted mb-0">
                Gerencie as avaliações por turma e disciplina.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nova Avaliação
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Disciplina</th>
                    <th>Turma</th>
                    <th>Tipo</th>
                    <th>Peso</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>

                <tbody>
                @forelse($avaliacoes as $avaliacao)
                    <tr>
                        <td>{{ $avaliacao->titulo }}</td>
                        <td>{{ $avaliacao->disciplina->nome }}</td>
                        <td>{{ $avaliacao->turma->nome }}</td>
                        <td>{{ $avaliacao->tipo }}</td>
                        <td>{{ $avaliacao->peso }}</td>
                        <td>
                            {{ $avaliacao->data ? $avaliacao->data->format('d/m/Y') : '—' }}
                        </td>
                        <td>
                            @php
                                $badge = match($avaliacao->status) {
                                    'ativa' => 'bg-success',
                                    'encerrada' => 'bg-secondary',
                                    default => 'bg-light text-dark',
                                };
                            @endphp

                            <span class="badge {{ $badge }}">
                             {{ ucfirst($avaliacao->status) }}
                            </span>
                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex gap-2">

                                {{-- STATUS ATIVA --}}
                                @if($avaliacao->status === 'ativa')
                                    <a href="{{ route('admin.gestao_academica.avaliacoes.edit', $avaliacao) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Editar avaliação">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.gestao_academica.avaliacoes.encerrar', $avaliacao) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                onclick="return confirm('Deseja encerrar esta avaliação?')">
                                            Encerrar
                                        </button>
                                    </form>
                                @endif

                                {{-- STATUS ENCERRADA --}}
                                @if($avaliacao->status === 'encerrada')
                                    <form action="{{ route('admin.gestao_academica.avaliacoes.reabrir', $avaliacao) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-secondary">
                                            Reabrir
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Nenhuma avaliação cadastrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>

@endsection
