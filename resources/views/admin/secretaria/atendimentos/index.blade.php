@extends('layouts.app')

@section('content')

    {{-- ===============================
        CABEÇALHO
        =============================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Atendimentos da Secretaria</h4>
            <p class="text-muted mb-0">
                Histórico de solicitações administrativas.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.secretaria.dashboard') }}"
               class="btn btn-outline-secondary">
                ← Voltar
            </a>

            <a href="{{ route('admin.secretaria.atendimentos.create') }}"
               class="btn btn-primary">
                ➕ Novo Atendimento
            </a>
        </div>
    </div>

    {{-- ===============================
        LISTAGEM
        =============================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Aluno</th>
                    <th>Responsável</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>

                <tbody>
                @forelse($atendimentos as $a)
                    <tr>
                        <td>{{ $a->tipo }}</td>
                        <td>{{ $a->aluno->user->name ?? '—' }}</td>
                        <td>{{ $a->responsavel->user->name ?? '—' }}</td>
                        <td>{{ $a->data_atendimento->format('d/m/Y') }}</td>

                        <td>
                            @php
                                $badge = match($a->status) {
                                    'concluido' => 'success',
                                    'pendente'  => 'warning',
                                    'cancelado' => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.secretaria.atendimentos.edit', $a) }}"
                               class="btn btn-sm btn-outline-primary">
                                ✏️
                            </a>

                            <form action="{{ route('admin.secretaria.atendimentos.destroy', $a) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Excluir atendimento?')">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Nenhum atendimento registrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $atendimentos->links() }}

        </div>
    </div>

@endsection
