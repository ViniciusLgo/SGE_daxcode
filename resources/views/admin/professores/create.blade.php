@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h4 class="mb-1">
        <i class="bi bi-person-video3"></i> Completar Cadastro do Professor
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

        <form method="POST"
              action="{{ route('admin.professores.store') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- user_id vindo do redirect --}}
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Telefone</label>
                    <input type="text" name="telefone"
                           class="form-control"
                           placeholder="(00) 00000-0000"
                           value="{{ old('telefone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Especialização</label>
                    <input type="text" name="especializacao"
                           class="form-control"
                           placeholder="Ex: Matemática, História..."
                           value="{{ old('especializacao') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Foto do Perfil</label>
                    <input type="file" name="foto_perfil"
                           class="form-control">
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
