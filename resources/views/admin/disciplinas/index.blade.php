@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                    <i class="bi bi-journal-bookmark-fill text-dax-yellow"></i>
                    Disciplinas
                </h1>
                <p class="text-slate-500">
                    Gerencie as disciplinas e seus vínculos com professores e turmas.
                </p>
            </div>

            <a href="{{ route('admin.disciplinas.create') }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white">
                <i class="bi bi-plus-circle"></i> Nova Disciplina
            </a>
        </div>

        {{-- Busca --}}
        <form method="GET" action="{{ route('admin.disciplinas.index') }}"
              class="flex gap-2 max-w-md">
            <input type="text" name="search"
                   placeholder="Buscar disciplina..."
                   value="{{ $search ?? '' }}"
                   class="w-full rounded-xl border px-4 py-2.5
                      bg-white dark:bg-dax-dark/60
                      border-slate-200 dark:border-slate-800">
            <button class="px-4 py-2.5 rounded-xl border
                       border-slate-200 dark:border-slate-800">
                <i class="bi bi-search"></i>
            </button>
        </form>

        {{-- Tabela --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 overflow-hidden">

            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-dax-dark">
                <tr class="text-left text-slate-600 dark:text-slate-400">
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Carga Horária</th>
                    <th class="px-4 py-3">Professores</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($disciplinas as $disciplina)
                    <tr class="hover:bg-slate-50 dark:hover:bg-dax-dark/80">
                        <td class="px-4 py-3">{{ $disciplina->nome }}</td>
                        <td class="px-4 py-3">
                            {{ $disciplina->carga_horaria ? $disciplina->carga_horaria.'h' : '—' }}
                        </td>
                        <td class="px-4 py-3 space-x-1">
                            @forelse ($disciplina->professores as $prof)
                                <span class="inline-flex items-center px-2 py-1 rounded-full
                                             bg-slate-200 dark:bg-slate-700 text-xs">
                                    {{ $prof->nome }}
                                </span>
                            @empty
                                <span class="text-slate-500 text-sm">Nenhum</span>
                            @endforelse
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.disciplinas.show', $disciplina) }}"
                               class="text-sky-600 hover:underline">
                                Ver
                            </a>
                            <a href="{{ route('admin.disciplinas.edit', $disciplina) }}"
                               class="text-yellow-600 hover:underline">
                                Editar
                            </a>
                            <form action="{{ route('admin.disciplinas.destroy', $disciplina) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Deseja excluir esta disciplina?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-4 py-6 text-center text-slate-500">
                            Nenhuma disciplina cadastrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            @if ($disciplinas->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $disciplinas->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
