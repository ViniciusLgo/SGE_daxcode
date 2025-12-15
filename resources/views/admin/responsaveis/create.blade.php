@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4 class="mb-1">
            <i class="bi bi-person-plus-fill text-success"></i>
            Cadastrar Responsável
        </h4>
        <p class="text-muted">Preencha os dados abaixo para adicionar um novo responsável.</p>
    </div>

    {{-- ALERTAS DE ERRO --}}
    @include('partials.alerts')

    <form action="{{ route('admin.responsaveis.store') }}" method="POST">
        @csrf

        {{-- Formulário unificado --}}
        @include('admin.responsaveis._form', ['responsavel' => null])

    </form>

@endsection
