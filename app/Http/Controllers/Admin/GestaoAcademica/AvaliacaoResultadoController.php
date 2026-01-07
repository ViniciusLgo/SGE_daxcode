<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use App\Models\Aluno;
use App\Models\AvaliacaoResultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvaliacaoResultadoController extends Controller
{
    /**
     * Tela de lancamento / visualizacao dos resultados
     */
    public function index(Avaliacao $avaliacao)
    {
        /**
         * Se a avaliacao estiver encerrada,
         * a tela passa a ser somente leitura
         */
        $somenteLeitura = $avaliacao->status === 'encerrada';

        /**
         * Alunos ATIVOS da turma vinculada a avaliacao
         * Regra correta:
         * - aluno ativo
         * - matricula ativa
         * - mesma turma da avaliacao
         */
        $alunos = Aluno::ativos()
            ->with('user')
            ->whereHas('matriculaModel', function ($q) use ($avaliacao) {
                $q->where('turma_id', $avaliacao->turma_id);
            })
            ->orderBy(
            // Ordena pelo nome do usuario
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'alunos.user_id')
            )
            ->get();

        /**
         * Resultados ja lancados,
         * indexados por aluno_id
         */
        $resultados = AvaliacaoResultado::where('avaliacao_id', $avaliacao->id)
            ->get()
            ->keyBy('aluno_id');

        return view('admin.gestao_academica.avaliacoes.resultados', compact(
            'avaliacao',
            'alunos',
            'resultados',
            'somenteLeitura'
        ));
    }

    /**
     * Salvar resultados em lote
     */
    public function store(Request $request, Avaliacao $avaliacao)
    {
        /**
         * Bloqueio de seguranca:
         * avaliacao encerrada nao pode ser alterada
         */
        if ($avaliacao->status === 'encerrada') {
            return back()->with(
                'error',
                'Avaliacao encerrada. Nao e possivel alterar resultados.'
            );
        }

        $dados = $request->input('resultados', []);

        foreach ($dados as $alunoId => $resultado) {

            /**
             * Cria ou atualiza o resultado do aluno
             */
            $registro = AvaliacaoResultado::firstOrNew([
                'avaliacao_id' => $avaliacao->id,
                'aluno_id'     => $alunoId,
            ]);

            $registro->nota       = $resultado['nota'] ?? null;
            $registro->observacao = $resultado['observacao'] ?? null;
            $registro->entregue   = isset($resultado['entregue']);

            /**
             * Upload do arquivo (se houver)
             */
            if ($request->hasFile("resultados.$alunoId.arquivo")) {

                $path = $request->file("resultados.$alunoId.arquivo")
                    ->store('avaliacoes/resultados', 'public');

                // Remove arquivo antigo
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
