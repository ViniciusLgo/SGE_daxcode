@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4><i class="bi bi-mortarboard"></i> Completar Cadastro do Aluno</h4>
        <p class="text-muted">
            Usuário: <strong>{{ $user->name }}</strong> ({{ $user->email }})
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.alunos.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->id }}">


                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Turma</label>
                        <select name="turma_id" class="form-select" required>
                            <option value="">Selecione a turma</option>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}">
                                    {{ $turma->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto_perfil" class="form-control">
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-success">
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
