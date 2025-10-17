@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">Novo professor</h4>
        <p class="text-muted mb-0">Cadastre um novo professor e defina seus dados principais.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Verifique os campos destacados.
                </div>
            @endif

            <form action="{{ route('admin.professores.store') }}" method="POST" class="needs-validation" novalidate>
                @include('professores._form')
            </form>
        </div>
    </div>
@endsection
