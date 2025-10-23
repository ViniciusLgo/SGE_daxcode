@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">➕ Novo Vínculo</h4>
        <p class="text-muted mb-0">Associe uma disciplina a uma turma e professor.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.disciplina_turma.store') }}">
                @csrf
                @include('admin.disciplina_turma._form', ['buttonText' => 'Salvar Vínculo'])
            </form>
        </div>
    </div>
@endsection
