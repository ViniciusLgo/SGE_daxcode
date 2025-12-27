@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                🧮 Centros de Custo
            </h1>
            <p class="text-sm text-slate-500">
                Agrupe despesas por projeto, turma ou atividade.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.centros.create') }}"
           class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold
              hover:bg-dax-greenSoft transition">
            + Novo Centro
        </a>
    </div>

    {{-- ================= FEEDBACK ================= --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-300 dark:border-emerald-700
                bg-emerald-50 dark:bg-emerald-900/30 p-4
                text-dax-dark dark:text-dax-light">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= CONTEÚDO ================= --}}
    @if($centros->count() === 0)

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-6
                text-slate-500">
            Nenhum centro de custo cadastrado ainda.
        </div>

    @else

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-800">

                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                    <tr class="text-left font-semibold text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-3 w-16">#</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Descrição</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($centros as $centro)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">

                            <td class="px-4 py-3">
                                {{ $centro->id }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-dax-dark dark:text-dax-light">
                                {{ $centro->nome }}
                            </td>

                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                {{ $centro->descricao ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-right space-x-3">

                                <a href="{{ route('admin.financeiro.centros.edit', $centro) }}"
                                   class="text-sm font-semibold text-blue-600 hover:underline">
                                    Editar
                                </a>

                                <form action="{{ route('admin.financeiro.centros.destroy', $centro) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Deseja realmente excluir este centro de custo?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-sm font-semibold text-red-600 hover:underline">
                                        Excluir
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    @endif

@endsection
