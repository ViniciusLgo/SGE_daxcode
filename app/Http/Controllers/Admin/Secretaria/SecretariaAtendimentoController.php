<?php

namespace App\Http\Controllers\Admin\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SecretariaAtendimento;
use App\Models\Aluno;
use App\Models\Responsavel;
use Illuminate\Http\Request;

class SecretariaAtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = SecretariaAtendimento::with(['aluno', 'responsavel'])
            ->orderByDesc('data_atendimento')
            ->paginate(10);

        return view('admin.secretaria.atendimentos.index', compact('atendimentos'));
    }

    public function create()
    {
        $alunos = Aluno::select('id', 'user_id', 'turma_id')
            ->with(['responsaveis.user', 'user:id,name'])
            ->orderBy('id')
            ->get();
        $responsaveis = Responsavel::with('user')
            ->orderBy('id')
            ->get();

        return view('admin.secretaria.atendimentos.create', compact(
            'alunos',
            'responsaveis'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'status' => 'required|in:pendente,concluido,cancelado',
            'aluno_id' => 'nullable|exists:alunos,id',
            'responsavel_id' => 'nullable|exists:responsaveis,id',
            'observacao' => 'nullable|string',
            'data_atendimento' => 'required|date',
        ]);

        SecretariaAtendimento::create($validated);

        return redirect()
            ->route('admin.secretaria.atendimentos.index')
            ->with('success', 'Atendimento registrado com sucesso.');
    }

    public function edit(SecretariaAtendimento $atendimento)
    {
        $alunos = Aluno::select('id', 'user_id', 'turma_id')
            ->with(['responsaveis.user', 'user:id,name'])
            ->orderBy('id')
            ->get();
        $responsaveis = Responsavel::with('user')
            ->orderBy('id')
            ->get();

        return view('admin.secretaria.atendimentos.edit', compact(
            'atendimento',
            'alunos',
            'responsaveis'
        ));
    }



    public function show(SecretariaAtendimento $atendimento)
    {
        // Garante que relações usadas na view estejam carregadas
        $atendimento->load([
            'aluno.user',
            'responsavel.user',
        ]);

        return view('admin.secretaria.atendimentos.show', compact('atendimento'));
    }
    public function update(Request $request, SecretariaAtendimento $atendimento)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:255',
            'status' => 'required|in:pendente,concluido,cancelado',
            'aluno_id' => 'nullable|exists:alunos,id',
            'responsavel_id' => 'nullable|exists:responsaveis,id',
            'observacao' => 'nullable|string',
            'data_atendimento' => 'required|date',
        ]);

        $atendimento->update($validated);

        return redirect()
            ->route('admin.secretaria.atendimentos.index')
            ->with('success', 'Atendimento atualizado com sucesso.');
    }


    public function responsaveisDoAluno(Aluno $aluno)
    {
        return response()->json(
            $aluno->responsaveis()
                ->with('user:id,name')
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'nome' => $r->user->name ?? 'Responsável'
                ])
        );
    }

    public function destroy(SecretariaAtendimento $atendimento)
    {
        $atendimento->delete();

        return redirect()
            ->route('admin.secretaria.atendimentos.index')
            ->with('success', 'Atendimento removido com sucesso.');
    }
}
