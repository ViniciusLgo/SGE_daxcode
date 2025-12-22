@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>Atendimentos da Secretaria</h4>
            <p class="text-muted mb-0">Histórico de solicitações administrativas.</p>
        </div>

        <a href="{{ route('admin.secretaria.atendimentos.create') }}"
           class="btn btn-primary">
            ➕ Novo Atendimento
        </a>
    </div>

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
                                    'pendente' => 'warning',
                                    'cancelado' => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $badge }}">
                            {{ ucfirst($a->status) }}
                        </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
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
