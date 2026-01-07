<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaTurma;
use App\Models\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    public function index()
    {
        $professorId = $this->professorId();

        $turmas = Turma::whereHas('disciplinaTurmas.professores', function ($q) use ($professorId) {
            $q->where('professor_id', $professorId);
        })
            ->withCount('alunos')
            ->orderBy('nome')
            ->get();

        return view('professores.turmas.index', compact('turmas'));
    }

    public function show(Turma $turma)
    {
        $professorId = $this->professorId();

        $temAcesso = DisciplinaTurma::where('turma_id', $turma->id)
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->exists();

        abort_unless($temAcesso, 403);

        $turma->load(['alunos.user']);

        $disciplinas = DisciplinaTurma::with('disciplina')
            ->where('turma_id', $turma->id)
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->get();

        return view('professores.turmas.show', compact('turma', 'disciplinas'));
    }
}
