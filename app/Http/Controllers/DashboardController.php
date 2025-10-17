<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Disciplina;
use App\Models\Professor;
use App\Models\Turma;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'totalAlunos' => Aluno::count(),
            'totalProfessores' => Professor::count(),
            'totalDisciplinas' => Disciplina::count(),
            'totalTurmas' => Turma::count(),
        ]);
    }
}
