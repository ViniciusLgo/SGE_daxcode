<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class DisciplinaController extends Controller
{
    private const PER_PAGE = 10;

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
                        ->orWhereHas('professores.user', function ($profQuery) use ($search) {
                            $profQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.disciplinas.index', compact('disciplinas', 'search'));
    }

    public function create()
    {
        // Lista de professores: id => nome (puxando do users)
        $professores = \App\Models\Professor::with('user')
            ->get()
            ->filter(fn($p) => $p->user) // garante user vinculado
            ->mapWithKeys(fn($p) => [$p->id => $p->user->name])
            ->sort()
            ->toArray();

        $turmas = \App\Models\Turma::orderBy('nome')->pluck('nome', 'id')->toArray();

        // Na criação não há selecionados ainda:
        $professoresSelecionados = [];

        return view('admin.disciplinas.create', compact('professores', 'turmas', 'professoresSelecionados'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'carga_horaria' => ['required', 'numeric', 'min:1'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'professores' => ['required', 'array'],
            'professores.*' => ['exists:professores,id'],
        ]);

        $disciplina = Disciplina::create([
            'nome' => $validated['nome'],
            'carga_horaria' => $validated['carga_horaria'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        if (!empty($validated['professores'])) {
            $disciplina->professores()->sync($validated['professores']);
        }

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina cadastrada com sucesso!');
    }


    public function edit($id)
    {
        $disciplina = Disciplina::with('professores.user')->findOrFail($id);

        // id => nome
        $professores = \App\Models\Professor::with('user')
            ->get()
            ->filter(fn($p) => $p->user)
            ->mapWithKeys(fn($p) => [$p->id => $p->user->name])
            ->sort()
            ->toArray();

        // IDs já vinculados à disciplina
        $professoresSelecionados = $disciplina->professores->pluck('id')->toArray();

        $turmas = \App\Models\Turma::orderBy('nome')->pluck('nome', 'id')->toArray();

        return view('admin.disciplinas.edit', compact('disciplina', 'professores', 'turmas', 'professoresSelecionados'));
    }




    public function update(Request $request, Disciplina $disciplina)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'carga_horaria' => ['required', 'numeric', 'min:1'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'professores' => ['required', 'array'],
            'professores.*' => ['exists:professores,id'],
        ]);

        $disciplina->update([
            'nome' => $validated['nome'],
            'carga_horaria' => $validated['carga_horaria'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        $disciplina->professores()->sync($validated['professores']);

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina atualizada com sucesso!');
    }

    public function show(Disciplina $disciplina)
    {
        $disciplina->load('professores');
        return view('admin.disciplinas.show', compact('disciplina'));

    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->professores()->detach();
        $disciplina->delete();

        return redirect()
            ->route('admin.disciplinas.index')
            ->with('status', 'Disciplina removida com sucesso!');
    }
}
