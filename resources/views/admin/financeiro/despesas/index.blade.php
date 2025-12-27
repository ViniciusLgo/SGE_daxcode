@extends('layouts.app')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                💰 Despesas do Projeto Social
            </h1>
            <p class="text-sm text-slate-500">
                Lançamentos de gastos por categoria e centro de custo.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.despesas.create') }}"
           class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
            + Nova Despesa
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl
            bg-emerald-100 text-emerald-700
            dark:bg-emerald-900/40 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTROS --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6 mb-6">

        @php
            $filtrosAtivos = request()->anyFilled([
                'data_inicio','data_fim','categoria_id','centro_custo_id',
                'forma_pagamento','status_pagamento','responsavel_id',
                'fornecedor','valor_min','valor_max'
            ]);
        @endphp

        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                {{-- Data início --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Data início</label>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                           class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- Data fim --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Data fim</label>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                           class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- Categoria --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Categoria</label>
                    <select name="categoria_id"
                            class="w-full rounded-xl px-4 py-2.5 bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value="">Todas</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" @selected(request('categoria_id') == $c->id)>
                                {{ $c->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botão --}}
                <div class="flex items-end">
                    <button class="w-full px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
                        Filtrar
                    </button>
                </div>

            </div>

            {{-- FILTROS AVANÇADOS --}}
            @if($filtrosAtivos)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mt-6">
                    {{-- Centro --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Centro de custo</label>
                        <select name="centro_custo_id" class="w-full rounded-xl px-4 py-2.5 border">
                            <option value="">Todos</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id }}" @selected(request('centro_custo_id') == $centro->id)>
                                    {{ $centro->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Forma --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Forma pagamento</label>
                        <select name="forma_pagamento" class="w-full rounded-xl px-4 py-2.5 border">
                            <option value="">Todas</option>
                            @foreach(['pix','dinheiro','transferencia','cartao','outros'] as $fp)
                                <option value="{{ $fp }}" @selected(request('forma_pagamento')==$fp)>
                                    {{ ucfirst($fp) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fornecedor --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Fornecedor</label>
                        <input type="text" name="fornecedor" value="{{ request('fornecedor') }}"
                               class="w-full rounded-xl px-4 py-2.5 border">
                    </div>

                    {{-- Limpar --}}
                    <div class="flex items-end">
                        <a href="{{ route('admin.financeiro.despesas.index') }}"
                           class="w-full px-4 py-2 rounded-xl border text-center">
                            Limpar filtros
                        </a>
                    </div>
                </div>
            @endif
        </form>
    </div>

    {{-- TABELA --}}
    <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/40">
            <tr class="text-slate-600 dark:text-slate-300">
                <th class="px-4 py-3">Data</th>
                <th class="px-4 py-3">Categoria</th>
                <th class="px-4 py-3">Centro</th>
                <th class="px-4 py-3 text-right">Valor</th>
                <th class="px-4 py-3">Responsável</th>
                <th class="px-4 py-3 text-right">Ações</th>
            </tr>
            </thead>

            <tbody>
            @forelse($despesas as $despesa)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($despesa->data)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $despesa->categoria?->nome }}</td>
                    <td class="px-4 py-3">{{ $despesa->centroCusto?->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-right font-semibold">
                        R$ {{ number_format($despesa->valor,2,',','.') }}
                    </td>
                    <td class="px-4 py-3">{{ $despesa->user?->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.financeiro.despesas.edit',$despesa) }}"
                           class="text-blue-600 font-semibold">Editar</a>

                        <form action="{{ route('admin.financeiro.despesas.destroy',$despesa) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Excluir esta despesa?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 font-semibold">Excluir</button>
                        </form>

                        @if($despesa->arquivo)
                            <a href="{{ asset('storage/'.$despesa->arquivo) }}"
                               target="_blank"
                               class="text-slate-500 font-semibold">
                                Comprovante
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        Nenhuma despesa lançada ainda.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $despesas->links() }}
    </div>

@endsection
