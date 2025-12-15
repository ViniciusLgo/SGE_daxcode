<?php

namespace App\Http\Controllers\Admin\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\UserDocument;
use App\Models\User;

class SecretariaDashboardController extends Controller
{
    public function index()
    {
        // Totais simples (sem status)
        $totalAlunos = Aluno::count();
        $totalTurmas = Turma::count();

        // EXEMPLO: Pendências de documentos (quando implementarmos documentos obrigatórios)
        // Por enquanto: retorna zero
        $pendencias = 0;

        // EXEMPLO: Atendimentos recentes
        // Futuro: tabela secretaria_atendimentos
        // Por agora: mock para a tabela ficar exibida sem erro
        $atendimentosRecentes = [
            ['tipo' => 'Matrícula', 'aluno' => '—', 'data' => now()->format('d/m/Y'), 'status' => 'Concluído'],
            ['tipo' => 'Declaração', 'aluno' => '—', 'data' => now()->format('d/m/Y'), 'status' => 'Pendente'],
        ];

        return view('admin.secretaria.dashboard', compact(
            'totalAlunos',
            'totalTurmas',
            'pendencias',
            'atendimentosRecentes'
        ));
    }
}
