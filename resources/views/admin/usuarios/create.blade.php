@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-person-plus-fill text-dark"></i> Cadastrar Novo Usuário</h4>
        <p class="text-muted mb-0">Preencha as informações abaixo para criar um novo acesso.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="mt-2 mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" name="name" class="form-control shadow-sm" placeholder="Nome completo" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control shadow-sm" placeholder="exemplo@email.com" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Senha</label>
                        <input type="password" name="password" class="form-control shadow-sm" placeholder="Mínimo 6 caracteres" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de Usuário</label>
                        <select name="tipo" class="form-select shadow-sm" required>
                            <option value="admin">Administrador</option>
                            <option value="professor">Professor</option>
                            <option value="aluno">Aluno</option>
                            <option value="responsavel">Responsável</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-dark px-4">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
