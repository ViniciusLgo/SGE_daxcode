@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-book text-primary"></i> Nova Disciplina</h4>
        <p class="text-muted mb-0">Cadastre uma nova disciplina e vincule um ou mais professores responsáveis.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <form action="{{ route('admin.disciplinas.store') }}" method="POST">
                    @csrf
                    @include('disciplinas._form')
                </form>

        </div>
    </div>
@endsection
