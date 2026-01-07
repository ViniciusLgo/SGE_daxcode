<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Disciplina;
use App\Models\Professor;
use App\Models\Turma;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CONTADORES PRINCIPAIS (KPIs)
        |--------------------------------------------------------------------------
        */
        $alunosCount       = Aluno::count();
        $professoresCount  = Professor::count();
        $disciplinasCount  = Disciplina::count();
        $turmasCount       = Turma::count();

        /*
        |--------------------------------------------------------------------------
        | CONFIGURACOES DO SISTEMA
        |--------------------------------------------------------------------------
        */
        $settings = Setting::first();

        /*
        |--------------------------------------------------------------------------
        | ALUNOS POR TURMA (GRAFICO DE PIZZA)
        |--------------------------------------------------------------------------
        */
        $alunosPorTurma = Turma::withCount('alunos')
            ->orderByDesc('alunos_count')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PROFESSORES POR DISCIPLINA (GRAFICO DE BARRAS)
        |--------------------------------------------------------------------------
        */
        $professoresPorDisciplina = Disciplina::withCount('professores')
            ->orderByDesc('professores_count')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | EVOLUCAO DE MATRICULAS (ULTIMOS 12 MESES)
        |--------------------------------------------------------------------------
        */
        $evolucaoMatriculas = Aluno::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        /*
        |--------------------------------------------------------------------------
        | ALUNOS RECENTES
        |--------------------------------------------------------------------------
        */
        $recentAlunos = Aluno::with('turma')
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | OCUPACAO MEDIA DAS TURMAS (BASE: 30 ALUNOS)
        |--------------------------------------------------------------------------
        */
        $ocupacaoMedia = $turmasCount > 0
            ? round(($alunosCount / ($turmasCount * 30)) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | ALERTAS DO SISTEMA
        |--------------------------------------------------------------------------
        */
        $turmasSemProfessor = Turma::whereHas('disciplinaTurmas', function ($q) {
            $q->whereDoesntHave('professores');
        })->count();
        $alunosSemDocumentos = Aluno::doesntHave('documentos')->count();

        /*
        |--------------------------------------------------------------------------
        | DADOS DO DIA (PODENDO SER EVOLUIDO DEPOIS)
        |--------------------------------------------------------------------------
        */
        $aniversariantesHoje = Aluno::whereMonth('data_nascimento', now()->month)
            ->whereDay('data_nascimento', now()->day)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', compact(
            'alunosCount',
            'professoresCount',
            'disciplinasCount',
            'turmasCount',
            'settings',
            'alunosPorTurma',
            'professoresPorDisciplina',
            'evolucaoMatriculas',
            'recentAlunos',
            'ocupacaoMedia',
            'turmasSemProfessor',
            'alunosSemDocumentos',
            'aniversariantesHoje'
        ));
    }
}
