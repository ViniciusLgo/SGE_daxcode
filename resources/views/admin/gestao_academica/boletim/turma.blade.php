@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Boletim da Turma</h4>
            <p class="text-muted mb-0">
                {{ $turma->nome }}
            </p>
        </div>

        <a href="{{ route('admin.turmas.show', $turma) }}"
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($boletins->isEmpty())
                <div class="alert alert-info mb-0">
                    Nenhum resultado lançado para esta turma ainda.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Aluno</th>

                            @foreach($disciplinas as $disciplina)
                                <th class="text-center">{{ $disciplina->nome }}</th>
                            @endforeach

                            <th class="text-center">Média</th>
                            <th class="text-center">Situação</th>
                            <th width="120">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($boletins as $item)
                            @php
                                $medias = $item['disciplinas']->pluck('media');
                                $mediaGeral = $medias->count() ? round($medias->avg(), 2) : 0;

                                $situacao = match(true) {
                                    $mediaGeral >= 6 => 'Aprovado',
                                    $mediaGeral >= 4 => 'Recuperação',
                                    default => 'Reprovado'
                                };
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $item['aluno']->user->name }}</strong>
                                </td>

                                @foreach($disciplinas as $disciplina)
                                    <td class="text-center">
                                        {{ optional($item['disciplinas']->get($disciplina->id))['media'] ?? '-' }}
                                    </td>
                                @endforeach

                                <td class="text-center fw-bold">
                                    {{ $mediaGeral }}
                                </td>

                                <td class="text-center">
                    <span class="badge
                        @if($situacao === 'Aprovado') bg-success
                        @elseif($situacao === 'Recuperação') bg-warning
                        @else bg-danger
                        @endif">
                        {{ $situacao }}
                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.boletim.aluno', $item['aluno']) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </div>
    </div>

@endsection
