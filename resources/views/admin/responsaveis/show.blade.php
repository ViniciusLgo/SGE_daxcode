@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4 class="mb-1">
            <i class="bi bi-person-vcard text-primary"></i>
            Detalhes do Responsável
        </h4>
        <p class="text-muted">Informações completas e alunos vinculados.</p>
    </div>

    <div class="card shadow-sm border-0 p-4">

        {{-- ========================== --}}
        {{-- DADOS DO RESPONSÁVEL --}}
        {{-- ========================== --}}
        <h5 class="fw-bold mb-3">
            <i class="bi bi-person-fill text-primary"></i> Informações Pessoais
        </h5>

        <div class="row g-3">

            <div class="col-md-6">
                <strong>Nome:</strong> {{ $responsavel->user->name }}
            </div>

            <div class="col-md-6">
                <strong>E-mail:</strong> {{ $responsavel->user->email }}
            </div>

            <div class="col-md-6">
                <strong>Telefone:</strong> {{ $responsavel->telefone ?? '—' }}
            </div>

            <div class="col-md-6">
                <strong>CPF:</strong> {{ $responsavel->cpf ?? '—' }}
            </div>

            <div class="col-md-6">
                <strong>Grau de Parentesco:</strong> {{ $responsavel->grau_parentesco ?? '—' }}
            </div>

        </div>

        <hr class="my-4">

        {{-- ========================== --}}
        {{-- ALUNOS VINCULADOS --}}
        {{-- ========================== --}}
        <h5 class="fw-bold mb-3">
            <i class="bi bi-people-fill text-success"></i> Alunos Vinculados
        </h5>

        @if($responsavel->alunos->isEmpty())
            <p class="text-muted">Nenhum aluno vinculado.</p>
        @else
            <div class="list-group">

                @foreach($responsavel->alunos as $aluno)

                    <div class="list-group-item d-flex justify-content-between align-items-center">

                        <div>
                            <strong>{{ $aluno->user->name }}</strong><br>
                            <span class="text-muted">
                                Turma: {{ $aluno->turma->nome ?? 'Sem turma' }}
                            </span>
                        </div>

                        <a href="{{ route('admin.alunos.show', $aluno->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            Ver Aluno
                        </a>
                    </div>

                @endforeach

            </div>
        @endif

        {{-- BOTÕES --}}
        <div class="mt-4 text-end">
            <a href="{{ route('admin.responsaveis.edit', $responsavel->id) }}"
               class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>

            <a href="{{ route('admin.responsaveis.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

    </div>
@endsection
