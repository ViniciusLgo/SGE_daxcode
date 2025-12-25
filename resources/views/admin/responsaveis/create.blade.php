@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">
            <i class="bi bi-people"></i> Completar Cadastro do Responsável
        </h4>
        <p class="text-muted mb-0">
            Usuário criado: <strong>{{ $user->name }}</strong> ({{ $user->email }})
        </p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.responsaveis.store') }}">
                @csrf

                {{-- user_id vindo do redirect --}}
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Telefone</label>
                        <input type="text" name="telefone"
                               class="form-control"
                               value="{{ old('telefone') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CPF</label>
                        <input type="text" name="cpf"
                               class="form-control"
                               placeholder="000.000.000-00"
                               value="{{ old('cpf') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Grau de Parentesco</label>
                        <input type="text" name="grau_parentesco"
                               class="form-control"
                               placeholder="Pai, Mãe, Responsável legal..."
                               value="{{ old('grau_parentesco') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Vincular Aluno(s)</label>
                        <select name="alunos[]" class="form-select" multiple>
                            @foreach($alunos as $aluno)
                                <option value="{{ $aluno->id }}">
                                    {{ $aluno->user->name }} — {{ $aluno->turma->nome ?? 'Sem turma' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Segure CTRL para selecionar mais de um aluno
                        </small>
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Finalizar Cadastro
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}"
                       class="btn btn-outline-secondary ms-2">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>
@endsection
