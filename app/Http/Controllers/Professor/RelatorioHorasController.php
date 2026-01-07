<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatorioHorasController extends Controller
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
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);

        $aulas = Aula::contaParaSalario()
            ->where('professor_id', $professorId)
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
