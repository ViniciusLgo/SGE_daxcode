<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\User;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    /**
     * LISTAGEM
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $alunos = Aluno::with(['user', 'turma'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('matricula', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.alunos.index', compact('alunos', 'search'));
    }

    /**
     * CREATE — recebe user_id
     */
    public function create(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $turmas = Turma::orderBy('nome')->get();

        return view('admin.alunos.create', compact('user', 'turmas'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'turma_id'  => 'required|exists:turmas,id',
            'telefone'  => 'nullable|string|max:20',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        $ultimoAluno = Aluno::orderByDesc('id')->first();
        $proximoNumero = $ultimoAluno ? $ultimoAluno->id + 1 : 1;

        $matricula = 'ALU-' . now()->format('Y') . '-' . str_pad($proximoNumero, 5, '0', STR_PAD_LEFT);

        $foto = null;
        if ($request->hasFile('foto_perfil')) {
            $foto = $request->file('foto_perfil')->store('alunos/fotos', 'public');
        }

        Aluno::create([
            'user_id'   => $validated['user_id'],
            'turma_id'  => $validated['turma_id'],
            'matricula' => $matricula,
            'telefone'  => $validated['telefone'] ?? null,
            'foto_perfil' => $foto,
        ]);

        return redirect()
            ->route('admin.alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso!');
    }


    /**
     * EDIT
     */
    public function edit($id)
    {
        $aluno = Aluno::with(['user', 'turma', 'responsaveis'])->findOrFail($id);
        $turmas = Turma::all();
        $responsaveis = Responsavel::with('user')->get();

        return view('admin.alunos.edit', compact('aluno', 'turmas', 'responsaveis'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $aluno = Aluno::with('user')->findOrFail($id);

        $validated = $request->validate([
            'user.name'   => 'required|string|max:255',
            'user.email'  => 'required|email|unique:users,email,' . $aluno->user->id,
            'telefone'   => 'nullable|string|max:20',
            'matricula'  => 'required|string|unique:alunos,matricula,' . $aluno->id,
            'turma_id'   => 'required|exists:turmas,id',
            'foto_perfil'=> 'nullable|image|max:2048',
        ]);

        $aluno->user->update($validated['user']);

        if ($request->hasFile('foto_perfil')) {
            if ($aluno->foto_perfil) {
                Storage::disk('public')->delete($aluno->foto_perfil);
            }
            $aluno->foto_perfil = $request->file('foto_perfil')->store('alunos/fotos', 'public');
        }

        $aluno->update([
            'telefone'  => $validated['telefone'] ?? null,
            'matricula' => $validated['matricula'],
            'turma_id'  => $validated['turma_id'],
            'foto_perfil' => $aluno->foto_perfil,
        ]);

        $aluno->responsaveis()->sync($request->responsaveis ?? []);

        return redirect()->back()->with('success', 'Aluno atualizado com sucesso!');
    }


    /**
     * SHOW — ficha completa do aluno
     */
    public function show($id)
    {
        $aluno = Aluno::with([
            'user',
            'turma',
            'responsaveis',
            'documentos',
            'registros',
        ])->findOrFail($id);

        return view('admin.alunos.show', compact('aluno'));
    }


    /**
     * DESTROY
     */
    public function destroy($id)
    {
        $aluno = Aluno::with('user')->findOrFail($id);

        if ($aluno->foto_perfil) {
            Storage::disk('public')->delete($aluno->foto_perfil);
        }

        $aluno->user->delete();
        $aluno->delete();

        return redirect()->route('admin.alunos.index')
            ->with('success', 'Aluno removido com sucesso!');
    }
}
