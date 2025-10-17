<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $disciplinas = Disciplina::query()
            ->with('professor')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('descricao', 'like', "%{$search}%")
                        ->orWhereHas('professor', function ($professorQuery) use ($search) {
                            $professorQuery->where('nome', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('disciplinas.index', compact('disciplinas', 'search'));
    }

    public function create(Request $request)
    {
        $professores = Professor::query()->orderBy('nome')->pluck('nome', 'id');
        $selectedProfessor = $request->input('professor_id');

        return view('disciplinas.create', compact('professores', 'selectedProfessor'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'professor_id' => ['required', 'exists:professores,id'],
            'nome' => ['required', 'string', 'max:255'],
            'carga_horaria' => ['nullable', 'integer', 'between:1,500'],
            'descricao' => ['nullable', 'string'],
        ]);

        Disciplina::create($validated);

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina cadastrada com sucesso.');
    }

    public function show(Disciplina $disciplina)
    {
        $disciplina->load('professor');

        return view('disciplinas.show', compact('disciplina'));
    }

    public function edit(Disciplina $disciplina)
    {
        $professores = Professor::query()->orderBy('nome')->pluck('nome', 'id');
        $selectedProfessor = $disciplina->professor_id;

        return view('disciplinas.edit', compact('disciplina', 'professores', 'selectedProfessor'));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $validated = $request->validate([
            'professor_id' => ['required', 'exists:professores,id'],
            'nome' => ['required', 'string', 'max:255'],
            'carga_horaria' => ['nullable', 'integer', 'between:1,500'],
            'descricao' => ['nullable', 'string'],
        ]);

        $disciplina->update($validated);

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina removida com sucesso.');
    }
}
