<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $professores = Professor::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('especializacao', 'like', "%{$search}%");
                });
            })
            ->withCount('disciplinas')
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('professores.index', compact('professores', 'search'));
    }

    public function create()
    {
        return view('professores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:professores,email'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'especializacao' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }


        Professor::create($validated);

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Professor cadastrado com sucesso.');
    }

    public function show(Professor $professor)
    {
        $disciplinas = $professor->disciplinas()
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('professores.show', compact('professor', 'disciplinas'));
    }

    public function edit(Professor $professor)
    {
        return view('professores.edit', compact('professor'));
    }

    public function update(Request $request, Professor $professor)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('professores', 'email')->ignore($professor->id)],
            'telefone' => ['nullable', 'string', 'max:20'],
            'especializacao' => ['nullable', 'string', 'max:255'],
        ]);
        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('avatars/professores', 'public');
        }


        $professor->update($validated);

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Dados do professor atualizados com sucesso.');
    }

    public function destroy(Professor $professor)
    {
        $professor->delete();

        return redirect()
            ->route('admin.professores.index')
            ->with('status', 'Professor removido com sucesso.');
    }
}
