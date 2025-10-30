@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Editar Professor</h4>

        @if ($errors->any())
            <div class="alert alert-danger">Verifique os campos abaixo.</div>
        @endif

        <form action="{{ route('admin.professores.update', $professor) }}" method="POST">
            @csrf
            @method('PUT')

            @include('professores._form')

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
@endsection
