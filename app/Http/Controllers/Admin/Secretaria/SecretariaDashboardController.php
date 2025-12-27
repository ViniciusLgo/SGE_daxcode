<?php

namespace App\Http\Controllers\Admin\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\SecretariaAtendimento;

class SecretariaDashboardController extends Controller
{
    /**
     * Dashboard da Secretaria
     */
    public function index()
    {
        // Totais gerais
        $totalAlunos = Aluno::count();
        $totalTurmas = Turma::count();


        $pendencias = Aluno::whereHas('documents', function ($q) {
            $q->whereNull('arquivo');
        })->count();


        // Atendimentos pendentes
        $atendimentosPendentes = SecretariaAtendimento::where('status', 'pendente')->count();

        // Atendimentos recentes
        $atendimentosRecentes = SecretariaAtendimento::with(['aluno.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.secretaria.dashboard', compact(
            'totalAlunos',
            'totalTurmas',
            'pendencias',
            'atendimentosPendentes',
            'atendimentosRecentes'
        ));
    }
}
