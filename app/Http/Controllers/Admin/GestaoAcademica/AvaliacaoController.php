<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * LISTAGEM
     */
    public function index()
    {
        // Carrega relações para evitar N+1
        $avaliacoes = Avaliacao::with(['turma', 'disciplina', 'professor'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.gestao_academica.avaliacoes.index', compact('avaliacoes'));
    }

    /**
     * FORMULÁRIO DE CRIAÇÃO
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
     * SALVAR NOVA AVALIAÇÃO
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'        => 'required|string|max:255',
            'turma_id'      => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'professor_id'  => 'required|exists:professores,id',
            'tipo'          => 'required|string',
            'peso'          => 'nullable|numeric|min:0|max:10',
            'data_avaliacao'=> 'nullable|date',
        ]);

        Avaliacao::create([
            'titulo'         => $validated['titulo'],
            'turma_id'       => $validated['turma_id'],
            'disciplina_id'  => $validated['disciplina_id'],
            'professor_id'   => $validated['professor_id'],
            'tipo'           => $validated['tipo'],
            'peso'           => $validated['peso'] ?? 1,
            'data_avaliacao' => $request->input('data_avaliacao'),

            // 🔹 COMEÇA COMO ATIVA (definido por você)
            'status'         => 'ativa',
        ]);

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliação cadastrada com sucesso.');
    }

    /**
     * FORMULÁRIO DE EDIÇÃO
     */
    public function edit(Avaliacao $avaliacao)
    {
        // 🔴 REGRA: se estiver encerrada, NÃO pode editar
        if ($avaliacao->status === 'encerrada') {
            return redirect()
                ->route('admin.gestao_academica.avaliacoes.index')
                ->with('warning', 'Avaliação encerrada. Reabra para editar.');
        }

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
     * ATUALIZAR AVALIAÇÃO
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        // 🔴 Segurança extra: não atualizar se estiver encerrada
        if ($avaliacao->status === 'encerrada') {
            return back()->with('error', 'Avaliação encerrada não pode ser alterada.');
        }

        $validated = $request->validate([
            'titulo'        => 'required|string|max:255',
            'turma_id'      => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'professor_id'  => 'required|exists:professores,id',
            'tipo'          => 'required|string',
            'peso'          => 'nullable|numeric|min:0|max:10',
            'data_avaliacao'=> 'nullable|date',
        ]);

        $avaliacao->update($validated);

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliação atualizada com sucesso.');
    }

    /**
     * EXCLUIR (SE NÃO ESTIVER ENCERRADA)
     */
    public function destroy(Avaliacao $avaliacao)
    {
        if ($avaliacao->status === 'encerrada') {
            return back()->with('error', 'Avaliação encerrada não pode ser excluída.');
        }

        $avaliacao->delete();

        return back()->with('success', 'Avaliação removida com sucesso.');
    }

    /**
     * ENCERRAR AVALIAÇÃO
     */
    public function encerrar(Avaliacao $avaliacao)
    {
        if ($avaliacao->status === 'encerrada') {
            return back()->with('warning', 'Esta avaliação já está encerrada.');
        }

        $avaliacao->update([
            'status' => 'encerrada',
        ]);

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliação encerrada com sucesso.');
    }

    /**
     * 🔓 REABRIR AVALIAÇÃO (VOLTA PARA ATIVA)
     */
    public function reabrir(Avaliacao $avaliacao)
    {
        if ($avaliacao->status !== 'encerrada') {
            return back()->with('warning', 'Apenas avaliações encerradas podem ser reabertas.');
        }

        $avaliacao->update([
            'status' => 'ativa',
        ]);

        return back()->with('success', 'Avaliação reaberta. Agora pode ser editada.');
    }
}
