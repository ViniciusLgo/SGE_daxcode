<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\User;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    /**
     * Listagem de alunos (com busca)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $alunos = Aluno::with(['user', 'turma'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('matricula', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.alunos.index', compact('alunos', 'search'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        // redireciona para o cadastro unificado de usuários (perfil aluno)
        return redirect()
            ->route('admin.usuarios.create', ['perfil' => 'aluno'])
            ->with('info', 'O cadastro de alunos agora é feito pela tela de Usuários.');
    }

    /**
     * Cria novo aluno e automaticamente o usuário vinculado.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'nullable|string|max:20',
            'matricula' => 'required|string|max:50|unique:alunos,matricula',
            'turma_id' => 'required|exists:turmas,id',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        // 🔹 Cria o usuário vinculado
        $user = User::create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make('123456789'), // senha padrão ajustada
            'tipo' => 'aluno',
        ]);

        // 🔹 Upload da foto, se enviada
        $fotoPath = null;
        if ($request->hasFile('foto_perfil')) {
            $fotoPath = $request->file('foto_perfil')->store('alunos/fotos', 'public');
        }

        // 🔹 Cria o aluno vinculado ao usuário
        $aluno = Aluno::create([
            'user_id' => $user->id,
            'matricula' => $validated['matricula'],
            'telefone' => $validated['telefone'] ?? null,
            'turma_id' => $validated['turma_id'],
            'foto_perfil' => $fotoPath,
        ]);

        return redirect()->route('admin.alunos.index')
            ->with('success', "Aluno criado com sucesso e usuário vinculado!");
    }

    /**
     * Exibe detalhes do aluno.
     */
    public function show($id)
    {
        $aluno = Aluno::with(['user', 'turma', 'responsaveis', 'documentos', 'registros'])->findOrFail($id);
        return view('admin.alunos.show', compact('aluno'));
    }

    /**
     * Formulário de edição.
     */
    public function edit($id)
    {
        $aluno = Aluno::with(['user', 'turma', 'responsaveis'])->findOrFail($id);
        $turmas = Turma::all();
        $responsaveis = Responsavel::with('user')->get();

        return view('admin.alunos.edit', compact('aluno', 'turmas', 'responsaveis'));
    }

    /**
     * Atualiza aluno e o respectivo usuário.
     */
    public function update(Request $request, $id)
    {
        $aluno = Aluno::with('user')->findOrFail($id);

        $validated = $request->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email,' . $aluno->user->id,
            'telefone' => 'nullable|string|max:20',
            'matricula' => 'required|string|max:50|unique:alunos,matricula,' . $aluno->id,
            'turma_id' => 'required|exists:turmas,id',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        // 🔹 Atualiza User
        $aluno->user->update([
            'name' => $validated['user']['name'],
            'email' => $validated['user']['email'],
        ]);

        // 🔹 Atualiza foto se enviada
        if ($request->hasFile('foto_perfil')) {
            if ($aluno->foto_perfil) {
                Storage::disk('public')->delete($aluno->foto_perfil);
            }
            $aluno->foto_perfil = $request->file('foto_perfil')->store('alunos/fotos', 'public');
        }

        // 🔹 Atualiza demais campos do aluno
        $aluno->update([
            'telefone' => $validated['telefone'] ?? null,
            'matricula' => $validated['matricula'],
            'turma_id' => $validated['turma_id'],
        ]);

        // 🔹 Sincroniza responsáveis (se houver)
        if ($request->filled('responsaveis')) {
            $aluno->responsaveis()->sync($request->responsaveis);
        }

        return redirect()->route('admin.alunos.edit', $aluno->id)
            ->with('success', 'Aluno e usuário atualizados com sucesso!');
    }

    /**
     * Exclui aluno e respectivo usuário vinculado.
     */
    public function destroy($id)
    {
        $aluno = Aluno::with('user')->findOrFail($id);

        // 🔹 Remove foto, se existir
        if ($aluno->foto_perfil) {
            Storage::disk('public')->delete($aluno->foto_perfil);
        }

        // 🔹 Deleta o usuário (cascade)
        if ($aluno->user) {
            $aluno->user->delete();
        }

        // 🔹 Deleta o aluno
        $aluno->delete();

        return redirect()->route('admin.alunos.index')
            ->with('success', 'Aluno e usuário removidos com sucesso!');
    }
}
