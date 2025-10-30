@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4><i class="bi bi-pencil-square text-warning"></i> Editar Responsável</h4>
        <p class="text-muted">Atualize as informações do responsável.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Corrija os erros abaixo.
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.responsaveis.update', $responsavel->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.responsaveis._form')
    </form>
@endsection
