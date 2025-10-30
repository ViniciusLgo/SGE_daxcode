@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 75vh;">
        <div class="card shadow-sm border-0" style="max-width: 500px; width: 100%;">
            <div class="card-header bg-dark text-white text-center fw-semibold">
                🔒 Primeiro Acesso - Alterar Senha
            </div>
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ops!</strong> Corrija os erros abaixo:
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('auth.first_access.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nova Senha</label>
                        <input type="password" name="password" class="form-control shadow-sm" required minlength="6">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" class="form-control shadow-sm" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-check-circle"></i> Salvar e Entrar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
