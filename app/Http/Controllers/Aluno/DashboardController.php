<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\DisciplinaTurma;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $aluno = auth()->user()?->aluno;
        abort_unless($aluno, 403);

        $disciplinas = DisciplinaTurma::where('turma_id', $aluno->turma_id)->count();

        $aulasHoje = Aula::where('turma_id', $aluno->turma_id)
            ->whereDate('data', Carbon::today())
            ->count();

        $proximasAulas = Aula::with(['turma', 'disciplina'])
            ->where('turma_id', $aluno->turma_id)
            ->whereDate('data', '>=', Carbon::today())
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        $avaliacoesAbertas = Avaliacao::where('turma_id', $aluno->turma_id)
            ->where('status', 'aberta')
            ->count();

        return view('alunos.dashboard', compact(
            'disciplinas',
            'aulasHoje',
            'proximasAulas',
            'avaliacoesAbertas'
        ));
    }
}
