<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlunoController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $alunos = Aluno::query()
            ->with('turma')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('matricula', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('nome')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('alunos.index', compact('alunos', 'search'));
    }

    public function create(Request $request)
    {
        $turmas = Turma::query()->orderBy('nome')->pluck('nome', 'id');
        $selectedTurma = $request->input('turma_id');

        return view('alunos.create', compact('turmas', 'selectedTurma'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'turma_id' => ['required', 'exists:turmas,id'],
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:alunos,email'],
            'matricula' => ['required', 'string', 'max:25', 'unique:alunos,matricula'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        Aluno::create($validated);

        return redirect()
            ->route('admin.alunos.index')
            ->with('status', 'Aluno cadastrado com sucesso.');
    }

    public function show(Aluno $aluno)
    {
        $aluno->load('turma');

        return view('alunos.show', compact('aluno'));
    }

    public function edit(Aluno $aluno)
    {
        $turmas = Turma::query()->orderBy('nome')->pluck('nome', 'id');

        $selectedTurma = $aluno->turma_id;

        return view('alunos.edit', compact('aluno', 'turmas', 'selectedTurma'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $validated = $request->validate([
            'turma_id' => ['required', 'exists:turmas,id'],
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('alunos', 'email')->ignore($aluno->id)],
            'matricula' => ['required', 'string', 'max:25', Rule::unique('alunos', 'matricula')->ignore($aluno->id)],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        $aluno->update($validated);

        return redirect()
            ->route('admin.alunos.index')
            ->with('status', 'Dados do aluno atualizados com sucesso.');
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return redirect()
            ->route('admin.alunos.index')
            ->with('status', 'Aluno removido com sucesso.');
    }
}
