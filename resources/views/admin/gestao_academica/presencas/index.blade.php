{{-- =============================================================================
| VIEW: INDEX DE PRESENCAS
| CAMINHO: resources/views/admin/gestao_academica/presencas/index.blade.php
|
| RESPONSABILIDADE:
| - Dashboard de controle de presenca por aula
| - Exibir KPIs consolidados
| - Permitir filtros simples e avancados
| - Exibir listagem de aulas com status de presenca
|
| OBSERVACOES IMPORTANTES:
| - ESTA VIEW NAO LISTA ALUNOS (apenas aulas)
| - Regras de aluno ativo/inativo NAO se aplicam aqui
| - Contagem de alunos e feita em outras telas (show/edit)
============================================================================= --}}

@extends('layouts.app')

@section('content')
    @php
        $routePrefix = $routePrefix ?? 'admin';
        $isProfessor = $isProfessor ?? false;
        $isAluno = $isAluno ?? false;
    @endphp
    <div class="space-y-6">

        {{-- =========================================================
            HEADER DA PAGINA
        ========================================================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                     Presencas
                </h1>
                <p class="text-sm text-slate-500">
                    Cobertura de presenca por aula (inclui aulas sem chamada)
                </p>
            </div>
        </div>

        {{-- =========================================================
            KPIs (INDICADORES RAPIDOS)
        ========================================================= --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- Total de aulas no periodo --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Aulas no periodo</div>
                <div class="text-2xl font-black">{{ $totalAulas }}</div>
            </div>

            {{-- Aulas com presenca registrada --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Com presenca</div>
                <div class="text-2xl font-black">{{ $comPresenca }}</div>
            </div>

            {{-- Aulas sem presenca --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
                <div class="text-sm text-slate-500">Sem presenca</div>
                <div class="text-2xl font-black">{{ $semPresenca }}</div>
            </div>

            {{-- Presencas finalizadas --}}
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
            ALERTA: AULAS SEM PRESENCA ANTIGAS
        ========================================================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-red-300 dark:border-red-700 p-5">
            <div class="text-sm text-red-600 font-semibold">
                 Aulas sem presenca ha {{ $limiteDias }} dias
            </div>
            <div class="text-2xl font-black text-red-700">
                {{ $aulasPendentesAntigas }}
            </div>
        </div>

        {{-- =========================================================
            GRAFICO + FILTRO RAPIDO
        ========================================================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ================= GRAFICO ================= --}}
            <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

                <div class="flex items-center justify-between mb-4">
                    <div class="font-semibold">
                        Distribuicao por status
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $inicio->format('d/m/Y') }}  {{ $fim->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Canvas com altura fixa --}}
                <div class="relative h-64">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>

            {{-- ================= FILTRO RAPIDO ================= --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

                <div class="font-semibold mb-4">
                    Filtro rapido (periodo)
                </div>

                <form method="GET" class="space-y-3">

                    {{-- Data inicio --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Data inicio</label>
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
                                Sem presenca
                            </option>
                            <option value="aberta" {{ request('status') === 'aberta' ? 'selected' : '' }}>
                                Aberta
                            </option>
                            <option value="finalizada" {{ request('status') === 'finalizada' ? 'selected' : '' }}>
                                Finalizada
                            </option>
                        </select>
                    </div>

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

                    {{-- Acoes --}}
                    <div class="flex gap-2 pt-2">
                        <button class="px-4 py-2.5 rounded-xl border font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Aplicar
                        </button>

                        @if(request()->query())
                            <a href="{{ route($routePrefix . '.presencas.index') }}"
                               class="px-4 py-2.5 rounded-xl border text-slate-500">
                                Limpar
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        {{-- =========================================================
            FILTROS AVANCADOS (ALPINE.JS)
        ========================================================= --}}
        <div x-data="{ open: false }"
             class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

            {{-- Botao toggle --}}
            <button type="button"
                    @click="open = !open"
                    class="flex items-center gap-2 font-semibold text-dax-dark dark:text-dax-light">
                <i class="bi bi-funnel"></i>
                Filtros avancados
                <span class="text-xs text-slate-500" x-text="open ? '(ocultar)' : '(expandir)'"></span>
            </button>

            {{-- Conteudo --}}
            <div x-show="open" x-transition class="mt-4">

                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Mantem filtros simples --}}
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
                    @if(!$isProfessor && !$isAluno)
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
                    @endif

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

                    {{-- Acoes --}}
                    <div class="flex items-end gap-2">
                        <button class="px-4 py-2.5 rounded-xl border font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Aplicar
                        </button>
                        <a href="{{ route($routePrefix . '.presencas.index') }}"
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
                {{-- Cabecalho --}}
                <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Data</th>
                    @if(!$isProfessor)
                        <th class="px-4 py-3 text-left">Professor</th>
                    @endif
                    <th class="px-4 py-3 text-left">Turma</th>
                    <th class="px-4 py-3 text-left">Disciplina</th>
                    <th class="px-4 py-3 text-center">h/a</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Acoes</th>
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
                            <div class="text-xs text-slate-500">{{ $aula->hora_inicio }}  {{ $aula->hora_fim }}</div>
                        </td>

                        @if(!$isProfessor)
                            <td class="px-4 py-3 font-semibold">{{ $aula->professor->user->name }}</td>
                        @endif
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
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">Sem presenca</span>
                            @endif
                        </td>

                        {{-- Acoes --}}
                        <td class="px-4 py-3 text-right space-x-3 font-semibold">
                            <a href="{{ route($routePrefix . '.aulas.show', $aula) }}" class="text-sky-600 hover:underline">Ver aula</a>

                            @if(!$isAluno)
                                <a href="{{ route($routePrefix . '.aulas.presenca.edit', $aula) }}" class="text-amber-600 hover:underline">Presenca</a>
                            @endif

                            @if($p)
                                <a href="{{ route($routePrefix . '.presencas.show', $p) }}" class="text-slate-600 hover:underline">Ver</a>
                            @elseif($isAluno)
                                <span class="text-slate-400">Aguardando</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isProfessor ? 6 : 7 }}" class="px-4 py-8 text-center text-slate-500">
                            Nenhuma aula encontrada no periodo.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Paginacao --}}
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
