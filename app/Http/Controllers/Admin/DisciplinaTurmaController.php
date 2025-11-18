<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\DisciplinaTurma;
use App\Models\DisciplinaTurmaProfessor;
use App\Models\Professor;

class DisciplinaTurmaController extends Controller
{
    /**
     * Tela para gerenciar todas as disciplinas da turma
     */
    public function edit($turmaId)
    {
        $turma = Turma::with([
            'disciplinas',
            'disciplinaTurmas.professores.user'
        ])->findOrFail($turmaId);

        $todasDisciplinas = Disciplina::orderBy('nome')->get();

        $professores = Professor::with('user')->get();

        return view('admin.disciplina_turma.edit', compact(
            'turma',
            'todasDisciplinas',
            'professores'
        ));
    }

    /**
     * Adicionar uma disciplina à turma
     */
    public function store(Request $request, $turmaId)
    {
        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_letivo' => 'nullable|string|max:10',
            'observacao' => 'nullable|string|max:255',
        ]);

        // Verificar se já existe vínculo
        $jaExiste = DisciplinaTurma::where('turma_id', $turmaId)
            ->where('disciplina_id', $request->disciplina_id)
            ->exists();

        if ($jaExiste) {
            return back()->with('error', 'Esta disciplina já está vinculada a esta turma.');
        }

        // cria o vínculo na tabela disciplina_turmas
        $vinculo = DisciplinaTurma::create([
            'turma_id' => $turmaId,
            'disciplina_id' => $request->disciplina_id,
            'ano_letivo' => $request->ano_letivo ?? date('Y'),
            'observacao' => $request->observacao,
        ]);

        return back()->with('success', 'Disciplina vinculada com sucesso!');
    }

    /**
     * Remover disciplina da turma
     */
    public function destroy($turmaId, $vinculoId)
    {
        $vinculo = DisciplinaTurma::findOrFail($vinculoId);

        // remover professores vinculados
        DisciplinaTurmaProfessor::where('disciplina_turma_id', $vinculo->id)->delete();

        // remover vínculo principal
        $vinculo->delete();

        return back()->with('success', 'Disciplina removida da turma.');
    }

    /**
     * Vincular professor a uma disciplina dentro da turma
     */
    public function vincularProfessor(Request $request, $turmaId, $vinculoId)
    {
        $request->validate([
            'professor_id' => 'required|exists:professores,id',
        ]);

        DisciplinaTurmaProfessor::firstOrCreate([
            'disciplina_turma_id' => $vinculoId,
            'professor_id' => $request->professor_id,
        ]);

        return back()->with('success', 'Professor vinculado com sucesso!');
    }

    /**
     * Remover professor do vínculo disciplina ↔ turma
     */
    public function removerProfessor($turmaId, $vinculoId, $professorId)
    {
        DisciplinaTurmaProfessor::where('disciplina_turma_id', $vinculoId)
            ->where('professor_id', $professorId)
            ->delete();

        return back()->with('success', 'Professor removido do vínculo.');
    }
}
