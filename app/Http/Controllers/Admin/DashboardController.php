<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Disciplina;
use App\Models\Professor;
use App\Models\Turma;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores principais
        $alunosCount = Aluno::count();
        $professoresCount = Professor::count();
        $disciplinasCount = Disciplina::count();
        $turmasCount = Turma::count();

        // Configurações do sistema
        $settings = Setting::first();

        // Alunos por turma (para gráfico de pizza)
        $alunosPorTurma = Turma::withCount('alunos')
            ->get()
            ->map(fn($t) => ['nome' => $t->nome, 'total' => $t->alunos_count]);

        // Professores por disciplina (para gráfico de barras)
        $professoresPorDisciplina = Disciplina::withCount('professores')
            ->get()
            ->map(fn($d) => ['nome' => $d->nome, 'total' => $d->professores_count]);

        // Evolução de matrículas (exemplo: agrupando por mês)
        $evolucaoMatriculas = Aluno::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Alunos recentes
        $recentAlunos = Aluno::latest()->take(5)->get();

        // Ocupação média
        $ocupacaoTurmas = Turma::withCount('alunos')->get();
        $ocupacaoMedia = $turmasCount > 0
            ? round($ocupacaoTurmas->avg('alunos_count') / 30 * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'alunosCount',
            'professoresCount',
            'disciplinasCount',
            'turmasCount',
            'alunosPorTurma',
            'professoresPorDisciplina',
            'evolucaoMatriculas',
            'ocupacaoMedia',
            'recentAlunos',
            'settings'
        ));
    }
}
