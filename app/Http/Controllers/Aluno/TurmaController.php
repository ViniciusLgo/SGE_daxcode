<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaTurma;
use App\Models\Turma;

class TurmaController extends Controller
{
    public function show()
    {
        $aluno = auth()->user()?->aluno;
        abort_unless($aluno, 403);

        $turma = Turma::with(['alunos.user'])->findOrFail($aluno->turma_id);

        $disciplinas = DisciplinaTurma::with('disciplina')
            ->where('turma_id', $turma->id)
            ->get();

        return view('alunos.turma', compact('turma', 'disciplinas'));
    }
}
