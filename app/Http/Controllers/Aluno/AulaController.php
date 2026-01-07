<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Disciplina;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AulaController extends Controller
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

        $query = Aula::with(['turma', 'disciplina', 'professor.user'])
            ->where('turma_id', $aluno->turma_id);

        $ordem = $request->get('ordem', 'asc');
        $query->orderBy('data', $ordem)->orderBy('hora_inicio', $ordem);

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data')) {
            $data = Carbon::createFromFormat('d/m/Y', $request->data)->format('Y-m-d');
            $query->whereDate('data', $data);
        }

        $aulas = $query->paginate(15)->withQueryString();

        $turmas = Turma::where('id', $aluno->turma_id)->get();
        $disciplinas = Disciplina::orderBy('nome')->get();

        return view('admin.aulas.index', [
            'aulas' => $aulas,
            'professores' => collect(),
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }

    public function show(Aula $aula)
    {
        $aluno = $this->aluno();
        abort_unless($aula->turma_id == $aluno->turma_id, 403);

        $aula->load(['turma', 'disciplina', 'professor.user']);

        return view('admin.aulas.show', [
            'aula' => $aula,
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }
}
