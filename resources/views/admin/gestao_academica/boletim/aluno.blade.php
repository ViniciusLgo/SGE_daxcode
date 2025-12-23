@extends('layouts.app')

@section('content')

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Boletim do Aluno</h4>
            <p class="text-muted mb-0">
                {{ $aluno->user->name }}
                | Turma {{ $aluno->turma->nome ?? '' }}
            </p>
        </div>

        <a href="{{ route('admin.alunos.show', $aluno) }}"
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    {{-- Conteúdo --}}
    @forelse($boletim as $item)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                {{-- Disciplina --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        {{ $item['disciplina']->nome }}
                    </h5>

                    {{-- Situação --}}
                    <span class="badge
                    {{ $item['situacao'] === 'Aprovado' ? 'bg-success' : '' }}
                    {{ $item['situacao'] === 'Recuperação' ? 'bg-warning text-dark' : '' }}
                    {{ $item['situacao'] === 'Reprovado' ? 'bg-danger' : '' }}">
                    {{ $item['situacao'] }}
                </span>
                </div>

                {{-- Tabela de Avaliações --}}
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-3">
                        <thead class="table-light">
                        <tr>
                            <th>Avaliação</th>
                            <th class="text-center" width="120">Tipo</th>
                            <th class="text-center" width="120">Nota</th>
                            <th class="text-center" width="120">Entrega</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item['avaliacoes'] as $resultado)
                            <tr>
                                <td>
                                    {{ $resultado->avaliacao->titulo }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $resultado->avaliacao->data_avaliacao->format('d/m/Y') }}
                                    </small>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($resultado->avaliacao->tipo) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if(!$resultado->entregue || $resultado->nota === null)
                                        <span class="text-danger fw-bold">0.00</span>
                                    @else
                                        {{ number_format($resultado->nota, 2, ',', '.') }}
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($resultado->entregue)
                                        <span class="badge bg-success">Entregue</span>
                                    @else
                                        <span class="badge bg-danger">Não entregue</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Média --}}
                <div class="d-flex justify-content-end">
                    <div class="text-end">
                        <small class="text-muted">Média da disciplina</small>
                        <h5 class="mb-0
                        {{ $item['media'] < 6 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($item['media'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Nenhuma avaliação registrada para este aluno.
        </div>
    @endforelse

@endsection
