<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaTurma;
use App\Models\Disciplina;
use App\Models\Turma;
use App\Models\Professor;
use Illuminate\Http\Request;

class DisciplinaTurmaController extends Controller
{
    public function index()
    {
        $vinculos = DisciplinaTurma::with(['disciplina', 'turma', 'professor'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.disciplina_turma.index', compact('vinculos'));
    }

    public function create()
    {
        $disciplinas = Disciplina::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();
        $professores = Professor::orderBy('nome')->get();

        return view('admin.disciplina_turma.create', compact('disciplinas', 'turmas', 'professores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'turma_id' => 'required|exists:turmas,id',
            'professor_id' => 'nullable|exists:professores,id',
            'ano_letivo' => 'nullable|string|max:20',
            'observacao' => 'nullable|string|max:255',
        ]);

        DisciplinaTurma::create($data);

        return redirect()->route('admin.disciplina_turma.index')
            ->with('success', 'Vínculo criado com sucesso!');
    }

    public function edit($id)
    {
        $vinculo = DisciplinaTurma::with('professor')->findOrFail($id);
        $disciplinas = Disciplina::orderBy('nome')->get();
        $turmas = Turma::orderBy('nome')->get();
        $professores = Professor::orderBy('nome')->get();

        // Lista de IDs já vinculados (para pré-selecionar)
        $professoresVinculados = is_array($vinculo->professor_id)
            ? $vinculo->professor_id
            : [$vinculo->professor_id];

        return view('admin.disciplina_turma.edit', compact('vinculo', 'disciplinas', 'turmas', 'professores', 'professoresVinculados'));
    }

    public function update(Request $request, $id)
    {
        $vinculo = DisciplinaTurma::findOrFail($id);

        $data = $request->validate([
            'ano_letivo' => 'nullable|string|max:20',
            'observacao' => 'nullable|string|max:255',
            'professores' => 'nullable|string', // IDs separados por vírgula
        ]);

        // Atualiza campos básicos
        $vinculo->update([
            'ano_letivo' => $data['ano_letivo'] ?? null,
            'observacao' => $data['observacao'] ?? null,
        ]);

        // Sincroniza os professores na pivot
        $professoresIds = collect(explode(',', $data['professores'] ?? ''))
            ->filter()
            ->map(fn($id) => (int)$id)
            ->unique()
            ->toArray();

        $vinculo->professores()->sync($professoresIds);

        return redirect()->route('admin.disciplina_turma.index')
            ->with('success', 'Professores vinculados com sucesso!');
    }



    public function destroy($id)
    {
        $vinculo = DisciplinaTurma::findOrFail($id);
        $vinculo->delete();

        return redirect()->route('admin.disciplina_turma.index')
            ->with('success', 'Vínculo removido com sucesso!');
    }
}
