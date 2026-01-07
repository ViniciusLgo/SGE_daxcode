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
     *  - Prepara dados para os graficos
     *  - Retorna a view COMPLETA
     * ================================================================
     */
    public function index(Request $request)
    {
        // ===========================
        // FILTROS DO USUARIO
        // ===========================
        $ano    = $request->ano ?? date('Y');
        $mes    = $request->mes ?? null;
        $inicio = $request->inicio ?? null;
        $fim    = $request->fim ?? null;

        // ===========================
        // QUERY BASE
        // (sera usada p/ multiplos relatorios)
        // ===========================
        $baseQuery = Despesa::query();

        if ($ano) $baseQuery->whereYear('data', $ano);
        if ($mes) $baseQuery->whereMonth('data', $mes);
        if ($inicio) $baseQuery->whereDate('data', '>=', $inicio);
        if ($fim) $baseQuery->whereDate('data', '<=', $fim);

        // =======================================================
        // TOTAIS RESUMIDOS
        // =======================================================

        // TOTAL do mes ATUAL (NAO depende de filtro de mes)
        $totalMes = Despesa::whereYear('data', $ano)
            ->whereMonth('data', date('m'))
            ->sum('valor');

        // TOTAL do ano (com filtro)
        $totalAno = Despesa::whereYear('data', $ano)->sum('valor');

        // Total de lancamentos filtrados
        $totalLancamentos = (clone $baseQuery)->count();

        // Categoria mais cara dentro do filtro
        $categoriaMaisCara = (clone $baseQuery)
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->first();

        // Top 5 categorias
        $topCategorias = (clone $baseQuery)
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // =======================================================
        // GRAFICO: Gastos por mes
        // =======================================================
        $gastosPorMes = Despesa::selectRaw('MONTH(data) as mes, SUM(valor) as total')
            ->whereYear('data', $ano)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // =======================================================
        // GRAFICO: Por categoria
        // =======================================================
        $porCategoria = (clone $baseQuery)
            ->select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->get();

        // =======================================================
        // GRAFICO: Por forma de pagamento
        // =======================================================
        $porFormaPagamento = (clone $baseQuery)
            ->select('forma_pagamento', DB::raw('SUM(valor) as total'))
            ->groupBy('forma_pagamento')
            ->orderBy('forma_pagamento')
            ->get();

        // =======================================================
        // GRAFICO: Por centro de custo
        // =======================================================
        $porCentro = (clone $baseQuery)
            ->select('centro_custo_id', DB::raw('SUM(valor) as total'))
            ->with('centroCusto')
            ->groupBy('centro_custo_id')
            ->orderByDesc('total')
            ->get();

        // =======================================================
        // RETURN  Envia TUDO para a VIEW
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
     *  CLONAR LANCAMENTOS DO MES ANTERIOR
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
            return back()->with('error', 'Nao ha despesas no mes anterior para clonar.');
        }

        foreach ($despesas as $d) {
            $nova = $d->replicate();
            $nova->data = now(); // Define como mes atual
            $nova->save();
        }

        return back()->with('success', 'Lancamentos do mes anterior clonados com sucesso!');
    }

    /**
     * ================================================================
     *  EXCLUSAO MULTIPLA DE DESPESAS
     * ================================================================
     */
    public function excluirMultiplas(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Nenhuma despesa selecionada.');
        }

        Despesa::whereIn('id', $ids)->delete();

        return back()->with('success', 'Despesas excluidas com sucesso!');
    }
}
