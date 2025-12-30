@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    📊 Carga Horária por Professor
                </h1>
                <p class="text-sm text-slate-500">
                    Relatório mensal de horas-aula e valor financeiro estimado.
                </p>
            </div>
        </div>

        {{-- FILTRO --}}
        <form method="GET"
              class="flex flex-wrap gap-4 items-end
                 bg-white dark:bg-dax-dark/60
                 border border-slate-200 dark:border-slate-800
                 rounded-2xl p-5">

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Mês
                </label>
                <input type="month"
                       name="mes"
                       value="{{ $mes }}"
                       class="rounded-xl border
                          border-slate-300 dark:border-slate-700
                          px-4 py-2.5
                          bg-white dark:bg-dax-dark">
            </div>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl
                       bg-dax-green text-white font-semibold">
                Atualizar
            </button>
        </form>

        {{-- TABELA --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                overflow-hidden">

            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Professor</th>
                    <th class="px-4 py-3 text-center">Horas-aula</th>
                    <th class="px-4 py-3 text-center">Valor Hora</th>
                    <th class="px-4 py-3 text-right">Total (R$)</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($relatorio as $linha)
                    <tr>
                        <td class="px-4 py-3 font-semibold">
                            {{ $linha['professor'] }}
                        </td>

                        <td class="px-4 py-3 text-center font-bold">
                            {{ $linha['horas'] }} h/a
                        </td>

                        <td class="px-4 py-3 text-center">
                            R$ {{ number_format($linha['valor_hora'], 2, ',', '.') }}
                        </td>

                        <td class="px-4 py-3 text-right font-bold text-dax-green">
                            R$ {{ number_format($linha['valor_total'], 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-4 py-6 text-center text-slate-500">
                            Nenhum registro encontrado para o período selecionado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
