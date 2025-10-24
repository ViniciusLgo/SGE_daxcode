<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * Lista todas as disciplinas com filtro opcional.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $disciplinas = Disciplina::query()
            ->with('professores')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('descricao', 'like', "%{$search}%")
                        ->orWhereHas('professores', function ($professorQuery) use ($search) {
                            $professorQuery->where('nome', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('disciplinas.index', compact('disciplinas', 'search'));
    }

    /**
     * Exibe o formulário de criação de disciplina.
     */
    public function create()
    {
        $professores = Professor::orderBy('nome')->pluck('nome', 'id');
        return view('disciplinas.create', compact('professores'));
    }

    /**
     * Salva uma nova disciplina e seus professores.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'nullable|integer|between:1,500',
            'descricao' => 'nullable|string',
            'professores' => 'required|array',
            'professores.*' => 'exists:professores,id',
        ]);

        // Cria a disciplina
        $disciplina = Disciplina::create([
            'nome' => $validated['nome'],
            'carga_horaria' => $validated['carga_horaria'] ?? null,
            'descricao' => $validated['descricao'] ?? null,
        ]);

        // Vincula os professores selecionados
        $disciplina->professores()->attach($validated['professores']);

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina cadastrada com sucesso!');
    }

    /**
     * Exibe uma disciplina específica.
     */
    public function show(Disciplina $disciplina)
    {
        $disciplina->load('professores');
        return view('disciplinas.show', compact('disciplina'));
    }

    /**
     * Exibe o formulário de edição de disciplina.
     */
    public function edit(Disciplina $disciplina)
    {
        $professores = Professor::orderBy('nome')->pluck('nome', 'id');
        $selectedProfessores = $disciplina->professores->pluck('id')->toArray();

        return view('disciplinas.edit', compact('disciplina', 'professores', 'selectedProfessores'));
    }

    /**
     * Atualiza a disciplina e seus vínculos com professores.
     */
    public function update(Request $request, Disciplina $disciplina)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria' => 'nullable|integer|between:1,500',
            'descricao' => 'nullable|string',
            'professores' => 'required|array',
            'professores.*' => 'exists:professores,id',
        ]);

        $disciplina->update([
            'nome' => $validated['nome'],
            'carga_horaria' => $validated['carga_horaria'] ?? null,
            'descricao' => $validated['descricao'] ?? null,
        ]);

        // Sincroniza professores vinculados (remove antigos e adiciona novos)
        $disciplina->professores()->sync($validated['professores']);

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina atualizada com sucesso!');
    }

    /**
     * Exclui uma disciplina.
     */
    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina removida com sucesso!');
    }
}
