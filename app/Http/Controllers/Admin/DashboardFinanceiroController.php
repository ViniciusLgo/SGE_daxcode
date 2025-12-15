<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $hoje = Carbon::today();
        $mesAtual = Carbon::today()->month;
        $anoAtual = Carbon::today()->year;

        // Total do mês
        $totalMes = Despesa::whereYear('data', $anoAtual)
            ->whereMonth('data', $mesAtual)
            ->sum('valor');

        // Total do ano
        $totalAno = Despesa::whereYear('data', $anoAtual)->sum('valor');

        // Quantidade de lançamentos
        $totalLancamentos = Despesa::count();

        // Categoria mais cara
        $categoriaMaisCara = Despesa::select('categoria_id', DB::raw('SUM(valor) as total'))
            ->with('categoria')
            ->groupBy('categoria_id')
            ->orderByDesc('total')
            ->first();

        // Centro mais caro
        $centroMaisCaro = Despesa::select('centro_custo_id', DB::raw('SUM(valor) as total'))
            ->with('centroCusto')
            ->groupBy('centro_custo_id')
            ->orderByDesc('total')
            ->first();

        // Gastos mês a mês (gráfico de linha)
        $gastosPorMes = Despesa::select(
            DB::raw('MONTH(data) as mes'),
            DB::raw('SUM(valor) as total')
        )
            ->whereYear('data', $anoAtual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Pizza: categorias
        $porCategoria = Despesa::select(
            'categoria_id',
            DB::raw('SUM(valor) as total')
        )
            ->with('categoria')
            ->groupBy('categoria_id')
            ->get();

        // Pizza: formas de pagamento
        $porFormaPagamento = Despesa::select(
            'forma_pagamento',
            DB::raw('SUM(valor) as total')
        )
            ->groupBy('forma_pagamento')
            ->get();

        // Barras: centros de custo
        $porCentro = Despesa::select(
            'centro_custo_id',
            DB::raw('SUM(valor) as total')
        )
            ->with('centroCusto')
            ->groupBy('centro_custo_id')
            ->get();

        return view('admin.financeiro.dashboard.index', compact(
            'totalMes',
            'totalAno',
            'totalLancamentos',
            'categoriaMaisCara',
            'centroMaisCaro',
            'gastosPorMes',
            'porCategoria',
            'porFormaPagamento',
            'porCentro'
        ));
    }
}
