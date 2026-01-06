@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Painel do Professor
            </h1>
            <p class="text-sm text-slate-500">
                Bem-vindo, {{ auth()->user()->name }} 
            </p>
        </div>
    </div>

    {{-- ================= KPIs ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- ================= DISCIPLINAS ================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="flex items-start justify-between">
                <span class="text-3xl"></span>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    DISCIPLINAS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ \App\Models\Disciplina::whereHas('professores', fn($q) =>
                        $q->where('professores.id', auth()->id())
                    )->count() }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Disciplinas atribuidas
                </p>
            </div>
        </div>

        {{-- ================= TURMAS ================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="flex items-start justify-between">
                <span class="text-3xl"></span>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    TURMAS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ \App\Models\Turma::whereHas('professores', fn($q) =>
                        $q->where('professores.id', auth()->id())
                    )->count() }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Turmas vinculadas
                </p>
            </div>
        </div>

    </div>

@endsection
