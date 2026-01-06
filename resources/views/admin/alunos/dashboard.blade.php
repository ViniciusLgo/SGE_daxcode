@extends('layouts.app')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
            Painel do Aluno
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Ola, {{ auth()->user()->name }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- NOTAS --}}
        <div class="rounded-2xl p-5
                bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                shadow-sm">
            <div class="flex justify-between items-center">
                <span class="text-3xl"></span>
            </div>
            <div class="mt-3 text-xs font-extrabold uppercase text-slate-500">
                Notas
            </div>
            <div class="text-4xl font-black text-dax-green mt-1">
                {{ rand(7,10) }}
            </div>
            <p class="text-sm text-slate-500 mt-1">Media geral</p>
        </div>

        {{-- FREQUENCIA --}}
        <div class="rounded-2xl p-5
                bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                shadow-sm">
            <span class="text-3xl"></span>
            <div class="mt-3 text-xs font-extrabold uppercase text-slate-500">
                Frequencia
            </div>
            <div class="text-4xl font-black text-dax-green mt-1">
                {{ rand(85,100) }}%
            </div>
            <p class="text-sm text-slate-500 mt-1">Presenca media</p>
        </div>

        {{-- TURMA --}}
        <div class="rounded-2xl p-5
                bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800
                shadow-sm">
            <span class="text-3xl"></span>
            <div class="mt-3 text-xs font-extrabold uppercase text-slate-500">
                Turma
            </div>
            <div class="text-2xl font-black text-dax-dark dark:text-dax-light mt-1">
                {{ optional(auth()->user()->turma)->nome ?? '' }}
            </div>
            <p class="text-sm text-slate-500 mt-1">Turma atual</p>
        </div>

    </div>

@endsection
