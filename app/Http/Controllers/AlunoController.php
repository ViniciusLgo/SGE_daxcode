<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    /**
     * Exibe a lista de alunos.
     */
    public function index(Request $request)
    {
        // captura o termo de busca da query string (se houver)
        $search = $request->input('search');

        // se tiver busca, filtra os alunos
        $alunos = Aluno::query()
            ->when($search, function ($query, $search) {
                $query->where('nome', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // garante que $search chegue na view
        return view('alunos.index', compact('alunos', 'search'));
    }


    /**
     * Exibe o formulário de criação de novo aluno.
     */
    public function create()
    {
        return view('alunos.create');
    }

    /**
     * Salva um novo aluno no banco de dados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        // Upload de foto de perfil, se houver
        if ($request->hasFile('foto_perfil')) {
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/alunos', 'public');
        }

        Aluno::create($validated);

        return redirect()->route('alunos.index')->with('status', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um aluno específico.
     */
    public function show(Aluno $aluno)
    {
        return view('alunos.show', compact('aluno'));
    }

    /**
     * Exibe o formulário de edição de um aluno.
     */
    public function edit(Aluno $aluno)
    {
        return view('alunos.edit', compact('aluno'));
    }

    /**
     * Atualiza os dados de um aluno existente.
     */
    public function update(Request $request, Aluno $aluno)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'foto_perfil' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto_perfil')) {
            // Exclui a antiga, se houver
            if ($aluno->foto_perfil) {
                Storage::disk('public')->delete($aluno->foto_perfil);
            }
            $validated['foto_perfil'] = $request->file('foto_perfil')->store('avatars/alunos', 'public');
        }

        $aluno->update($validated);

        return redirect()->route('admin.alunos.show', $aluno)->with('status', 'Dados do aluno atualizados com sucesso!');
    }

    /**
     * Remove um aluno.
     */
    public function destroy(Aluno $aluno)
    {
        if ($aluno->foto_perfil) {
            Storage::disk('public')->delete($aluno->foto_perfil);
        }

        $aluno->delete();
        return redirect()->route('alunos.index')->with('status', 'Aluno excluído com sucesso!');
    }
}
