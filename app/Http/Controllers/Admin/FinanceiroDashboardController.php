<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceiroDashboardController extends Controller
{
    /**
     * ================================================================
     *  DASHBOARD FINANCEIRO
     *  - Aplica filtros
     *  - Calcula totais
     *  - Prepara dados para os gráficos
     *  - Retorna a view COMPLETA
     * ================================================================
     */
    public function index(Request $request)
    {
        // ===========================
        // FILTROS DO USUÁRIO
        // ===========================
        $ano    = $request->ano ?? date('Y');
        $mes    = $request->mes ?? null;
        $inicio = $request->inicio ?? null;
        $fim    = $request->fim ?? null;

        // ===========================
        // QUERY BASE
        // (será usada p/ múltiplos relatórios)
        // ===========================
        $query = Despesa::query();

        if ($ano) $query->whereYear('data', $ano);
        if ($mes) $query->whereMonth('data', $mes);
        if ($inicio) $query->whereDate('data', '>=', $inicio);
        if ($fim) $query->whereDate('data', '<=', $fim);

        // =======================================================
        // TOTAIS RESUMIDOS
        // =======================================================

        // TOTAL do mês ATUAL (NÃO depende de filtro de mês)
        $totalMes = Despesa::whereYear('data', $ano)
            ->whereMonth('data', date('m'))
            ->sum('valor');

        // TOTAL do ano (com filtro)
        $totalAno = Despesa::whereYear('data', $ano)->sum('valor');

        // Total de lançamentos filtrados
        $totalLancamentos = $query->count();

        // Categoria mais cara dentro do filtro
        $categoriaMaisCara = $query
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->first();

        // Top 5 categorias
        $topCategorias = $query
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =======================================================
        // GRÁFICO: Gastos por mês
        // =======================================================
        $gastosPorMes = Despesa::selectRaw('MONTH(data) as mes, SUM(valor) as total')
            ->whereYear('data', $ano)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // =======================================================
        // GRÁFICO: Por categoria
        // =======================================================
        $porCategoria = $query
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->get();

        // =======================================================
        // GRÁFICO: Por forma de pagamento
        // =======================================================
        $porFormaPagamento = $query
            ->select('forma_pagamento', DB::raw('SUM(valor) as total'))
            ->groupBy('forma_pagamento')
            ->orderBy('forma_pagamento')
            ->get();

        // =======================================================
        // GRÁFICO: Por centro de custo
        // =======================================================
        $porCentro = $query
            ->select('centro_custo_id', DB::raw('SUM(valor) as total'))
            ->with('centroCusto')
            ->groupBy('centro_custo_id')
            ->orderByDesc('total')
            ->get();

        // =======================================================
        // RETURN — Envia TUDO para a VIEW
        // =======================================================
        return view('admin.financeiro.dashboard', compact(
            'ano', 'mes', 'inicio', 'fim',
            'totalMes', 'totalAno', 'totalLancamentos',
            'categoriaMaisCara', 'topCategorias',
            'gastosPorMes', 'porCategoria',
            'porFormaPagamento', 'porCentro'
        ));
    }

    /**
     * ================================================================
     *  CLONAR LANÇAMENTOS DO MÊS ANTERIOR
     * ================================================================
     */
    public function clonarMesAnterior()
    {
        $mesAtual   = now()->month;
        $anoAtual   = now()->year;

        $mesAnterior = now()->subMonth()->month;
        $anoAnterior = now()->subMonth()->year;

        $despesas = Despesa::whereMonth('data', $mesAnterior)
            ->whereYear('data', $anoAnterior)
            ->get();

        if ($despesas->isEmpty()) {
            return back()->with('error', 'Não há despesas no mês anterior para clonar.');
        }

        foreach ($despesas as $d) {
            $nova = $d->replicate();
            $nova->data = now(); // Define como mês atual
            $nova->save();
        }

        return back()->with('success', 'Lançamentos do mês anterior clonados com sucesso!');
    }

    /**
     * ================================================================
     *  EXCLUSÃO MÚLTIPLA DE DESPESAS
     * ================================================================
     */
    public function excluirMultiplas(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Nenhuma despesa selecionada.');
        }

        Despesa::whereIn('id', $ids)->delete();

        return back()->with('success', 'Despesas excluídas com sucesso!');
    }
}
