@extends('layouts.app')

@section('content')

    @php
        $responsavel = auth()->user()->responsavel;
        $filhos = $responsavel->alunos()->with(['user','turma'])->get();
    @endphp

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
            Painel do Responsável
        </h1>
        <p class="text-sm text-slate-500">
            Bem-vindo, {{ auth()->user()->name }} — aqui você acompanha seus filhos e registros escolares.
        </p>
    </div>

    {{-- ================= KPIs ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        {{-- FILHOS --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="text-3xl">🎓</div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    FILHOS VINCULADOS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $filhos->count() }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Total de alunos sob sua responsabilidade
                </p>
            </div>
        </div>

        {{-- TURMAS --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="text-3xl">🏫</div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    TURMAS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $filhos->pluck('turma_id')->unique()->count() }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Total de turmas diferentes
                </p>
            </div>
        </div>

        {{-- REGISTROS --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="text-3xl">📄</div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    REGISTROS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ \App\Models\AlunoRegistro::whereIn('aluno_id', $filhos->pluck('id'))->count() }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Atestados, ocorrências ou documentos
                </p>
            </div>
        </div>

    </div>

    {{-- ================= LISTA DE FILHOS ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="font-semibold text-dax-dark dark:text-dax-light">
                👦👧 Filhos vinculados
            </h2>
        </div>

        <div class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($filhos as $aluno)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-dax-dark dark:text-dax-light">
                            {{ $aluno->user->name }}
                        </p>
                        <p class="text-sm text-slate-500">
                            Turma: {{ $aluno->turma->nome ?? 'Sem turma' }}
                        </p>
                    </div>

                    <a href="{{ route('admin.alunos.show', $aluno->id) }}"
                       class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                              text-sm font-semibold text-dax-dark dark:text-dax-light
                              hover:bg-slate-100 dark:hover:bg-dax-dark/80 transition">
                        Ver detalhes
                    </a>
                </div>
            @empty
                <div class="px-6 py-6 text-center text-slate-500">
                    Nenhum aluno vinculado.
                </div>
            @endforelse
        </div>

    </div>

@endsection
