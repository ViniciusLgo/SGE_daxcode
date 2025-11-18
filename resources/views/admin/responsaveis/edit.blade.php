@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4 class="mb-1">
            <i class="bi bi-pencil-square text-warning"></i>
            Editar Responsável
        </h4>
        <p class="text-muted">Atualize os dados cadastrados e os vínculos do responsável.</p>
    </div>

    {{-- ALERTAS --}}
    @include('partials.alerts')

    <form action="{{ route('admin.responsaveis.update', $responsavel->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Reaproveita o form estruturado --}}
        @include('admin.responsaveis._form')

    </form>

@endsection
