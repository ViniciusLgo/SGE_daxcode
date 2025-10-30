@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ $disciplina->nome }}</h4>
            <p class="text-muted mb-0">Informações da disciplina e professor responsável.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.disciplinas.edit', $disciplina) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-outline-secondary">Voltar</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Dados da disciplina</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $disciplina->nome }}</dd>

                        <dt class="col-sm-5">Carga horária</dt>
                        <dd class="col-sm-7">{{ $disciplina->carga_horaria ? $disciplina->carga_horaria . ' horas' : '—' }}</dd>

                        <dt class="col-sm-5">Criada em</dt>
                        <dd class="col-sm-7">{{ $disciplina->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5">Atualizada em</dt>
                        <dd class="col-sm-7">{{ $disciplina->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>

                    <div class="mt-3">
                        <h6>Descrição</h6>
                        <p class="mb-0 text-muted">{{ $disciplina->descricao ?: 'Nenhuma descrição informada.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Professores responsáveis</h5>

                    @if($disciplina->professores->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($disciplina->professores as $professor)
                                <li class="mb-2">
                                    <strong>{{ $professor->nome }}</strong><br>
                                    <small class="text-muted">
                                        {{ $professor->email }} — {{ $professor->telefone ?? 'sem telefone' }}
                                    </small>
                                    @if($professor->especializacao)
                                        <br><small><em>{{ $professor->especializacao }}</em></small>
                                    @endif
                                    <br>
                                    <a href="{{ route('admin.professores.show', $professor) }}"
                                       class="btn btn-outline-primary btn-sm mt-1">
                                        Ver perfil
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Nenhum professor vinculado.</p>
                    @endif
                </div>
            </div>
        </div>


    </div>
@endsection
