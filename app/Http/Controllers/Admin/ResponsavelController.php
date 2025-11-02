<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsavel;
use App\Models\User;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResponsavelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $responsaveis = Responsavel::with(['user', 'alunos'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('alunos')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.responsaveis.index', compact('responsaveis', 'search'));
    }

    public function create()
    {
        $alunos = Aluno::with('user')->orderBy('id')->get();
        return view('admin.responsaveis.create', compact('alunos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'telefone'         => 'nullable|string|max:50',
            'cpf'              => 'nullable|string|max:14|unique:responsaveis,cpf',
            'grau_parentesco'  => 'nullable|string|max:50',
            'alunos'           => 'array',
        ]);

        // Cria o usuário
        $user = User::create([
            'name'     => $validated['nome'],
            'email'    => $validated['email'],
            'password' => Hash::make('123456789'),
            'tipo'     => 'responsavel',
        ]);

        // Cria o responsável vinculado
        $responsavel = Responsavel::create([
            'user_id'        => $user->id,
            'telefone'       => $validated['telefone'] ?? null,
            'cpf'            => $validated['cpf'] ?? null,
            'grau_parentesco'=> $validated['grau_parentesco'] ?? null,
        ]);

        // Vincula alunos, se houver
        if ($request->filled('alunos')) {
            $responsavel->alunos()->sync($request->alunos);
        }

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsável e usuário criados com sucesso!');
    }

    public function show($id)
    {
        $responsavel = Responsavel::with(['user', 'alunos'])->findOrFail($id);
        return view('admin.responsaveis.show', compact('responsavel'));
    }

    public function edit($id)
    {
        $alunos = Aluno::with('user')->orderBy('id')->get();
        $responsavel = Responsavel::with(['user', 'alunos'])->findOrFail($id);
        return view('admin.responsaveis.edit', compact('responsavel', 'alunos'));
    }

    public function update(Request $request, $id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nome'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $responsavel->user_id,
            'telefone'         => 'nullable|string|max:50',
            'cpf'              => 'nullable|string|max:14|unique:responsaveis,cpf,' . $responsavel->id,
            'grau_parentesco'  => 'nullable|string|max:50',
            'alunos'           => 'array',
        ]);

        $responsavel->user->update([
            'name'  => $validated['nome'],
            'email' => $validated['email'],
        ]);

        $responsavel->update($validated);
        $responsavel->alunos()->sync($request->alunos ?? []);

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsável e usuário atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);
        $responsavel->alunos()->detach();
        $responsavel->user?->delete();
        $responsavel->delete();

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsável e usuário excluídos com sucesso!');
    }
}
