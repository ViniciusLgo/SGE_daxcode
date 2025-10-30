<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $alunos = Aluno::query()
            ->with('turma')
            ->when($search, function ($query) use ($search) {
                $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('matricula', 'like', "%{$search}%");
            })
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('admin.alunos.index', compact('alunos', 'search'));
    }

    public function create()
    {
        $turmas = Turma::orderBy('nome')->get();
        return view('admin.alunos.create', compact('turmas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:50|unique:alunos,matricula',
            'email' => 'required|email|unique:alunos,email',
            'telefone' => 'nullable|string|max:20',
            'foto_perfil' => 'nullable|image|max:5120',
            'data_nascimento' => 'nullable|date',
            'turma_id' => 'required|exists:turmas,id',
        ]);

        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/alunos', 'public');
        }

        Aluno::create($validated);

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }


    public function show($id)
    {
        $aluno = Aluno::with(['turma', 'responsaveis', 'documentos', 'registros'])->findOrFail($id);
        return view('admin.alunos.show', compact('aluno'));
    }



    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        $turmas = Turma::orderBy('nome')->get();
        $aluno->load('responsaveis');
        $responsaveis = \App\Models\Responsavel::orderBy('nome')->get();
        return view('admin.alunos.edit', compact('aluno', 'responsaveis', 'turmas'));


    }



    public function update(Request $request, Aluno $aluno)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:50|unique:alunos,matricula,' . $aluno->id,
            'email' => 'required|email|unique:alunos,email,' . $aluno->id,
            'telefone' => 'nullable|string|max:20',
            'foto_perfil' => 'nullable|image|max:5120',
            'data_nascimento' => 'nullable|date',
            'turma_id' => 'required|exists:turmas,id',
            'responsaveis' => 'array|nullable',

        ]);

        if ($request->hasFile('foto_perfil')) {
            if ($aluno->foto_perfil) {
                Storage::disk('public')->delete($aluno->foto_perfil);
            }
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/alunos', 'public');
        }

        if ($request->has('responsaveis')) {
            $aluno->responsaveis()->sync($request->responsaveis);
        }


        $aluno->update($validated);

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(Aluno $aluno)
    {
        if ($aluno->foto_perfil) {
            Storage::disk('public')->delete($aluno->foto_perfil);
        }

        $aluno->delete();

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno excluído com sucesso!');
    }

    public function resetPassword($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $novaSenha = 'Dax@' . rand(1000, 9999); // exemplo gerador de senha temporária

        $user->update([
            'password' => Hash::make($novaSenha),
            'first_login' => true, // força troca no próximo acesso
        ]);

        return back()->with('success', "Senha redefinida com sucesso! Nova senha: {$novaSenha}");
    }
}
