@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Resultados da Avaliação</h4>
            <p class="text-muted mb-0">
                {{ $avaliacao->titulo }}
                — {{ $avaliacao->disciplina->nome ?? '' }}
                | Turma {{ $avaliacao->turma->nome ?? '' }}
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="btn btn-outline-secondary">
            ← Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.gestao_academica.avaliacoes.resultados.store', $avaliacao) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Aluno</th>
                            <th class="text-center" width="110">Nota</th>
                            <th width="220">Arquivo</th>
                            <th>Observação</th>
                            <th class="text-center" width="120">Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($alunos as $aluno)
                            @php
                                $resultado = $resultados[$aluno->id] ?? null;
                            @endphp

                            <tr>
                                {{-- Aluno --}}
                                <td>
                                    <strong>{{ $aluno->user->name }}</strong>
                                </td>

                                {{-- Nota --}}
                                <td class="text-center">
                                    <input type="number"
                                           name="resultados[{{ $aluno->id }}][nota]"
                                           class="form-control text-center
                                           {{ ($resultado?->nota !== null && $resultado->nota < 6) ? 'border-danger' : '' }}"
                                           min="0"
                                           max="10"
                                           step="0.01"
                                           value="{{ $resultado->nota ?? '' }}"
                                        {{ $somenteLeitura ? 'disabled' : '' }}>
                                </td>

                                {{-- Arquivo --}}
                                <td>
                                    @if($resultado && $resultado->arquivo)
                                        <a href="{{ asset('storage/'.$resultado->arquivo) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary mb-1">
                                            📄 Ver arquivo
                                        </a>
                                    @else
                                        <span class="text-muted small">Nenhum arquivo</span>
                                    @endif

                                    @unless($somenteLeitura)
                                        <div class="mt-1">
                                            <label class="btn btn-sm btn-outline-secondary">
                                                Anexar
                                                <input type="file"
                                                       name="resultados[{{ $aluno->id }}][arquivo]"
                                                       hidden>
                                            </label>
                                        </div>
                                    @endunless
                                </td>

                                {{-- Observação --}}
                                <td>
                                    <input type="text"
                                           name="resultados[{{ $aluno->id }}][observacao]"
                                           class="form-control form-control-sm"
                                           placeholder="Comentário opcional"
                                           value="{{ $resultado->observacao ?? '' }}"
                                        {{ $somenteLeitura ? 'disabled' : '' }}>
                                </td>

                                {{-- Entregue --}}
                                <td class="text-center">
                                    @if($resultado?->entregue)
                                        <span class="badge bg-success">Entregue</span>
                                    @else
                                        <span class="badge bg-secondary">Pendente</span>
                                    @endif

                                    @unless($somenteLeitura)
                                        <div class="form-check mt-1 d-flex justify-content-center">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="resultados[{{ $aluno->id }}][entregue]"
                                                @checked($resultado?->entregue)>
                                        </div>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @unless($somenteLeitura)
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary">
                            Salvar Resultados
                        </button>
                    </div>
                @endunless

            </form>

        </div>
    </div>

@endsection
