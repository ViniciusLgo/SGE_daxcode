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

class UserController extends Controller
{
    /**
     * LISTAGEM DE USUÁRIOS
     */
    public function index()
    {
        $usuarios = User::orderByDesc('id')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * FORMULÁRIO DE CRIAÇÃO
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * STORE — CRIA USUÁRIO E PERFIL ASSOCIADO
     * Regra: User é a entidade primária.
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
     * FORMULÁRIO DE EDIÇÃO
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('user'));
    }

    /**
     * UPDATE — ATUALIZA USUÁRIO E GARANTE PERFIL
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

        // Garante consistência do perfil
        if ($user->tipo === 'aluno' && !$user->aluno) {
            DB::transaction(function () use ($user) {
                $turmaPadrao = Turma::where('nome', 'like', 'Turma Padr%')->firstOrFail();

                $ultimoAluno = Aluno::lockForUpdate()->orderByDesc('id')->first();
                $proximoNumeroAluno = $ultimoAluno ? $ultimoAluno->id + 1 : 1;
                $codigoAluno = 'ALU-' . now()->format('Y') . '-' .
                    str_pad($proximoNumeroAluno, 5, '0', STR_PAD_LEFT);

                $aluno = Aluno::create([
                    'user_id'   => $user->id,
                    'turma_id'  => $turmaPadrao->id,
                    'matricula' => $codigoAluno,
                ]);

                $ultimaMatricula = Matricula::lockForUpdate()->orderByDesc('id')->first();
                $proximoNumeroMatricula = $ultimaMatricula ? $ultimaMatricula->id + 1 : 1;
                $codigoMatricula = 'MAT-' . now()->format('Y') . '-' .
                    str_pad($proximoNumeroMatricula, 5, '0', STR_PAD_LEFT);

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
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * DESTROY — REMOVE USUÁRIO E PERFIL
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
            ->with('success', 'Usuário e perfil excluídos com sucesso!');
    }
}
