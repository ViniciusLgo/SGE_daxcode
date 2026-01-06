@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                 Categoria de Despesa
            </h1>
            <p class="text-sm text-slate-500">
                {{ $categoria->nome }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.financeiro.categorias.edit', $categoria) }}"
               class="px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      font-semibold hover:bg-slate-100 dark:hover:bg-slate-800">
                 Editar
            </a>

            <a href="{{ route('admin.financeiro.despesas.create', ['categoria_id' => $categoria->id]) }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold">
                 Nova Despesa
            </a>

            <a href="{{ route('admin.financeiro.categorias.index') }}"
               class="px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700">
                 Voltar
            </a>
        </div>
    </div>

    {{-- ================= DADOS DA CATEGORIA ================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-6 mb-6">

        <h2 class="text-lg font-semibold mb-4 text-dax-dark dark:text-dax-light">
             Dados da Categoria
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-slate-500">Nome</span>
                <p class="font-semibold">{{ $categoria->nome }}</p>
            </div>

            <div>
                <span class="text-slate-500">Total de Despesas</span>
                <p class="font-semibold">
                    {{ $categoria->despesas->count() }}
                </p>
            </div>

            <div class="md:col-span-2">
                <span class="text-slate-500">Descricao</span>
                <p class="mt-1">
                    {{ $categoria->descricao ?? '' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ================= KPIs ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Total --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-5">
            <p class="text-sm text-slate-500">Total gasto</p>
            <h3 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                R$ {{ number_format($categoria->despesas->sum('valor'), 2, ',', '.') }}
            </h3>
        </div>

        {{-- Media --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-5">
            <p class="text-sm text-slate-500">Media por despesa</p>
            <h3 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                R$ {{ number_format($categoria->despesas->avg('valor') ?? 0, 2, ',', '.') }}
            </h3>
        </div>

        {{-- Ultima despesa --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-5">
            <p class="text-sm text-slate-500">Ultima despesa</p>
            <h3 class="text-lg font-semibold text-dax-dark dark:text-dax-light">
                {{ optional($categoria->despesas->sortByDesc('data')->first())->data?->format('d/m/Y') ?? '' }}
            </h3>
        </div>

    </div>

    {{-- ================= DESPESAS DA CATEGORIA ================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-lg font-semibold text-dax-dark dark:text-dax-light">
                 Despesas Vinculadas
            </h2>
        </div>

        @if($categoria->despesas->isEmpty())
            <div class="p-6 text-slate-500">
                Nenhuma despesa cadastrada para esta categoria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/40 text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">Data</th>
                        <th class="px-4 py-3 text-left">Descricao</th>
                        <th class="px-4 py-3 text-left">Centro</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Valor</th>
                        <th class="px-4 py-3 text-right">Acoes</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @foreach($categoria->despesas as $despesa)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">

                            <td class="px-4 py-3">
                                {{ $despesa->data->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $despesa->descricao ?? '' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $despesa->centroCusto->nome ?? '' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($despesa->status_pagamento === 'pago')
                                        bg-emerald-100 text-emerald-700
                                    @elseif($despesa->status_pagamento === 'pendente')
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-slate-200 text-slate-700
                                    @endif">
                                    {{ ucfirst(str_replace('_',' ', $despesa->status_pagamento)) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right font-semibold">
                                R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.financeiro.despesas.edit', $despesa) }}"
                                   class="text-blue-600 font-semibold hover:underline">
                                    Editar
                                </a>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
