@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-person-badge-fill text-dax-yellow"></i>
                Professores
            </h1>
            <p class="text-slate-500">
                Gerencie os professores e suas disciplinas vinculadas.
            </p>
        </div>

        {{-- Filtro / Busca --}}
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Busca</label>
                <input
                    type="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Nome ou e-mail"
                    class="w-full rounded-xl border px-4 py-2.5
                       bg-white dark:bg-dax-dark/60
                       border-slate-200 dark:border-slate-800">
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="px-4 py-2.5 rounded-xl bg-dax-green text-white hover:bg-dax-greenSoft transition">
                    <i class="bi bi-search"></i> Filtrar
                </button>

                @if($search)
                    <a href="{{ route('admin.professores.index') }}"
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
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">E-mail</th>
                    <th class="px-4 py-3">Disciplinas</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($professores as $prof)
                    <tr class="hover:bg-slate-50 dark:hover:bg-dax-dark/80">
                        <td class="px-4 py-3">{{ $prof->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $prof->user->email ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $prof->disciplinas_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.professores.show', $prof) }}"
                               class="text-sky-600 hover:underline">
                                Ver
                            </a>
                            <a href="{{ route('admin.professores.edit', $prof) }}"
                               class="text-yellow-600 hover:underline">
                                Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.professores.destroy', $prof) }}"
                                  class="inline"
                                  onsubmit="return confirm('Deseja excluir este professor?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                            Nenhum professor encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Paginação --}}
            @if($professores->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $professores->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
