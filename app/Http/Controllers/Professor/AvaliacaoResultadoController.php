<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use App\Models\AvaliacaoResultado;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AvaliacaoResultadoController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    private function avaliacaoPermitida(Avaliacao $avaliacao, int $professorId): bool
    {
        return DB::table('disciplina_turmas')
            ->join('disciplina_turma_professor', 'disciplina_turma_professor.disciplina_turma_id', '=', 'disciplina_turmas.id')
            ->where('disciplina_turma_professor.professor_id', $professorId)
            ->where('disciplina_turmas.turma_id', $avaliacao->turma_id)
            ->where('disciplina_turmas.disciplina_id', $avaliacao->disciplina_id)
            ->exists();
    }

    public function index(Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->avaliacaoPermitida($avaliacao, $professorId), 403);

        $somenteLeitura = $avaliacao->status === 'encerrada';

        $alunos = Aluno::ativos()
            ->with('user')
            ->whereHas('matriculaModel', function ($q) use ($avaliacao) {
                $q->where('turma_id', $avaliacao->turma_id);
            })
            ->orderBy(
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'alunos.user_id')
            )
            ->get();

        $resultados = AvaliacaoResultado::where('avaliacao_id', $avaliacao->id)
            ->get()
            ->keyBy('aluno_id');

        return view('admin.gestao_academica.avaliacoes.resultados', [
            'avaliacao' => $avaliacao,
            'alunos' => $alunos,
            'resultados' => $resultados,
            'somenteLeitura' => $somenteLeitura,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function store(Request $request, Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->avaliacaoPermitida($avaliacao, $professorId), 403);

        if ($avaliacao->status === 'encerrada') {
            return back()->with(
                'error',
                'Avaliacao encerrada. Nao e possivel alterar resultados.'
            );
        }

        $dados = $request->input('resultados', []);

        foreach ($dados as $alunoId => $resultado) {
            $registro = AvaliacaoResultado::firstOrNew([
                'avaliacao_id' => $avaliacao->id,
                'aluno_id'     => $alunoId,
            ]);

            $registro->nota       = $resultado['nota'] ?? null;
            $registro->observacao = $resultado['observacao'] ?? null;
            $registro->entregue   = isset($resultado['entregue']);

            if ($request->hasFile("resultados.$alunoId.arquivo")) {
                $path = $request->file("resultados.$alunoId.arquivo")
                    ->store('avaliacoes/resultados', 'public');

                if ($registro->arquivo) {
                    Storage::disk('public')->delete($registro->arquivo);
                }

                $registro->arquivo = $path;
            }

            $registro->save();
        }

        return back()->with('success', 'Resultados salvos com sucesso.');
    }
}
