<?php

namespace App\Http\Controllers;

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

        return view('turmas.index', compact('turmas', 'search'));
    }

    public function create()
    {
        return view('turmas.create');
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

    public function show(Turma $turma)
    {
        $alunos = $turma->alunos()
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('turmas.show', compact('turma', 'alunos'));
    }

    public function edit(Turma $turma)
    {
        return view('turmas.edit', compact('turma'));
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
}
