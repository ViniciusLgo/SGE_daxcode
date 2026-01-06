@extends('layouts.app')

@section('content')

    {{-- Cabecalho --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
            <i class="bi bi-pencil text-dax-green"></i>
            Editar Usuario
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Atualize as informacoes do usuario selecionado.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            rounded-2xl shadow-sm">

        <div class="p-6">

            {{-- Erros --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20
                        border border-red-200 dark:border-red-800
                        px-4 py-3 text-red-700 dark:text-red-300">
                    <strong class="block mb-1">Ops! Corrija os erros abaixo:</strong>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.usuarios._form')

                {{-- Acoes --}}
                <div class="mt-8 flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center gap-2
                               px-6 py-2.5 rounded-xl
                               bg-dax-green text-white font-bold
                               hover:bg-dax-greenSoft transition">
                        <i class="bi bi-save"></i>
                        Salvar Alteracoes
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}"
                       class="inline-flex items-center gap-2
                          px-5 py-2.5 rounded-xl
                          border border-slate-300 dark:border-slate-700
                          text-slate-700 dark:text-slate-300
                          hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <i class="bi bi-arrow-left"></i>
                        Voltar
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection
