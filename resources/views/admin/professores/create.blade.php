@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-plus-fill text-success me-2"></i> Novo Professor
            </h4>
            <p class="text-muted mb-0">Preencha os dados para cadastrar um novo professor.</p>
        </div>
        <a href="{{ route('admin.professores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Exibe mensagens de sucesso ou erro --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulário de cadastro --}}
            <form action="{{ route('admin.professores.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome</label>
                    <input type="text" name="nome" class="form-control shadow-sm"
                           value="{{ old('nome') }}" required placeholder="Ex: Maria Santos">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">E-mail</label>
                    <input type="email" name="email" class="form-control shadow-sm"
                           value="{{ old('email') }}" required placeholder="Ex: maria@escola.com">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Telefone</label>
                    <input type="text" name="telefone" class="form-control shadow-sm"
                           value="{{ old('telefone') }}" placeholder="(71) 99999-9999">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Especialização</label>
                    <input type="text" name="especializacao" class="form-control shadow-sm"
                           value="{{ old('especializacao') }}" placeholder="Ex: Matemática, Física...">
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Salvar Professor
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
