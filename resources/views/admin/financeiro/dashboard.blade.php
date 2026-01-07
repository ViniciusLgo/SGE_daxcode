@extends('layouts.app')

@section('content')

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                 Dashboard Financeiro
            </h1>
            <p class="text-sm text-slate-500">
                Visao geral das despesas do projeto social.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.despesas.create') }}"
           class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold
              hover:bg-dax-greenSoft transition">
            + Lancar Despesa
        </a>
    </div>

    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6 mb-6">

        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                {{-- Ano --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Ano
                    </label>
                    <select name="ano"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" @selected($i == $ano)>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Mes --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Mes
                    </label>
                    <select name="mes"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        <option value="">Todos</option>
                        @foreach([
                            1=>'Janeiro',2=>'Fevereiro',3=>'Marco',4=>'Abril',5=>'Maio',
                            6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',
                            10=>'Outubro',11=>'Novembro',12=>'Dezembro'
                        ] as $m => $nome)
                            <option value="{{ $m }}" @selected($mes == $m)>
                                {{ $nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Inicio --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        De
                    </label>
                    <input type="date" name="inicio" value="{{ $inicio }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- Fim --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Ate
                    </label>
                    <input type="date" name="fim" value="{{ $fim }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
                    Filtrar
                </button>
                <a href="{{ route('admin.financeiro.dashboard') }}"
                   class="px-4 py-2 rounded-xl border">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    {{-- ========================================================= --}}
    {{-- KPIs --}}
    {{-- ========================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        {{-- Total mes --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-6">
            <p class="text-sm text-slate-500 mb-1">Total do mes</p>
            <h3 class="text-2xl font-black">
                R$ {{ number_format($totalMes, 2, ',', '.') }}
            </h3>
        </div>

        {{-- Total ano --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-6">
            <p class="text-sm text-slate-500 mb-1">Total do ano</p>
            <h3 class="text-2xl font-black">
                R$ {{ number_format($totalAno, 2, ',', '.') }}
            </h3>
        </div>

        {{-- Categorias --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-6">
            <p class="text-sm text-slate-500 mb-2">Categorias mais utilizadas</p>
            <ul class="space-y-1 text-sm">
                @forelse($topCategorias as $item)
                    <li class="flex justify-between">
                        <span>{{ $item->categoria->nome ?? 'Sem categoria' }}</span>
                        <strong>
                            R$ {{ number_format($item->total, 2, ',', '.') }}
                        </strong>
                    </li>
                @empty
                    <li class="text-slate-500">Nenhuma despesa lancada.</li>
                @endforelse
            </ul>
        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- RANKING --}}
    {{-- ========================================================= --}}
    <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-6 mb-6">
        <h2 class="text-lg font-black mb-4">
             Ranking de Categorias (Top 5)
        </h2>

        <ol class="space-y-2">
            @foreach($porCategoria as $c)
                <li class="flex justify-between px-4 py-2 rounded-xl
                       bg-slate-100 dark:bg-slate-900">
                    <span>{{ $c->categoria->nome ?? 'Sem categoria' }}</span>
                    <strong>
                        R$ {{ number_format($c->total, 2, ',', '.') }}
                    </strong>
                </li>
            @endforeach
        </ol>
    </div>

    {{-- ========================================================= --}}
    {{-- GRAFICOS --}}
    {{-- ========================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @foreach([
            ['id'=>'graficoMes','titulo'=>"Gastos por mes ($ano)"],
            ['id'=>'graficoCategoria','titulo'=>'Distribuicao por categoria'],
            ['id'=>'graficoCentro','titulo'=>'Gastos por centro de custo'],
            ['id'=>'graficoForma','titulo'=>'Forma de pagamento']
        ] as $g)
            <div class="rounded-2xl border bg-white dark:bg-dax-dark/60 p-6 h-[350px]">
                <h3 class="font-semibold mb-3">{{ $g['titulo'] }}</h3>
                <canvas id="{{ $g['id'] }}" height="250"></canvas>
            </div>
        @endforeach

    </div>

@endsection


{{-- ========================================================= --}}
{{-- SCRIPTS DOS GRAFICOS --}}
{{-- ========================================================= --}}
@section('scripts')

    {{-- Chart.js + plugin de rotulos --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        /* ===========================================================
           PREPARACAO DOS DADOS
        =========================================================== */

        const mesesFixos = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];

        // Preencher meses vazios
        const dadosMes = Array(12).fill(0);

        @foreach($gastosPorMes as $g)
            dadosMes[{{ $g->mes - 1 }}] = {{ $g->total }};
        @endforeach


        /* ------- Categorias ------- */
        const labelsCategoria = @json($porCategoria->map(fn($c)=>$c->categoria->nome ?? 'Sem categoria'));
        const dadosCategoria  = @json($porCategoria->pluck('total'));

        // Gerar cores automaticas
        const coresCategorias = labelsCategoria.map((_, i) => {
            const paleta = [
                "#2196F3","#E91E63","#FF9800","#4CAF50","#9C27B0",
                "#03A9F4","#795548","#00BCD4","#8BC34A","#FF5722"
            ];
            return paleta[i % paleta.length];
        });


        /* ------- Centro de custo ------- */
        const labelsCentro = @json($porCentro->map(fn($c)=>$c->centroCusto->nome ?? 'Sem centro'));
        const dadosCentro  = @json($porCentro->pluck('total'));

        /* ------- Forma de pagamento ------- */
        const labelsForma = @json($porFormaPagamento->pluck('forma_pagamento'));
        const dadosForma  = @json($porFormaPagamento->pluck('total'));


        /* ===========================================================
           GRAFICO: LINHA (GASTOS POR MES)
        =========================================================== */
        new Chart(document.getElementById('graficoMes'), {
            type: 'line',
            data: {
                labels: mesesFixos,
                datasets: [{
                    label: "Gastos (R$)",
                    data: dadosMes,
                    borderColor: "#0d6efd",
                    backgroundColor: "rgba(13,110,253,.2)",
                    borderWidth: 3,
                    pointRadius: 4,
                    tension: 0.4
                }]
            }
        });


        /* ===========================================================
           GRAFICO: PIZZA (CATEGORIAS)
        =========================================================== */
        new Chart(document.getElementById('graficoCategoria'), {
            type: 'pie',
            data: {
                labels: labelsCategoria,
                datasets: [{
                    data: dadosCategoria,
                    backgroundColor: coresCategorias
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        formatter: value => "R$ " + value.toLocaleString("pt-BR"),
                        color: "#fff",
                        font: { weight: "bold" }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        /* ===========================================================
           GRAFICO: BARRA (CENTRO DE CUSTO)
        =========================================================== */
        new Chart(document.getElementById('graficoCentro'), {
            type: 'bar',
            data: {
                labels: labelsCentro,
                datasets: [{
                    label: "Gastos (R$)",
                    data: dadosCentro,
                    backgroundColor: "#0d6efd"
                }]
            }
        });


        /* ===========================================================
           GRAFICO: PIZZA (FORMA DE PAGAMENTO)
        =========================================================== */
        new Chart(document.getElementById('graficoForma'), {
            type: 'pie',
            data: {
                labels: labelsForma,
                datasets: [{
                    data: dadosForma,
                    backgroundColor: ["#4CAF50","#2196F3","#FFC107","#E91E63"]
                }]
            }
        });

    </script>

@endsection
