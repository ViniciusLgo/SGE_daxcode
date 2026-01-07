<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\Request;
use DB;

class CargaHorariaController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    public function index(Request $request)
    {
        $professorId = $this->professorId();
        $mes = $request->get('mes', now()->format('Y-m'));

        [$ano, $mesNumero] = explode('-', $mes);
        $valorHora = 45;

        $relatorio = Aula::select(
            'professor_id',
            DB::raw('SUM(quantidade_blocos) as total_horas')
        )
            ->where('professor_id', $professorId)
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
