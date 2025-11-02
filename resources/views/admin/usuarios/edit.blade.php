@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h4><i class="bi bi-pencil"></i> Editar Usuário</h4>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                    @method('PUT')
                    @include('admin.usuarios._form')
                    <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mt-3">Voltar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
