<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Professor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CargaHorariaProfessorController extends Controller
{
    public function index(Request $request)
    {
        // Mes selecionado (YYYY-MM)
        $mes = $request->get('mes', now()->format('Y-m'));

        [$ano, $mesNumero] = explode('-', $mes);

        // Valor da hora-aula (fixo por enquanto)
        $valorHora = 45; // depois vira config ou campo do professor

        $relatorio = Aula::select(
            'professor_id',
            DB::raw('SUM(quantidade_blocos) as total_horas')
        )
            ->whereYear('data', $ano)
            ->whereMonth('data', $mesNumero)
            ->groupBy('professor_id')
            ->with('professor.user')
            ->get()
            ->map(function ($item) use ($valorHora) {
                return [
                    'professor'   => $item->professor->user->name,
                    'horas'       => $item->total_horas,
                    'valor_hora'  => $valorHora,
                    'valor_total' => $item->total_horas * $valorHora,
                ];
            });

        return view('admin.relatorios.carga_horaria_professores.index', [
            'relatorio' => $relatorio,
            'mes'       => $mes,
            'valorHora' => $valorHora
        ]);
    }
}
