@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-book text-dax-yellow"></i>
                Nova Disciplina
            </h1>
            <p class="text-slate-500">
                Cadastre uma nova disciplina e vincule um ou mais professores responsaveis.
            </p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
                    <strong>Ops!</strong> Corrija os erros abaixo.
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.disciplinas.store') }}" method="POST">
                @csrf

                @include('admin.disciplinas._form')
            </form>

        </div>
    </div>
@endsection
