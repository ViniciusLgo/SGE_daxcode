@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    📅 Registro de Aulas
                </h1>
                <p class="text-sm text-slate-500">
                    Linha do tempo acadêmica de aulas, reuniões, eventos e formações
                    <span class="block text-xs mt-1">
                    ⏱️ Cálculo baseado em <strong>hora-aula (50 minutos)</strong>
                </span>
                </p>
            </div>

            <a href="{{ route('admin.aulas.create') }}"
               class="inline-flex items-center gap-2
                  px-4 py-2 rounded-xl
                  bg-dax-green text-white font-semibold
                  hover:bg-dax-greenSoft transition">
                ➕ Nova Aula
            </a>
        </div>

        {{-- ================= FILTROS ================= --}}
        <form method="GET"
              class="grid grid-cols-1 md:grid-cols-6 gap-4
                 bg-white dark:bg-dax-dark/60
                 border border-slate-200 dark:border-slate-800
                 rounded-2xl p-5">

            {{-- Data --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Data</label>
                <input type="text"
                       id="filtro_data"
                       name="data"
                       placeholder="DD/MM/AAAA"
                       value="{{ request('data') }}"
                       class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark">
            </div>

            {{-- Professor --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Professor</label>
                <select name="professor_id"
                        class="w-full rounded-xl border px-4 py-2.5
                           bg-white dark:bg-dax-dark">
                    <option value="">Todos</option>
                    @foreach($professores as $prof)
                        <option value="{{ $prof->id }}"
                            {{ request('professor_id') == $prof->id ? 'selected' : '' }}>
                            {{ $prof->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Tipo</label>
                <select name="tipo"
                        class="w-full rounded-xl border px-4 py-2.5
                           bg-white dark:bg-dax-dark">
                    <option value="">Todos</option>
                    @foreach(['aula','reuniao','evento','formacao'] as $t)
                        <option value="{{ $t }}"
                            {{ request('tipo') === $t ? 'selected' : '' }}>
                            {{ ucfirst($t) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Turma (preparado para filtro avançado) --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Turma</label>
                <input type="text"
                       name="turma"
                       placeholder="Ex: TI25"
                       value="{{ request('turma') }}"
                       class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark">
            </div>

            {{-- Ordenação --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Ordenação</label>
                <select name="ordem"
                        class="w-full rounded-xl border px-4 py-2.5
                           bg-white dark:bg-dax-dark">
                    <option value="asc" {{ request('ordem','asc') === 'asc' ? 'selected' : '' }}>
                        Mais antigas primeiro
                    </option>
                    <option value="desc" {{ request('ordem') === 'desc' ? 'selected' : '' }}>
                        Mais recentes primeiro
                    </option>
                </select>
            </div>

            {{-- Ações --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl border font-semibold
                           hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Filtrar
                </button>

                @if(request()->query())
                    <a href="{{ route('admin.aulas.index') }}"
                       class="px-4 py-2.5 rounded-xl border text-slate-500">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        {{-- ================= LISTAGEM ================= --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                overflow-hidden">

            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Data</th>
                    <th class="px-4 py-3 text-left">Professor</th>
                    <th class="px-4 py-3 text-left">Turma</th>
                    <th class="px-4 py-3 text-left">Disciplina</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-center">
                        Hora-aula
                        <div class="text-xs font-normal">(1 h/a = 50 min)</div>
                    </th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($aulas as $aula)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">

                        {{-- Data --}}
                        <td class="px-4 py-3">
                            <div class="font-semibold">
                                {{ $aula->data->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $aula->hora_inicio }} → {{ $aula->hora_fim }}
                            </div>
                        </td>

                        {{-- Professor --}}
                        <td class="px-4 py-3 font-semibold">
                            {{ $aula->professor->user->name }}
                        </td>

                        {{-- Turma --}}
                        <td class="px-4 py-3">
                            {{ $aula->turma->nome }}
                        </td>

                        {{-- Disciplina --}}
                        <td class="px-4 py-3">
                            {{ $aula->disciplina->nome }}
                        </td>

                        {{-- Tipo --}}
                        <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 rounded-full
                                     text-xs font-semibold
                                     bg-slate-200 dark:bg-slate-700">
                            {{ ucfirst($aula->tipo) }}
                        </span>
                        </td>

                        {{-- Hora-aula --}}
                        <td class="px-4 py-3 text-center font-bold">
                            {{ $aula->quantidade_blocos }} h/a
                        </td>

                        {{-- Ações --}}
                        <td class="px-4 py-3 text-right space-x-3 font-semibold">
                            <a href="{{ route('admin.aulas.show', $aula) }}"
                               class="text-sky-600 hover:underline">
                                Ver
                            </a>
                            <a href="{{ route('admin.aulas.edit', $aula) }}"
                               class="text-amber-600 hover:underline">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-4 py-8 text-center text-slate-500">
                            Nenhuma aula registrada até o momento.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Paginação --}}
            @if($aulas->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $aulas->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Flatpickr filtro --}}
    <script>
        flatpickr("#filtro_data", {
            dateFormat: "d/m/Y",
            locale: "pt",
            allowInput: true
        });
    </script>
@endsection
