<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\Disciplina;
use App\Models\DisciplinaTurma;
use App\Models\Presenca;
use App\Models\Turma;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);

        $disciplinas = Disciplina::whereHas('professores', function ($q) use ($professor) {
            $q->where('professores.id', $professor->id);
        })->count();

        $turmas = Turma::whereHas('disciplinaTurmas.professores', function ($q) use ($professor) {
            $q->where('professor_id', $professor->id);
        })->count();

        $aulasHoje = Aula::where('professor_id', $professor->id)
            ->whereDate('data', Carbon::today())
            ->count();

        $proximasAulas = Aula::with(['turma', 'disciplina'])
            ->where('professor_id', $professor->id)
            ->whereDate('data', '>=', Carbon::today())
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        $aulasSemPresenca = Aula::where('professor_id', $professor->id)
            ->whereDoesntHave('presenca')
            ->count();

        $presencasAbertas = Presenca::where('professor_id', $professor->id)
            ->where('status', 'aberta')
            ->count();

        $avaliacoesAbertas = Avaliacao::where('status', 'aberta')
            ->whereExists(function ($q) use ($professor) {
                $q->selectRaw(1)
                    ->from('disciplina_turmas')
                    ->join(
                        'disciplina_turma_professor',
                        'disciplina_turma_professor.disciplina_turma_id',
                        '=',
                        'disciplina_turmas.id'
                    )
                    ->whereColumn('disciplina_turmas.turma_id', 'avaliacoes.turma_id')
                    ->whereColumn('disciplina_turmas.disciplina_id', 'avaliacoes.disciplina_id')
                    ->where('disciplina_turma_professor.professor_id', $professor->id);
            })
            ->count();

        return view('professores.dashboard', compact(
            'disciplinas',
            'turmas',
            'aulasHoje',
            'proximasAulas',
            'aulasSemPresenca',
            'presencasAbertas',
            'avaliacoesAbertas'
        ));
    }
}
