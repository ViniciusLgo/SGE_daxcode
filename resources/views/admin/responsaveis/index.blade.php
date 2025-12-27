@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-people-fill text-dax-yellow"></i>
                Responsáveis
            </h1>
            <p class="text-slate-500">
                Gerencie os responsáveis e seus alunos vinculados.
            </p>
        </div>

        {{-- Busca --}}
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">
                    Buscar por nome ou e-mail
                </label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Ex: Maria, João, email@..."
                    class="w-full rounded-xl border px-4 py-2.5
                       bg-white dark:bg-dax-dark/60
                       border-slate-200 dark:border-slate-800">
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="px-4 py-2.5 rounded-xl bg-dax-green text-white">
                    <i class="bi bi-search"></i> Filtrar
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.responsaveis.index') }}"
                       class="px-4 py-2.5 rounded-xl border
                          border-slate-200 dark:border-slate-800">
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
                <tr class="text-left text-slate-600 dark:text-slate-400">
                    <th class="px-4 py-3">Responsável</th>
                    <th class="px-4 py-3">E-mail</th>
                    <th class="px-4 py-3">Telefone</th>
                    <th class="px-4 py-3">Alunos</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($responsaveis as $r)
                    <tr class="hover:bg-slate-50 dark:hover:bg-dax-dark/80">

                        <td class="px-4 py-3 font-semibold">
                            {{ $r->user->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $r->user->email }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $r->telefone ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1
                                         px-2 py-1 rounded-lg
                                         bg-dax-green/10 text-dax-green">
                                {{ $r->alunos_count }} aluno(s)
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.responsaveis.show', $r) }}"
                               class="text-sky-600 hover:underline">
                                Ver
                            </a>
                            <a href="{{ route('admin.responsaveis.edit', $r) }}"
                               class="text-yellow-600 hover:underline">
                                Editar
                            </a>
                            <form action="{{ route('admin.responsaveis.destroy', $r) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Excluir este responsável?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Excluir
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Nenhum responsável encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if($responsaveis->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $responsaveis->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
