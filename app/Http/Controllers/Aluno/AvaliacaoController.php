<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    private function aluno()
    {
        $aluno = auth()->user()?->aluno;
        abort_unless($aluno, 403);
        return $aluno;
    }

    public function index()
    {
        $aluno = $this->aluno();

        $avaliacoes = Avaliacao::with(['turma', 'disciplina'])
            ->where('turma_id', $aluno->turma_id)
            ->orderBy('data_avaliacao', 'desc')
            ->paginate(10);

        return view('admin.gestao_academica.avaliacoes.index', [
            'avaliacoes' => $avaliacoes,
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }
}
