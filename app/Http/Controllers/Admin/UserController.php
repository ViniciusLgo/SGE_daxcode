<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderByDesc('id')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'tipo'     => 'required|in:admin,aluno,professor,responsavel',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 🔹 Criação do usuário
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo'     => $validated['tipo'],
        ]);

        // 🔹 Se for aluno, cria o perfil correspondente
        if ($user->tipo === 'aluno') {
            // Verifica se existe turma padrão
            $turmaPadrao = \App\Models\Turma::where('id', 999)->first();

            // Se não existir, cria
            if (!$turmaPadrao) {
                $turmaPadrao = \App\Models\Turma::create([
                    'id' => 999,
                    'nome' => 'Turma Padrão',
                    'ano' => date('Y'),
                    'turno' => 'Indefinido',
                    'descricao' => 'Turma criada automaticamente para alunos sem turma atribuída',
                ]);
            }

            // Cria o aluno vinculado
            Aluno::create([
                'user_id'   => $user->id,
                'turma_id'  => $turmaPadrao->id,
                'matricula' => 'A' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'telefone'  => null,
                'foto_perfil' => null,
            ]);
        }

        // 🔹 Professor
        elseif ($user->tipo === 'professor') {
            Professor::create([
                'user_id' => $user->id,
                'especializacao' => null,
            ]);
        }

        // 🔹 Responsável
        elseif ($user->tipo === 'responsavel') {
            Responsavel::create([
                'user_id' => $user->id,
                'cpf' => null,
                'grau_parentesco' => null,
            ]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', "Usuário ({$user->tipo}) e perfil criados com sucesso!");
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'tipo'     => 'required|in:admin,aluno,professor,responsavel',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // 🔹 Garante que o perfil correspondente exista
        if ($user->tipo === 'aluno' && !$user->aluno) {
            $turmaPadrao = \App\Models\Turma::where('id', 999)->first() ?? \App\Models\Turma::create([
                'id' => 999,
                'nome' => 'Turma Padrão',
                'ano' => date('Y'),
                'turno' => 'Indefinido',
                'descricao' => 'Turma criada automaticamente',
            ]);

            Aluno::create([
                'user_id'   => $user->id,
                'turma_id'  => $turmaPadrao->id,
                'matricula' => 'A' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            ]);
        }

        if ($user->tipo === 'professor' && !$user->professor) {
            Professor::create(['user_id' => $user->id]);
        }

        if ($user->tipo === 'responsavel' && !$user->responsavel) {
            Responsavel::create(['user_id' => $user->id]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        match ($user->tipo) {
            'aluno'       => $user->aluno()?->delete(),
            'professor'   => $user->professor()?->delete(),
            'responsavel' => $user->responsavel()?->delete(),
            default       => null,
        };

        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuário e perfil excluídos com sucesso!');
    }
}
