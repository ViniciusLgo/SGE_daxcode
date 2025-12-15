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
    /**
     * LISTAGEM DE RESPONSÁVEIS
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $responsaveis = Responsavel::with(['user'])
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

    /**
     * FORMULÁRIO DE CRIAÇÃO
     */
    public function create()
    {
        $alunos = Aluno::with(['user', 'turma'])->orderBy('id')->get();

        return view('admin.responsaveis.create', compact('alunos'));
    }

    /**
     * SALVAR NOVO RESPONSÁVEL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // USER
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6|confirmed',

            // RESPONSÁVEL
            'telefone'          => 'nullable|string|max:50',
            'cpf'               => 'nullable|string|max:14|unique:responsaveis,cpf',
            'grau_parentesco'   => 'nullable|string|max:50',

            // ALUNOS
            'alunos'            => 'array',
        ]);

        // ============================
        // 1. Criar usuário
        // ============================
        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'tipo'      => 'responsavel',
        ]);

        // ============================
        // 2. Criar responsável
        // ============================
        $responsavel = Responsavel::create([
            'user_id'          => $user->id,
            'telefone'         => $validated['telefone'] ?? null,
            'cpf'              => $validated['cpf'] ?? null,
            'grau_parentesco'  => $validated['grau_parentesco'] ?? null,
        ]);

        // ============================
        // 3. Vincular alunos
        // ============================
        if ($request->filled('alunos')) {
            $responsavel->alunos()->sync($request->alunos);
        }

        return redirect()
            ->route('admin.responsaveis.index')
            ->with('success', 'Responsável criado com sucesso!');
    }

    /**
     * EXIBIR RESPONSÁVEL
     */
    public function show($id)
    {
        $responsavel = Responsavel::with(['user', 'alunos.user', 'alunos.turma'])->findOrFail($id);

        return view('admin.responsaveis.show', compact('responsavel'));
    }

    /**
     * EDITAR RESPONSÁVEL
     */
    public function edit($id)
    {
        $responsavel = Responsavel::with(['user', 'alunos'])->findOrFail($id);
        $alunos = Aluno::with(['user', 'turma'])->orderBy('id')->get();

        return view('admin.responsaveis.edit', compact('responsavel', 'alunos'));
    }

    /**
     * ATUALIZAR RESPONSÁVEL
     */
    public function update(Request $request, $id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);

        $validated = $request->validate([
            // USER
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $responsavel->user_id,

            // RESPONSÁVEL
            'telefone'        => 'nullable|string|max:50',
            'cpf'             => 'nullable|string|max:14|unique:responsaveis,cpf,' . $responsavel->id,
            'grau_parentesco' => 'nullable|string|max:50',

            // ALUNOS
            'alunos'          => 'array',
        ]);

        // Atualiza USER
        $responsavel->user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Atualiza RESPONSÁVEL
        $responsavel->update([
            'telefone'        => $validated['telefone'] ?? null,
            'cpf'             => $validated['cpf'] ?? null,
            'grau_parentesco' => $validated['grau_parentesco'] ?? null,
        ]);

        // Atualiza pivot aluno ↔ responsável
        $responsavel->alunos()->sync($request->alunos ?? []);

        return redirect()
            ->route('admin.responsaveis.index')
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    /**
     * EXCLUIR RESPONSÁVEL
     */
    public function destroy($id)
    {
        $responsavel = Responsavel::with('user')->findOrFail($id);

        // Remove vínculos
        $responsavel->alunos()->detach();

        // Remove user vinculado
        if ($responsavel->user) {
            $responsavel->user->delete();
        }

        // Remove registro de responsável
        $responsavel->delete();

        return redirect()
            ->route('admin.responsaveis.index')
            ->with('success', 'Responsável excluído com sucesso!');
    }
}
