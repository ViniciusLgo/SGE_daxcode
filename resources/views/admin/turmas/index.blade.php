@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Turmas
                </h1>
                <p class="text-slate-500">
                    Gerencie todas as turmas cadastradas.
                </p>
            </div>

            <a href="{{ route('admin.turmas.create') }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white">
                Nova Turma
            </a>
        </div>

        {{-- Busca --}}
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Busca</label>
                <input type="search" name="search"
                       value="{{ $search }}"
                       placeholder="Nome, turno ou descrição"
                       class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
            </div>

            <div class="flex items-end gap-2">
                <button class="px-4 py-2.5 rounded-xl border">
                    Filtrar
                </button>

                @if($search)
                    <a href="{{ route('admin.turmas.index') }}"
                       class="px-4 py-2.5 rounded-xl border">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabela --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 overflow-hidden">

            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-dax-dark">
                <tr>
                    <th class="px-4 py-3 text-left">Nome</th>
                    <th class="px-4 py-3 text-left">Ano</th>
                    <th class="px-4 py-3 text-left">Turno</th>
                    <th class="px-4 py-3 text-left">Alunos</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($turmas as $turma)
                    <tr>
                        <td class="px-4 py-3">{{ $turma->nome }}</td>
                        <td class="px-4 py-3">{{ $turma->ano ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($turma->turno)
                                <span class="px-2 py-1 rounded-full text-xs
                                             bg-slate-200 dark:bg-slate-700">
                                    {{ $turma->turno }}
                                </span>
                            @else — @endif
                        </td>
                        <td class="px-4 py-3">{{ $turma->alunos_count }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.boletins.turmas.show', $turma) }}"
                               class="text-sky-600 hover:underline">
                                Boletim
                            </a>
                            <a href="{{ route('admin.turmas.show', $turma) }}"
                               class="text-slate-600 hover:underline">
                                Detalhes
                            </a>
                            <a href="{{ route('admin.turmas.edit', $turma) }}"
                               class="text-yellow-600 hover:underline">
                                Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.turmas.destroy', $turma) }}"
                                  class="inline"
                                  onsubmit="return confirm('Excluir esta turma removerá vínculos e alunos. Continuar?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-4 py-6 text-center text-slate-500">
                            Nenhuma turma encontrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($turmas->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $turmas->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
