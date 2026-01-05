{{-- =============================================================================
| VIEW: INDEX DE PRESENÇAS
| CAMINHO: resources/views/admin/gestao_academica/presencas/index.blade.php
|
| RESPONSABILIDADE:
| - Dashboard de controle de presença por aula
| - Exibir KPIs consolidados
| - Permitir filtros simples e avançados
| - Exibir listagem de aulas com status de presença
|
| OBSERVAÇÕES IMPORTANTES:
| - ESTA VIEW NÃO LISTA ALUNOS (apenas aulas)
| - Regras de aluno ativo/inativo NÃO se aplicam aqui
| - Contagem de alunos é feita em outras telas (show/edit)
============================================================================= --}}

@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- =========================================================
            HEADER DA PÁGINA
        ========================================================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    📋 Presenças
                </h1>
                <p class="text-sm text-slate-500">
                    Cobertura de presença por aula (inclui aulas sem chamada)
                </p>
            </div>
        </div>

        {{-- =========================================================
            KPIs (INDICADORES RÁPIDOS)
        ========================================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- Total de aulas no período --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Aulas no período</div>
                <div class="text-2xl font-black">{{ $totalAulas }}</div>
            </div>

            {{-- Aulas com presença registrada --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Com presença</div>
                <div class="text-2xl font-black">{{ $comPresenca }}</div>
            </div>

            {{-- Aulas sem presença --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Sem presença</div>
                <div class="text-2xl font-black">{{ $semPresenca }}</div>
            </div>

            {{-- Presenças finalizadas --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Finalizadas</div>
                <div class="text-2xl font-black">{{ $finalizadas }}</div>
            </div>

            {{-- Percentual de cobertura --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Cobertura</div>
                <div class="text-2xl font-black">{{ $cobertura }}%</div>
            </div>
        </div>

        {{-- =========================================================
            ALERTA: AULAS SEM PRESENÇA ANTIGAS
        ========================================================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-red-300 dark:border-red-700 p-5">
            <div class="text-sm text-red-600 font-semibold">
                ⚠️ Aulas sem presença há {{ $limiteDias }} dias
            </div>
            <div class="text-2xl font-black text-red-700">
                {{ $aulasPendentesAntigas }}
            </div>
        </div>

        {{-- =========================================================
            GRÁFICO + FILTRO RÁPIDO
        ========================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ================= GRÁFICO ================= --}}
            <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex items-center justify-between mb-4">
                    <div class="font-semibold">
                        Distribuição por status
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $inicio->format('d/m/Y') }} → {{ $fim->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Canvas com altura fixa --}}
                <div class="relative h-64">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>

            {{-- ================= FILTRO RÁPIDO ================= --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

                <div class="font-semibold mb-4">
                    Filtro rápido (período)
                </div>

                <form method="GET" class="space-y-3">

                    {{-- Data início --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Data início</label>
                        <input type="date"
                               name="data_inicio"
                               value="{{ request('data_inicio', $inicio->toDateString()) }}"
                               class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                    </div>

                    {{-- Data fim --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Data fim</label>
                        <input type="date"
                               name="data_fim"
                               value="{{ request('data_fim', $fim->toDateString()) }}"
                               class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                            <option value="">Todos</option>
                            <option value="sem_presenca" {{ request('status') === 'sem_presenca' ? 'selected' : '' }}>
                                Sem presença
                            </option>
                            <option value="aberta" {{ request('status') === 'aberta' ? 'selected' : '' }}>
                                Aberta
                            </option>
                            <option value="finalizada" {{ request('status') === 'finalizada' ? 'selected' : '' }}>
                                Finalizada
                            </option>
                        </select>
                    </div>

                    {{-- Ações --}}
                    <div class="flex gap-2 pt-2">
                        <button class="px-4 py-2.5 rounded-xl border font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Aplicar
                        </button>

                        @if(request()->query())
                            <a href="{{ route('admin.presencas.index') }}"
                               class="px-4 py-2.5 rounded-xl border text-slate-500">
                                Limpar
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        {{-- =========================================================
            FILTROS AVANÇADOS (ALPINE.JS)
        ========================================================= --}}
        <div x-data="{ open: false }"
             class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

            {{-- Botão toggle --}}
            <button type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 font-semibold text-dax-dark dark:text-dax-light">
                <i class="bi bi-funnel"></i>
                Filtros avançados
                <span class="text-xs text-slate-500" x-text="open ? '(ocultar)' : '(expandir)'"></span>
            </button>

            {{-- Conteúdo --}}
            <div x-show="open" x-transition class="mt-4">

                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Mantém filtros simples --}}
                    <input type="hidden" name="data_inicio" value="{{ request('data_inicio') }}">
                    <input type="hidden" name="data_fim" value="{{ request('data_fim') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">

                    {{-- Turma --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Turma</label>
                        <select name="turma_id" class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                            <option value="">Todas</option>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                                    {{ $turma->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Professor --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Professor</label>
                        <select name="professor_id" class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                            <option value="">Todos</option>
                            @foreach($professores as $prof)
                                <option value="{{ $prof->id }}" {{ request('professor_id') == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Disciplina --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Disciplina</label>
                        <select name="disciplina_id" class="w-full rounded-xl border px-4 py-2.5 bg-white dark:bg-dax-dark">
                            <option value="">Todas</option>
                            @foreach($disciplinas as $disciplina)
                                <option value="{{ $disciplina->id }}" {{ request('disciplina_id') == $disciplina->id ? 'selected' : '' }}>
                                    {{ $disciplina->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Ações --}}
                    <div class="flex items-end gap-2">
                        <button class="px-4 py-2.5 rounded-xl border font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Aplicar
                        </button>
                        <a href="{{ route('admin.presencas.index') }}"
                           class="px-4 py-2.5 rounded-xl border text-slate-500">
                            Limpar
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- =========================================================
            LISTAGEM DE AULAS
        ========================================================= --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 border-slate-200 dark:border-slate-800 overflow-hidden">

            {{-- Tabela --}}
            <table class="min-w-full text-sm">
                {{-- Cabeçalho --}}
                <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Data</th>
                    <th class="px-4 py-3 text-left">Professor</th>
                    <th class="px-4 py-3 text-left">Turma</th>
                    <th class="px-4 py-3 text-left">Disciplina</th>
                    <th class="px-4 py-3 text-center">h/a</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
                </thead>

                {{-- Corpo --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($aulas as $aula)
                    @php
                        $p = $aula->presenca;
                        $status = $p?->status ?? 'sem_presenca';
                    @endphp

                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $aula->data->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $aula->hora_inicio }} → {{ $aula->hora_fim }}</div>
                        </td>

                        <td class="px-4 py-3 font-semibold">{{ $aula->professor->user->name }}</td>
                        <td class="px-4 py-3">{{ $aula->turma->nome }}</td>
                        <td class="px-4 py-3">{{ $aula->disciplina->nome }}</td>
                        <td class="px-4 py-3 text-center font-bold">{{ $aula->quantidade_blocos }}</td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            @if($status === 'finalizada')
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Finalizada</span>
                            @elseif($status === 'aberta')
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Aberta</span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">Sem presença</span>
                            @endif
                        </td>

                        {{-- Ações --}}
                        <td class="px-4 py-3 text-right space-x-3 font-semibold">
                            <a href="{{ route('admin.aulas.show', $aula) }}" class="text-sky-600 hover:underline">Ver aula</a>
                            <a href="{{ route('admin.aulas.presenca.edit', $aula) }}" class="text-amber-600 hover:underline">Presença</a>

                            @if($p)
                                <a href="{{ route('admin.presencas.show', $p) }}" class="text-slate-600 hover:underline">Ver</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                            Nenhuma aula encontrada no período.
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

    {{-- =========================================================
        CHART.JS
    ========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartStatus');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartStatus['labels']),
                datasets: [{ data: @json($chartStatus['data']) }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection
