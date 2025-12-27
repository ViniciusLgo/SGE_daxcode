@extends('layouts.app')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex gap-2">
                <i class="bi bi-mortarboard"></i> Alunos
            </h1>
            <p class="text-slate-500 dark:text-slate-400">
                Gerencie os estudantes cadastrados no sistema.
            </p>
        </div>
    </div>

    {{-- BUSCA --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-3 items-end">
        <div class="w-full md:w-80">
            <label class="text-sm font-semibold">Busca</label>
            <input type="search"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Nome, matrícula ou e-mail"
                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900 px-4 py-2.5">
        </div>

        <button class="px-5 py-2.5 rounded-xl border
                   border-dax-green text-dax-green font-bold
                   hover:bg-dax-green hover:text-white transition">
            <i class="bi bi-search"></i> Filtrar
        </button>

        @if($search)
            <a href="{{ route('admin.alunos.index') }}"
               class="px-4 py-2.5 text-slate-500 hover:underline">
                Limpar
            </a>
        @endif
    </form>

    {{-- TABELA --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900">
            <tr class="text-left text-slate-500">
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">Matrícula</th>
                <th class="px-4 py-3">E-mail</th>
                <th class="px-4 py-3">Turma</th>
                <th class="px-4 py-3 text-right">Ações</th>
            </tr>
            </thead>

            <tbody>
            @forelse($alunos as $aluno)
                <tr class="border-t border-slate-200 dark:border-slate-800">
                    <td class="px-4 py-3 font-semibold">
                        {{ $aluno->user->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3">{{ $aluno->matricula ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $aluno->user->email ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $aluno->turma->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.alunos.show', $aluno) }}"
                           class="text-dax-blue hover:underline">Ver</a>

                        <a href="{{ route('admin.alunos.edit', $aluno) }}"
                           class="text-amber-500 hover:underline">Editar</a>

                        <form action="{{ route('admin.alunos.destroy', $aluno) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Excluir este aluno?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                        Nenhum aluno encontrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if($alunos->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $alunos->links() }}
            </div>
        @endif
    </div>

@endsection
