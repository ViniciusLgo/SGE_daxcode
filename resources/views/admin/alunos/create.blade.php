@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-person-plus text-primary"></i> Novo Aluno</h4>
        <p class="text-muted mb-0">Preencha as informações para cadastrar um novo estudante.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo antes de continuar.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


                <form action="{{ route('admin.alunos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do Aluno</label>
                            <input type="text" name="nome" class="form-control" required placeholder="Nome completo" value="{{ old('nome') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" value="{{ old('email') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000" value="{{ old('telefone') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Matrícula</label>
                            <input type="text" name="matricula" class="form-control" required placeholder="Ex: 2025A001" value="{{ old('matricula') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Turma</label>
                            <select name="turma_id" class="form-select" required>
                                <option value="">Selecione uma turma</option>
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                        {{ $turma->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> Salvar Aluno
                        </button>
                        <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary px-3">Voltar</a>
                    </div>
                </form>





        </div>
    </div>
@endsection
