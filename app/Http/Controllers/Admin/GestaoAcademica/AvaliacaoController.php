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
     * Lista todas as avaliações
     */
    public function index()
    {
        $avaliacoes = Avaliacao::with(['turma', 'disciplina', 'professor'])
            ->orderByDesc('data_avaliacao')
            ->paginate(10);

        return view('admin.gestao_academica.avaliacoes.index', compact('avaliacoes'));
    }

    /**
     * Formulário de criação
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
     * Salvar nova avaliação
     */
    public function store(Request $request)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'professor_id'   => 'required|exists:professores,id',
            'titulo'         => 'required|string|max:255',
            'tipo'           => 'required|string|max:50',
            'data_avaliacao' => 'required|date',
        ]);

        Avaliacao::create([
            'turma_id'       => $request->turma_id,
            'disciplina_id'  => $request->disciplina_id,
            'professor_id'   => $request->professor_id,
            'titulo'         => $request->titulo,
            'tipo'           => $request->tipo,
            'data_avaliacao' => $request->data_avaliacao,
            'status'         => 'aberta',
        ]);

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliação criada com sucesso.');
    }

    /**
     * Editar avaliação
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
     * Atualizar avaliação
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'professor_id'   => 'required|exists:professores,id',
            'titulo'         => 'required|string|max:255',
            'tipo'           => 'required|string|max:50',
            'data_avaliacao' => 'required|date',
            'status'         => 'required|in:aberta,encerrada',
        ]);

        $avaliacao->update($request->all());

        return redirect()
            ->route('admin.gestao_academica.avaliacoes.index')
            ->with('success', 'Avaliação atualizada com sucesso.');
    }

    /**
     * Encerrar avaliação
     */
    public function encerrar(Avaliacao $avaliacao)
    {
        $avaliacao->update(['status' => 'encerrada']);

        return back()->with('success', 'Avaliação encerrada.');
    }
}
