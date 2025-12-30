@extends('layouts.app')

@section('content')

    {{-- =========================================================
    | CABEÇALHO
    ========================================================= --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex gap-2">
                <i class="bi bi-graph-up"></i> Relatório de Evasão
            </h1>
            <p class="text-slate-500 dark:text-slate-400">
                Indicadores e tendências de evasão com filtros por turma e período.
            </p>
        </div>
    </div>

    {{-- =========================================================
    | FILTROS DO RELATÓRIO
    ========================================================= --}}
    <form method="GET"
          class="mb-6 rounded-2xl bg-white dark:bg-dax-dark/60
             border border-slate-200 dark:border-slate-800 p-4">

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">

            {{-- TURMA --}}
            <div class="md:col-span-2">
                <label class="text-sm font-semibold">Turma</label>
                <select name="turma_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                           bg-white dark:bg-slate-900 px-4 py-2.5">
                    <option value="">Todas</option>
                    @foreach($turmas as $t)
                        <option value="{{ $t->id }}" {{ (string)$turmaId === (string)$t->id ? 'selected' : '' }}>
                            {{ $t->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ANO --}}
            <div>
                <label class="text-sm font-semibold">Ano</label>
                <input type="number" name="ano" value="{{ $ano }}"
                       placeholder="2025"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                          bg-white dark:bg-slate-900 px-4 py-2.5">
            </div>

            {{-- TURNO --}}
            <div>
                <label class="text-sm font-semibold">Turno</label>
                <select name="turno"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                           bg-white dark:bg-slate-900 px-4 py-2.5">
                    <option value="">Todos</option>
                    @foreach(['manhã','tarde','noite'] as $t)
                        <option value="{{ $t }}" {{ $turno === $t ? 'selected' : '' }}>
                            {{ ucfirst($t) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PERÍODO --}}
            <div>
                <label class="text-sm font-semibold">Início</label>
                <input type="date" name="inicio" value="{{ $inicio }}"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                          bg-white dark:bg-slate-900 px-4 py-2.5">
            </div>

            <div>
                <label class="text-sm font-semibold">Fim</label>
                <input type="date" name="fim" value="{{ $fim }}"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                          bg-white dark:bg-slate-900 px-4 py-2.5">
            </div>

            {{-- AÇÕES --}}
            <div class="flex gap-3">
                <button class="px-5 py-2.5 rounded-xl border border-dax-green
                           text-dax-green font-bold hover:bg-dax-green hover:text-white transition">
                    <i class="bi bi-funnel"></i> Aplicar
                </button>

                @if(request()->query())
                    <a href="{{ route('admin.relatorios.evasao.index') }}"
                       class="px-4 py-2.5 text-slate-500 hover:underline">
                        Limpar
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- =========================================================
    | KPIs PRINCIPAIS
    ========================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        {{-- TOTAL --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <div class="text-sm text-slate-500">Total de Matrículas</div>
            <div class="text-3xl font-black">{{ $total }}</div>
        </div>

        {{-- ATIVOS --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <div class="text-sm text-slate-500">Ativos</div>
            <div class="text-3xl font-black">{{ $ativos }}</div>
            <span class="inline-flex mt-2 px-2 py-0.5 rounded-full text-xs font-bold
                     bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
            ATIVO
        </span>
        </div>

        {{-- DESISTENTES --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <div class="text-sm text-slate-500">Desistentes</div>
            <div class="text-3xl font-black">{{ $desistentes }}</div>
            <span class="inline-flex mt-2 px-2 py-0.5 rounded-full text-xs font-bold
                     bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
            DESISTENTE
        </span>
        </div>

        {{-- TAXA --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <div class="text-sm text-slate-500">Taxa de Evasão</div>
            <div class="text-3xl font-black">{{ $taxaEvasao }}%</div>
            <div class="text-xs text-slate-500">(desistentes ÷ total)</div>
        </div>
    </div>

    {{-- =========================================================
    | GRÁFICOS (Chart.js)
    ========================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- EVASÃO DIÁRIA --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <h2 class="text-lg font-black mb-3">Desistências por Dia</h2>
            <canvas id="evasaoChart" height="130"></canvas>
        </div>

        {{-- EVASÃO MENSAL --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <h2 class="text-lg font-black mb-3">Evasão por Mês</h2>
            <canvas id="evasaoMensalChart" height="130"></canvas>
        </div>
    </div>

    {{-- =========================================================
    | EVASÃO POR TURMA + TOP MOTIVOS
    ========================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- EVASÃO POR TURMA (GRÁFICO) --}}
        <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <h2 class="text-lg font-black mb-3">Evasão por Turma</h2>
            <canvas id="evasaoPorTurmaChart" height="140"></canvas>
        </div>

        {{-- TOP MOTIVOS --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-5">
            <h2 class="text-lg font-black mb-3">Motivos mais frequentes</h2>

            @forelse($topMotivos as $m)
                <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold truncate">
                    {{ $m->motivo_label }}
                </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold
                             bg-slate-100 dark:bg-slate-700">
                    {{ $m->total }}
                </span>
                </div>
            @empty
                <p class="text-sm text-slate-500">Sem dados no período.</p>
            @endforelse
        </div>
    </div>

    {{-- =========================================================
    | LISTA DE DESISTENTES
    ========================================================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border overflow-hidden">
        <div class="p-5">
            <h2 class="text-lg font-black">Últimos alunos desistentes</h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900">
            <tr class="text-left text-slate-500">
                <th class="px-4 py-3">Aluno</th>
                <th class="px-4 py-3">Turma</th>
                <th class="px-4 py-3">Data</th>
                <th class="px-4 py-3">Motivo</th>
                <th class="px-4 py-3 text-right">Ação</th>
            </tr>
            </thead>
            <tbody>
            @forelse($listaDesistentes as $m)
                <tr class="border-t">
                    <td class="px-4 py-3 font-semibold">{{ $m->aluno->user->name }}</td>
                    <td class="px-4 py-3">{{ $m->turma->nome }}</td>
                    <td class="px-4 py-3">{{ optional($m->data_status)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $m->motivo ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.alunos.show', $m->aluno_id) }}"
                           class="text-dax-blue hover:underline">
                            Ver aluno
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                        Nenhuma desistência encontrada.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- =========================================================
    | CHART.JS
    ========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('evasaoChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{ label: 'Desistências', data: @json($chartData), tension: 0.25 }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('evasaoMensalChart'), {
            type: 'line',
            data: {
                labels: @json($labelsMes),
                datasets: [{ label: 'Mensal', data: @json($dataMes), tension: 0.25 }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('evasaoPorTurmaChart'), {
            type: 'bar',
            data: {
                labels: @json($labelsTurma),
                datasets: [{ label: 'Desistentes', data: @json($dataTurma) }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>

@endsection
