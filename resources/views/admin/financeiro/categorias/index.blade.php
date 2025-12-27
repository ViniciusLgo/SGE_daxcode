@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                🏷️ Categorias de Despesas
            </h1>
            <p class="text-sm text-slate-500">
                Gerencie os tipos de gastos do projeto social.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.categorias.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                  bg-dax-green text-white font-semibold
                  hover:bg-dax-greenSoft transition">
            ➕ Nova Categoria
        </a>
    </div>

    {{-- ================= FLASH SUCCESS ================= --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-300 dark:border-emerald-700
                    bg-emerald-50 dark:bg-emerald-900/30 p-4
                    text-dax-dark dark:text-dax-light">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= EMPTY STATE ================= --}}
    @if($categorias->count() === 0)
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                    bg-white dark:bg-dax-dark/60 p-6 text-center">
            <p class="text-slate-500">
                Nenhuma categoria cadastrada ainda.
            </p>
            <p class="mt-2 text-sm text-slate-400">
                Clique em <strong>“Nova Categoria”</strong> para começar.
            </p>
        </div>
    @else
        {{-- ================= GRÁFICOS ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- TOTAL POR CATEGORIA --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-5">
                <h2 class="text-sm font-semibold mb-3 text-dax-dark dark:text-dax-light">
                    💰 Total gasto por categoria
                </h2>
                <canvas id="graficoCategorias"></canvas>
            </div>

            {{-- QUANTIDADE DE DESPESAS --}}
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-5">
                <h2 class="text-sm font-semibold mb-3 text-dax-dark dark:text-dax-light">
                    📊 Quantidade de despesas
                </h2>
                <canvas id="graficoQuantidade"></canvas>
            </div>

        </div>


        {{-- ================= TABLE CARD ================= --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                    bg-white dark:bg-dax-dark/60 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">

                    {{-- HEAD --}}
                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                    <tr class="text-left text-sm font-semibold text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-3 w-16">#</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Descrição</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($categorias as $categoria)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">

                            <td class="px-4 py-3 text-sm text-slate-500">
                                {{ $categoria->id }}
                            </td>

                            <td class="px-4 py-3 font-semibold text-dax-dark dark:text-dax-light">
                                {{ $categoria->nome }}
                            </td>

                            <td class="px-4 py-3 text-sm text-slate-500">
                                {{ $categoria->descricao ?: '—' }}
                            </td>

                            <td class="text-right space-x-2">

                                {{-- VER --}}
                                <a href="{{ route('admin.financeiro.categorias.show', $categoria) }}"
                                   class="text-sm font-semibold text-dax-green hover:underline">
                                    Ver
                                </a>

                                {{-- EDITAR --}}
                                <a href="{{ route('admin.financeiro.categorias.edit', $categoria) }}"
                                   class="text-sm font-semibold text-blue-600 hover:underline">
                                    Editar
                                </a>

                                {{-- EXCLUIR --}}
                                <form action="{{ route('admin.financeiro.categorias.destroy', $categoria) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Deseja realmente excluir esta categoria?');">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="text-sm font-semibold text-red-600 hover:underline">
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

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const categorias = @json($graficoCategorias);

        const labels = categorias.map(c => c.nome);
        const valores = categorias.map(c => c.total);
        const quantidades = @json($categorias->pluck('despesas_count'));

        /* === TOTAL GASTO === */
        new Chart(document.getElementById('graficoCategorias'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total gasto (R$)',
                    data: valores,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        /* === QUANTIDADE === */
        new Chart(document.getElementById('graficoQuantidade'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: quantidades,
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

@endsection

