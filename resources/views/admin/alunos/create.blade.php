@extends('layouts.app')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex gap-2">
            <i class="bi bi-mortarboard text-dax-green"></i>
            Cadastrar Aluno
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Preencha os dados para cadastrar um novo aluno.
        </p>
    </div>

    <div class="bg-white dark:bg-dax-dark/60 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.alunos.store') }}" enctype="multipart/form-data">
            @include('admin.alunos._form')
        </form>
    </div>

@endsection
