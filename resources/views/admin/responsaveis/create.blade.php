@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4><i class="bi bi-person-plus-fill text-success"></i> Cadastrar Responsável</h4>
        <p class="text-muted">Preencha os dados abaixo para adicionar um novo responsável.</p>
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

    <form action="{{ route('admin.responsaveis.store') }}" method="POST">
        @csrf
        @include('admin.responsaveis._form', ['responsavel' => null])
    </form>
@endsection
