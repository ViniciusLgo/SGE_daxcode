@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">Editar turma</h4>
        <p class="text-muted mb-0">Atualize as informações da turma selecionada.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Verifique os campos destacados.
                </div>
            @endif

            <form action="{{ route('admin.turmas.update', $turma) }}" method="POST" class="needs-validation" novalidate>
                @method('PUT')
                @include('turmas._form')
            </form>
        </div>
    </div>
@endsection
