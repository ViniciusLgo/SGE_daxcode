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
     * Tela de lançamento / visualização dos resultados
     */
    public function index(Avaliacao $avaliacao)
    {
        // Regra: se avaliação encerrada, tela será somente leitura
        $somenteLeitura = $avaliacao->status === 'encerrada';

        // Alunos da turma da avaliação
        $alunos = Aluno::with('user')
            ->join('users', 'users.id', '=', 'alunos.user_id')
            ->where('alunos.turma_id', $avaliacao->turma_id)
            ->orderBy('users.name')
            ->select('alunos.*')
            ->get();



        // Resultados já existentes (indexados por aluno_id)
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
        // Bloqueio de segurança
        if ($avaliacao->status === 'encerrada') {
            return back()->with('error', 'Avaliação encerrada. Não é possível alterar resultados.');
        }

        $dados = $request->input('resultados', []);

        foreach ($dados as $alunoId => $resultado) {

            $registro = AvaliacaoResultado::firstOrNew([
                'avaliacao_id' => $avaliacao->id,
                'aluno_id'     => $alunoId,
            ]);

            $registro->nota = $resultado['nota'] ?? null;
            $registro->observacao = $resultado['observacao'] ?? null;
            $registro->entregue = isset($resultado['entregue']);

            // Upload de arquivo (se houver)
            if ($request->hasFile("resultados.$alunoId.arquivo")) {
                $path = $request->file("resultados.$alunoId.arquivo")
                    ->store('avaliacoes/resultados', 'public');

                // Remove arquivo antigo, se existir
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
