@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">{{ $aluno->nome }}</h4>
            <p class="text-muted mb-0">Detalhes do aluno e sua turma.</p>
        </div>
        <a href="{{ route('admin.alunos.index') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Informações pessoais</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $aluno->nome }}</dd>

                        <dt class="col-sm-5">Matrícula</dt>
                        <dd class="col-sm-7">{{ $aluno->matricula }}</dd>

                        <dt class="col-sm-5">E-mail</dt>
                        <dd class="col-sm-7">{{ $aluno->email }}</dd>

                        <dt class="col-sm-5">Telefone</dt>
                        <dd class="col-sm-7">{{ $aluno->telefone ?: '—' }}</dd>

                        <dt class="col-sm-5">Data de nascimento</dt>
                        <dd class="col-sm-7">
                            {{ $aluno->data_nascimento?->translatedFormat('d \\d\\e F \\d\\e Y') ?? '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title">Turma</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $aluno->turma->nome ?? '—' }}</dd>

                        <dt class="col-sm-5">Ano</dt>
                        <dd class="col-sm-7">{{ $aluno->turma?->ano ?? '—' }}</dd>

                        <dt class="col-sm-5">Turno</dt>
                        <dd class="col-sm-7">{{ $aluno->turma?->turno ?? '—' }}</dd>

                        <dt class="col-sm-5">Descrição</dt>
                        <dd class="col-sm-7">{{ $aluno->turma?->descricao ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
