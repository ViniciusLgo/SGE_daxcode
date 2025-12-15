@extends('layouts.app')

@section('content')

    {{-- ========================================================= --}}
    {{-- CABEÇALHO --}}
    {{-- ========================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Dashboard Financeiro</h4>
            <p class="text-muted mb-0">Visão geral das despesas do projeto social.</p>
        </div>

        {{-- Botão principal de criar despesa --}}
        <a href="{{ route('admin.financeiro.despesas.create') }}" class="btn btn-primary">
            + Lançar Despesa
        </a>
    </div>


    {{-- ========================================================= --}}
    {{-- FILTROS (ANO / MÊS / PERÍODO) --}}
    {{-- ========================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="GET" class="row g-3">

                {{-- Filtro por ano --}}
                <div class="col-md-3">
                    <label class="form-label">Ano</label>
                    <select name="ano" class="form-select">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == $ano ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Filtro por mês --}}
                <div class="col-md-3">
                    <label class="form-label">Mês</label>
                    <select name="mes" class="form-select">
                        <option value="">Todos</option>
                        @foreach ([
                            1=>'Janeiro',2=>'Fevereiro',3=>'Março',4=>'Abril',5=>'Maio',
                            6=>'Junho',7=>'Julho',8=>'Agosto',9=>'Setembro',
                            10=>'Outubro',11=>'Novembro',12=>'Dezembro'
                        ] as $m => $nome)
                            <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                {{ $nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Período de início --}}
                <div class="col-md-3">
                    <label class="form-label">De</label>
                    <input type="date" name="inicio" class="form-control" value="{{ $inicio }}">
                </div>

                {{-- Período de fim --}}
                <div class="col-md-3">
                    <label class="form-label">Até</label>
                    <input type="date" name="fim" class="form-control" value="{{ $fim }}">
                </div>

                {{-- Botões --}}
                <div class="col-12 text-end">
                    <button class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('admin.financeiro.dashboard') }}" class="btn btn-secondary">
                        Limpar
                    </a>
                </div>

            </form>

        </div>
    </div>



    {{-- ========================================================= --}}
    {{-- CARDS RESUMO SUPERIOR --}}
    {{-- ========================================================= --}}
    <div class="row g-3 mb-4">

        {{-- Total do mês --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body">
                    <p class="text-muted mb-1">Total do mês</p>
                    <h3>R$ {{ number_format($totalMes, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Total do ano --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body">
                    <p class="text-muted mb-1">Total do ano</p>
                    <h3>R$ {{ number_format($totalAno, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- Categorias mais utilizadas --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body">
                    <p class="text-muted mb-1">Categorias mais utilizadas</p>

                    <ul class="mb-0">
                        @forelse($topCategorias as $item)
                            <li>
                                {{ $item->categoria->nome ?? 'Sem categoria' }}:
                                <strong>R$ {{ number_format($item->total, 2, ',', '.') }}</strong>
                            </li>
                        @empty
                            <li>Nenhuma despesa lançada.</li>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- RANKING DE CATEGORIAS --}}
    {{-- ========================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3">🏆 Ranking de Categorias (Top 5)</h5>

            <ol class="list-group list-group-numbered">
                @foreach($porCategoria as $c)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $c->categoria->nome ?? 'Sem categoria' }}</span>
                        <strong>R$ {{ number_format($c->total, 2, ',', '.') }}</strong>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>



    {{-- ========================================================= --}}
    {{-- GRÁFICOS --}}
    {{-- ========================================================= --}}
    <div class="row g-4">

        {{-- Linha: Gastos por mês --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3" style="height: 350px;">
                <h6 class="mb-3">Gastos por mês ({{ $ano }})</h6>
                <canvas id="graficoMes" height="250"></canvas>
            </div>
        </div>

        {{-- Pizza: Categorias --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3" style="height: 350px;">
                <h6 class="mb-3">Distribuição por categoria</h6>
                <canvas id="graficoCategoria" height="250"></canvas>
            </div>
        </div>

        {{-- Barras: Centros de custo --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3" style="height: 350px;">
                <h6 class="mb-3">Gastos por centro de custo</h6>
                <canvas id="graficoCentro" height="250"></canvas>
            </div>
        </div>

        {{-- Pizza: Forma de pagamento --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3" style="height: 350px;">
                <h6 class="mb-3">Forma de pagamento</h6>
                <canvas id="graficoForma" height="250"></canvas>
            </div>
        </div>

    </div>

@endsection



{{-- ========================================================= --}}
{{-- SCRIPTS DOS GRÁFICOS --}}
{{-- ========================================================= --}}
@section('scripts')

    {{-- Chart.js + plugin de rótulos --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        /* ===========================================================
           PREPARAÇÃO DOS DADOS
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

        // Gerar cores automáticas
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
           GRÁFICO: LINHA (GASTOS POR MÊS)
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
           GRÁFICO: PIZZA (CATEGORIAS)
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
           GRÁFICO: BARRA (CENTRO DE CUSTO)
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
           GRÁFICO: PIZZA (FORMA DE PAGAMENTO)
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
