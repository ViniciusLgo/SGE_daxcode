@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                📘 Avaliações
            </h1>
            <p class="text-sm text-slate-500">
                Gestão de avaliações por turma e disciplina.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-dax-green text-white font-semibold
                  hover:bg-dax-greenSoft transition">
            ➕ Nova Avaliação
        </a>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 overflow-hidden">

        {{-- ================= TABELA ================= --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/40">
                <tr class="text-left text-sm font-semibold text-slate-600 dark:text-slate-300">
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Turma</th>
                    <th class="px-4 py-3">Disciplina</th>
                    <th class="px-4 py-3 text-center">Tipo</th>
                    <th class="px-4 py-3 text-center">Data</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($avaliacoes as $avaliacao)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">

                        {{-- Título --}}
                        <td class="px-4 py-3 font-semibold text-dax-dark dark:text-dax-light">
                            {{ $avaliacao->titulo }}
                        </td>

                        {{-- Turma --}}
                        <td class="px-4 py-3">
                            {{ $avaliacao->turma->nome ?? '—' }}
                        </td>

                        {{-- Disciplina --}}
                        <td class="px-4 py-3">
                            {{ $avaliacao->disciplina->nome ?? '—' }}
                        </td>

                        {{-- Tipo --}}
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                         bg-sky-100 text-sky-700
                                         dark:bg-sky-900/40 dark:text-sky-300">
                                {{ ucfirst($avaliacao->tipo) }}
                            </span>
                        </td>

                        {{-- Data --}}
                        <td class="px-4 py-3 text-center">
                            {{ \Carbon\Carbon::parse($avaliacao->data_avaliacao)->format('d/m/Y') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            @if($avaliacao->status === 'encerrada')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                             bg-slate-200 text-slate-700
                                             dark:bg-slate-700 dark:text-slate-200">
                                    Encerrada
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                             bg-emerald-100 text-emerald-700
                                             dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Aberta
                                </span>
                            @endif
                        </td>

                        {{-- Ações --}}
                        <td class="px-4 py-3 text-right space-x-2">

                            {{-- Editar --}}
                            <a href="{{ route('admin.gestao_academica.avaliacoes.edit', $avaliacao) }}"
                               class="text-sm font-semibold text-blue-600 hover:underline">
                                Editar
                            </a>

                            {{-- Resultados --}}
                            <a href="{{ route('admin.gestao_academica.avaliacoes.resultados.index', $avaliacao) }}"
                               class="text-sm font-semibold
                                   {{ $avaliacao->status === 'aberta'
                                       ? 'text-emerald-600 hover:underline'
                                       : 'text-slate-500 hover:underline' }}">
                                {{ $avaliacao->status === 'aberta' ? 'Lançar' : 'Ver' }}
                            </a>

                            {{-- Reabrir --}}
                            @if($avaliacao->status === 'encerrada')
                                <form action="{{ route('admin.gestao_academica.avaliacoes.reabrir', $avaliacao) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm font-semibold text-emerald-600 hover:underline">
                                        Reabrir
                                    </button>
                                </form>
                            @endif

                            {{-- Encerrar --}}
                            @if($avaliacao->status === 'aberta')
                                <form action="{{ route('admin.gestao_academica.avaliacoes.encerrar', $avaliacao) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="text-sm font-semibold text-yellow-600 hover:underline"
                                        onclick="return confirm('Deseja encerrar esta avaliação?')">
                                        Encerrar
                                    </button>
                                </form>
                            @endif

                            {{-- Excluir --}}
                            <form action="{{ route('admin.gestao_academica.avaliacoes.destroy', $avaliacao) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="text-sm font-semibold text-red-600 hover:underline"
                                    onclick="return confirm('Deseja excluir esta avaliação?')">
                                    Excluir
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-8 text-center text-slate-500">
                            Nenhuma avaliação cadastrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= PAGINAÇÃO ================= --}}
        @if(method_exists($avaliacoes, 'links'))
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-800">
                {{ $avaliacoes->links() }}
            </div>
        @endif

    </div>

@endsection
