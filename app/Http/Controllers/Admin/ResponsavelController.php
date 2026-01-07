<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsavel;
use App\Models\User;
use App\Models\Aluno;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $responsaveis = Responsavel::with('user')
            ->withCount('alunos')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.responsaveis.index', compact('responsaveis', 'search'));
    }

    /**
     * CREATE  recebe user_id
     */
    public function create(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $alunos = Aluno::select('id', 'user_id', 'turma_id')
            ->with(['user:id,name', 'turma:id,nome'])
            ->orderBy('id')
            ->get();

        return view('admin.responsaveis.create', compact('user', 'alunos'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'telefone'       => 'nullable|string|max:50',
            'cpf'            => 'nullable|string|max:14|unique:responsaveis,cpf',
            'grau_parentesco'=> 'nullable|string|max:50',
            'alunos'         => 'array',
        ]);

        $responsavel = Responsavel::create([
            'user_id'         => $validated['user_id'],
            'telefone'        => $validated['telefone'] ?? null,
            'cpf'             => $validated['cpf'] ?? null,
            'grau_parentesco' => $validated['grau_parentesco'] ?? null,
        ]);

        $responsavel->alunos()->sync($request->alunos ?? []);

        return redirect()
            ->route('admin.responsaveis.index')
            ->with('success', 'Responsavel cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $responsavel = Responsavel::with(['user', 'alunos'])->findOrFail($id);
        $alunos = Aluno::select('id', 'user_id', 'turma_id')
            ->with(['user:id,name', 'turma:id,nome'])
            ->orderBy('id')
            ->get();

        return view('admin.responsaveis.edit', compact('responsavel', 'alunos'));
    }

    public function update(Request $request, $id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);

        $validated = $request->validate([
            'user.name'  => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email,' . $responsavel->user->id,
            'telefone'   => 'nullable|string|max:50',
            'cpf'        => 'nullable|string|max:14|unique:responsaveis,cpf,' . $responsavel->id,
            'grau_parentesco' => 'nullable|string|max:50',
        ]);

        $responsavel->user->update($validated['user']);

        $responsavel->update([
            'telefone'        => $validated['telefone'] ?? null,
            'cpf'             => $validated['cpf'] ?? null,
            'grau_parentesco' => $validated['grau_parentesco'] ?? null,
        ]);

        $responsavel->alunos()->sync($request->alunos ?? []);

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsavel atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);

        $responsavel->alunos()->detach();
        $responsavel->user->delete();
        $responsavel->delete();

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsavel removido com sucesso!');
    }

    public function show($id)
    {
        $responsavel = Responsavel::with([
            'user',
            'alunos.user',
            'alunos.turma'
        ])->findOrFail($id);

        return view('admin.responsaveis.show', compact('responsavel'));
    }

}
