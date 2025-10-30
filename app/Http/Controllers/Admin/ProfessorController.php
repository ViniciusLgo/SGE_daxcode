<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * Listagem de professores com busca.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = \App\Models\Professor::query();

        if ($search) {
            $query->where('nome', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $professores = $query
            ->withCount('disciplinas')
            ->orderBy('nome')
            ->paginate(10);

        return view('admin.professores.index', compact('professores', 'search'));
    }


    /**
     * Formulário de criação.
     */
    public function create()
    {
        return view('admin.professores.create');
    }

    /**
     * Armazena um novo professor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:professores,email'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'especializacao' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }

        Professor::create($validated);

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Professor cadastrado com sucesso.');
    }

    /**
     * Exibe detalhes do professor.
     */
    public function show(Professor $professor)
    {
        $disciplinas = $professor->disciplinas()
            ->orderBy('nome')
            ->paginate(10);

        return view('admin.professores.show', compact('professor', 'disciplinas'));
    }


    /**
     * Formulário de edição.
     */
    public function edit(Professor $professor)
    {
        return view('professores.edit', compact('professor'));
    }

    /**
     * Atualiza o registro do professor.
     */
    public function update(Request $request, Professor $professor)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('professores', 'email')->ignore($professor->id)],
            'telefone' => ['nullable', 'string', 'max:20'],
            'especializacao' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }

        $professor->update($validated);

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Dados do professor atualizados com sucesso.');
    }

    /**
     * Remove o professor.
     */
    public function destroy(Professor $professor)
    {
        $professor->delete();

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Professor removido com sucesso.');
    }
}
