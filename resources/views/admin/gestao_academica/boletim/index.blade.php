@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
            📘 Boletins
        </h1>
        <p class="text-sm text-slate-500">
            Consulta e visualização de boletins escolares.
        </p>
    </div>

    {{-- ================= GRID ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- BOLETIM POR TURMA --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-6
                hover:shadow-md transition">

            <h2 class="text-lg font-black text-dax-dark dark:text-dax-light mb-2">
                📘 Boletim por Turma
            </h2>

            <p class="text-sm text-slate-500 mb-6">
                Visualize o boletim consolidado dos alunos organizados por turma,
                com médias, avaliações e situação geral.
            </p>

            <a href="{{ route('admin.turmas.index') }}"
               class="inline-flex items-center gap-2
                  px-4 py-2 rounded-xl
                  border border-slate-300 dark:border-slate-700
                  text-dax-dark dark:text-dax-light
                  hover:bg-slate-100 dark:hover:bg-slate-800
                  font-semibold">
                Acessar Turmas
            </a>
        </div>

        {{-- BOLETIM POR ALUNO --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-6
                hover:shadow-md transition">

            <h2 class="text-lg font-black text-dax-dark dark:text-dax-light mb-2">
                👤 Boletim por Aluno
            </h2>

            <p class="text-sm text-slate-500 mb-6">
                Acesse o boletim individual diretamente pelo cadastro do aluno,
                com histórico completo de avaliações e resultados.
            </p>

            <a href="{{ route('admin.alunos.index') }}"
               class="inline-flex items-center gap-2
                  px-4 py-2 rounded-xl
                  border border-slate-300 dark:border-slate-700
                  text-dax-dark dark:text-dax-light
                  hover:bg-slate-100 dark:hover:bg-slate-800
                  font-semibold">
                Ir para Alunos
            </a>
        </div>

    </div>

@endsection
