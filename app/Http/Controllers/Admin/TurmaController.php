<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $turmas = Turma::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('turno', 'like', "%{$search}%")
                        ->orWhere('descricao', 'like', "%{$search}%");
                });
            })
            ->withCount('alunos')
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.turmas.index', compact('turmas', 'search'));
    }

    public function create()
    {
        return view('admin.turmas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'turno' => ['nullable', 'string', 'max:50'],
            'ano' => ['nullable', 'integer', 'between:2000,2100'],
            'descricao' => ['nullable', 'string'],
        ]);

        Turma::create($validated);

        return redirect()
            ->route('admin.turmas.index')
            ->with('status', 'Turma criada com sucesso.');
    }

    public function show($id)
    {
        $turma = \App\Models\Turma::with([
            'disciplinas',
            'disciplinas.professores' // cada disciplina tem professores via pivot
        ])->findOrFail($id);

        return view('admin.turmas.show', compact('turma'));
    }




    public function edit(Turma $turma)
    {
        return view('admin.turmas.edit', compact('turma'));
    }

    public function update(Request $request, Turma $turma)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'turno' => ['nullable', 'string', 'max:50'],
            'ano' => ['nullable', 'integer', 'between:2000,2100'],
            'descricao' => ['nullable', 'string'],
        ]);

        $turma->update($validated);

        return redirect()
            ->route('admin.turmas.index')
            ->with('status', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();

        return redirect()
            ->route('admin.turmas.index')
            ->with('status', 'Turma removida com sucesso.');
    }

    public function adicionarDisciplina(Request $request, $turmaId)
    {
        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'professores' => 'nullable|array|max:4',
            'professores.*' => 'exists:professores,id',
            'ano_letivo' => 'nullable|string|max:10',
            'observacao' => 'nullable|string|max:255'
        ]);

        $vinculo = \App\Models\DisciplinaTurma::create([
            'turma_id' => $turmaId,
            'disciplina_id' => $request->disciplina_id,
            'ano_letivo' => $request->ano_letivo ?? date('Y'),
            'observacao' => $request->observacao,
        ]);

        if ($request->filled('professores')) {
            foreach ($request->professores as $profId) {
                \App\Models\DisciplinaTurmaProfessor::create([
                    'disciplina_turma_id' => $vinculo->id,
                    'professor_id' => $profId
                ]);
            }
        }

        return redirect()->route('admin.turmas.show', $turmaId)
            ->with('success', 'Disciplina vinculada com sucesso!');
    }

    // --- Remover disciplina da turma ---
    public function removerDisciplina($turmaId, $vinculoId)
    {
        $vinculo = \App\Models\DisciplinaTurma::findOrFail($vinculoId);
        $vinculo->professores()->detach();
        $vinculo->delete();

        return redirect()->route('admin.turmas.show', $turmaId)
            ->with('success', 'Vínculo removido com sucesso!');
    }

}
