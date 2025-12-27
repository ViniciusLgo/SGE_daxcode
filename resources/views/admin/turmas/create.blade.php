@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Nova Turma
            </h1>
            <p class="text-slate-500">
                Cadastre uma nova turma.
            </p>
        </div>

        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
                    <strong>Ops!</strong> Verifique os campos destacados.
                </div>
            @endif

            <form action="{{ route('admin.turmas.store') }}" method="POST">
                @include('admin.turmas._form')
            </form>

        </div>
    </div>
@endsection
