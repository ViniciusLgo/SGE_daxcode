<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\Responsavel;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\CodeGenerator;

class UserController extends Controller
{
    /**
     * LISTAGEM DE USUARIOS
     */
    public function index()
    {
        $usuarios = User::orderByDesc('id')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * FORMULARIO DE CRIACAO
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * STORE  CRIA USUARIO E PERFIL ASSOCIADO
     * Regra: User e a entidade primaria.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'tipo'     => 'required|in:admin,aluno,professor,responsavel',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tipo'     => $validated['tipo'],
        ]);

        return match ($user->tipo) {
            'aluno' => redirect()
                ->route('admin.alunos.create', ['user_id' => $user->id]),

            'professor' => redirect()
                ->route('admin.professores.create', ['user_id' => $user->id]),

            'responsavel' => redirect()
                ->route('admin.responsaveis.create', ['user_id' => $user->id]),

            default => redirect()
                ->route('admin.usuarios.index')
                ->with('success', 'Administrador criado com sucesso!')
        };
    }


    /**
     * FORMULARIO DE EDICAO
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('user'));
    }

    /**
     * UPDATE  ATUALIZA USUARIO E GARANTE PERFIL
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $tipoAnterior = $user->tipo;

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

        if ($tipoAnterior !== $user->tipo) {
            match ($tipoAnterior) {
                'aluno' => $user->aluno?->delete(),
                'professor' => $user->professor?->delete(),
                'responsavel' => $user->responsavel?->delete(),
                default => null,
            };
        }

        // Garante consistencia do perfil
        if ($user->tipo === 'aluno' && !$user->aluno) {
            DB::transaction(function () use ($user) {
                $turmaPadrao = Turma::where('nome', 'like', 'Turma Padr%')->firstOrFail();

                $codigoAluno = CodeGenerator::next('aluno');

                $aluno = Aluno::create([
                    'user_id'   => $user->id,
                    'turma_id'  => $turmaPadrao->id,
                    'matricula' => $codigoAluno,
                ]);

                $codigoMatricula = CodeGenerator::next('matricula');

                $aluno->matriculaModel()->create([
                    'codigo'            => $codigoMatricula,
                    'turma_id'          => $turmaPadrao->id,
                    'status'            => 'ativo',
                    'data_status'       => now(),
                    'motivo'            => null,
                    'observacao'        => 'Matricula inicial criada automaticamente',
                    'user_id_alteracao' => auth()->id(),
                ]);
            });
        }

        if ($user->tipo === 'professor' && !$user->professor) {
            Professor::create(['user_id' => $user->id]);
        }

        if ($user->tipo === 'responsavel' && !$user->responsavel) {
            Responsavel::create(['user_id' => $user->id]);
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario atualizado com sucesso!');
    }

    /**
     * DESTROY  REMOVE USUARIO E PERFIL
     */
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

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario e perfil excluidos com sucesso!');
    }
}
