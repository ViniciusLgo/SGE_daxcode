<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Responsavel;
use App\Models\Aluno;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    public function index()
    {
        $responsaveis = Responsavel::withCount('alunos')->paginate(10);
        return view('admin.responsaveis.index', compact('responsaveis'));
    }

    public function create()
    {
        $alunos = Aluno::orderBy('nome')->get();
        return view('admin.responsaveis.create', compact('alunos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:50',
            'cpf' => 'nullable|string|max:14|unique:responsaveis,cpf',
            'grau_parentesco' => 'nullable|string|max:50',
            'alunos' => 'array',
        ]);

        $responsavel = Responsavel::create($data);

        if ($request->filled('alunos')) {
            $responsavel->alunos()->sync($request->alunos);
        }

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsável cadastrado com sucesso!');
    }

    public function show(Responsavel $responsavel)
    {
        $responsavel->load('alunos');
        return view('admin.responsaveis.show', compact('responsavel'));
    }

    public function edit(Responsavel $responsavel)
    {
        $alunos = \App\Models\Aluno::orderBy('nome')->get();
        $responsavel->load('alunos');
        return view('admin.responsaveis.edit', compact('responsavel', 'alunos'));
    }

    public function update(Request $request, Responsavel $responsavel)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:50',
            'cpf' => 'nullable|string|max:14|unique:responsaveis,cpf,' . $responsavel->id,
            'grau_parentesco' => 'nullable|string|max:50',
            'alunos' => 'array',
        ]);

        $responsavel->update($data);
        $responsavel->alunos()->sync($request->alunos ?? []);

        return redirect()->route('admin.responsaveis.index')
            ->with('success', 'Responsável atualizado com sucesso!');
    }

    public function destroy(Responsavel $responsavel)
    {
        // Remove vínculos de alunos antes da exclusão
        $responsavel->alunos()->detach();

        $responsavel->delete();

        return redirect()
            ->route('admin.responsaveis.index')
            ->with('success', 'Responsável excluído com sucesso!');
    }
}
