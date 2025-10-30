@extends('layouts.app')

@section('content')
    <h4 class="mb-3"><i class="bi bi-person-plus-fill text-success"></i> Cadastrar Responsável</h4>

    <form action="{{ route('admin.responsaveis.store') }}" method="POST">
        @csrf
        @include('admin.responsaveis._form', ['responsavel' => null])
    </form>
@endsection
