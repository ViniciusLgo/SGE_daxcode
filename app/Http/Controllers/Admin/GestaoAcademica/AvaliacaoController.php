<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avaliacao;
use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\Professor;

class AvaliacaoController extends Controller
{
    /**
     * Lista todas as avaliacoes
     */
    public function index()
    {
        $avaliacoes = Avaliacao::with(['turma', 'disciplina'])
            ->orderBy('data_avaliacao', 'desc')
            ->paginate(10);

        return view(
            'admin.gestao_academica.avaliacoes.index',
            compact('avaliacoes')
        );
    }

    /**
     * Formulario de criacao
     */
    public function create()
    {
        $turmas = Turma::orderBy('nome')->get();
        $disciplinas = Disciplina::orderBy('nome')->get();
        $professores = Professor::with('user')->get();

        return view('admin.gestao_academica.avaliacoes.create', compact(
            'turmas',
            'disciplinas',
            'professores'
        ));
    }

    /**
     * Salvar nova avaliacao
     */
    public function store(Request $request)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'titulo'         => 'required|string|max:255',
            'tipo'           => 'required|in:prova,trabalho,atividade,recuperacao',
            'data_avaliacao' => 'required|date',
        ]);

        Avaliacao::create([
            'turma_id'       => $request->turma_id,
            'disciplina_id'  => $request->disciplina_id,
            'titulo'         => $request->titulo,
            'tipo'           => $request->tipo,
            'data_avaliacao' => $request->data_avaliacao,
            'status'         => 'aberta',
        ]);

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliacao criada com sucesso.');
    }

    /**
     * Editar avaliacao
     */
    public function edit(Avaliacao $avaliacao)
    {
        $turmas = Turma::orderBy('nome')->get();
        $disciplinas = Disciplina::orderBy('nome')->get();
        $professores = Professor::with('user')->get();

        return view('admin.gestao_academica.avaliacoes.edit', compact(
            'avaliacao',
            'turmas',
            'disciplinas',
            'professores'
        ));
    }

    /**
     * Atualizar avaliacao
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        $request->validate([
            'turma_id'        => 'required|exists:turmas,id',
            'disciplina_id'   => 'required|exists:disciplinas,id',
            'titulo'          => 'required|string|max:255',
            'tipo'            => 'required|in:prova,trabalho,atividade,recuperacao',
            'data_avaliacao'  => 'required|date',
            'status'          => 'required|in:aberta,encerrada',
        ]);

        $avaliacao->update($request->only([
            'turma_id',
            'disciplina_id',
            'titulo',
            'tipo',
            'data_avaliacao',
            'status',
        ]));

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliacao atualizada com sucesso.');
    }


    /**
     * Encerrar avaliacao
     */
    public function encerrar(Avaliacao $avaliacao)
    {
        $avaliacao->update(['status' => 'encerrada']);

        return back()->with('success', 'Avaliacao encerrada.');
    }
    /**
     * Reabrir avaliacao encerrada
     */
    public function reabrir(Avaliacao $avaliacao)
    {
        if ($avaliacao->status === 'encerrada') {
            $avaliacao->update([
                'status' => 'aberta',
            ]);
        }

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.edit', $avaliacao)
            ->with('success', 'Avaliacao reaberta com sucesso.');
    }

    /**
     * Remove a avaliacao do sistema
     */
    public function destroy(Avaliacao $avaliacao)
    {
        //  Bloqueia APENAS exclusao
        if ($avaliacao->resultados()->exists()) {
            return redirect()
                ->route('admin.gestao_academica.avaliacoes.index')
                ->with('error', 'Nao e possivel excluir uma avaliacao que ja possui resultados.');
        }

        $avaliacao->delete();

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliacao excluida com sucesso.');
    }


}
