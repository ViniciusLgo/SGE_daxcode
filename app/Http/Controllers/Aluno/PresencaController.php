<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Disciplina;
use App\Models\Presenca;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    private function aluno()
    {
        $aluno = auth()->user()?->aluno;
        abort_unless($aluno, 403);
        return $aluno;
    }

    public function index(Request $request)
    {
        $aluno = $this->aluno();

        $inicio = $request->filled('data_inicio')
            ? Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $fim = $request->filled('data_fim')
            ? Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay()
            : now()->endOfDay();

        $query = Aula::with(['turma', 'disciplina', 'professor.user', 'presenca'])
            ->where('turma_id', $aluno->turma_id)
            ->whereBetween('data', [$inicio->toDateString(), $fim->toDateString()])
            ->orderByDesc('data')
            ->orderByDesc('hora_inicio');

        if ($request->filled('status')) {
            if ($request->status === 'sem_presenca') {
                $query->whereDoesntHave('presenca');
            } else {
                $query->whereHas('presenca', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        $aulas = $query->paginate(15)->withQueryString();

        $totalAulas = (clone $query)->count();
        $comPresenca = (clone $query)->whereHas('presenca')->count();
        $finalizadas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'finalizada'))
            ->count();
        $abertas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'aberta'))
            ->count();

        $semPresenca = max($totalAulas - $comPresenca, 0);
        $cobertura = $totalAulas > 0 ? round(($comPresenca / $totalAulas) * 100, 1) : 0;

        $limiteDias = 7;
        $aulasPendentesAntigas = (clone $query)
            ->whereDoesntHave('presenca')
            ->where('data', '<=', now()->subDays($limiteDias)->toDateString())
            ->count();

        $chartStatus = [
            'labels' => ['Sem presenca', 'Aberta', 'Finalizada'],
            'data'   => [$semPresenca, $abertas, $finalizadas],
        ];

        $turmas = Turma::where('id', $aluno->turma_id)->get();
        $disciplinas = Disciplina::orderBy('nome')->get();

        return view('admin.gestao_academica.presencas.index', [
            'aulas' => $aulas,
            'inicio' => $inicio,
            'fim' => $fim,
            'totalAulas' => $totalAulas,
            'comPresenca' => $comPresenca,
            'semPresenca' => $semPresenca,
            'abertas' => $abertas,
            'finalizadas' => $finalizadas,
            'cobertura' => $cobertura,
            'chartStatus' => $chartStatus,
            'aulasPendentesAntigas' => $aulasPendentesAntigas,
            'limiteDias' => $limiteDias,
            'turmas' => $turmas,
            'professores' => collect(),
            'disciplinas' => $disciplinas,
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }

    public function show(Presenca $presenca)
    {
        $aluno = $this->aluno();
        abort_unless($presenca->turma_id == $aluno->turma_id, 403);

        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel',
            'alunos.justificativa',
        ]);

        return view('admin.gestao_academica.presencas.show', [
            'presenca' => $presenca,
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }
}
