<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Professor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RelatorioHorasController extends Controller
{
    /**
     * Relatório mensal de horas por professor
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);

        $aulas = Aula::contaParaSalario()
            ->whereNotNull('professor_id')
            ->whereNotNull('data')
            ->whereNotNull('duracao_minutos')
            ->whereMonth('data', $mes)
            ->whereYear('data', $ano)
            ->with('professor.user')
            ->groupBy('professor_id')
            ->selectRaw('
                professor_id,
                COUNT(*) as total_registros,
                SUM(quantidade_blocos) as total_blocos,
                SUM(duracao_minutos) as total_minutos
            ')
            ->orderBy('professor_id')
            ->get();

        return view('admin.relatorios.horas.index', [
            'aulas' => $aulas,
            'mes'   => $mes,
            'ano'   => $ano,
        ]);
    }
}
