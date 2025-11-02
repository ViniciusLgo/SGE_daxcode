@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-pencil-square text-warning me-2"></i> Editar Professor
            </h4>
            <p class="text-muted mb-0">Atualize as informações deste professor.</p>
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

            {{-- Formulário de edição --}}
            <form action="{{ route('admin.professores.update', $professor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome</label>
                    <input type="text" name="nome" class="form-control shadow-sm"
                           value="{{ old('nome', $professor->user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">E-mail</label>
                    <input type="email" name="email" class="form-control shadow-sm"
                           value="{{ old('email', $professor->user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Telefone</label>
                    <input type="text" name="telefone" class="form-control shadow-sm"
                           value="{{ old('telefone', $professor->telefone) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Especialização</label>
                    <input type="text" name="especializacao" class="form-control shadow-sm"
                           value="{{ old('especializacao', $professor->especializacao) }}">
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.professores.show', $professor->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
