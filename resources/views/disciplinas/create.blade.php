@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">Nova disciplina</h4>
        <p class="text-muted mb-0">Associe a disciplina a um professor responsável.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Verifique os campos destacados.
                </div>
            @endif

            <form action="{{ route('admin.disciplinas.store') }}" method="POST" class="needs-validation" novalidate>
                @include('disciplinas._form')
            </form>
        </div>
    </div>
@endsection
