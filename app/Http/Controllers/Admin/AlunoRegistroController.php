<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlunoRegistro;
use App\Models\User;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoRegistroController extends Controller
{
    /**
     * Listagem dos registros (com filtros)
     */
    public function index(Request $request)
    {
        $query = AlunoRegistro::with(['aluno', 'turma', 'responsavel']);

        // Filtros dinâmicos
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        $registros = $query->latest()->paginate(10);
        $turmas = Turma::orderBy('nome')->get();

        return view('admin.aluno_registros.index', compact('registros', 'turmas'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $alunos = \App\Models\User::orderBy('name')->get();
        $turmas = \App\Models\Turma::orderBy('nome')->get();
        return view('admin.aluno_registros.create', compact('alunos', 'turmas'));
    }


    /**
     * Salvar novo registro
     */
    public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:users,id',
            'tipo' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'data_evento' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['responsavel_id'] = auth()->id();

        if ($request->hasFile('arquivo')) {
            $path = $request->file('arquivo')->store('public/alunos/registros');
            $data['arquivo'] = str_replace('public/', 'storage/', $path);
        }

        AlunoRegistro::create($data);

        return redirect()->route('aluno_registros.index')->with('success', 'Registro adicionado com sucesso!');
    }

    /**
     * Exibir um registro
     */
    public function show(AlunoRegistro $aluno_registro)
    {
        return view('admin.aluno_registros.show', compact('aluno_registro'));
    }

    /**
     * Editar registro
     */
    public function edit(AlunoRegistro $aluno_registro)
    {
        $alunos = User::where('tipo', 'aluno')->orderBy('name')->get();
        $turmas = Turma::orderBy('nome')->get();
        return view('admin.aluno_registros.edit', compact('aluno_registro', 'alunos', 'turmas'));
    }

    /**
     * Atualizar registro
     */
    public function update(Request $request, AlunoRegistro $aluno_registro)
    {
        $request->validate([
            'aluno_id' => 'required|exists:users,id',
            'tipo' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'data_evento' => 'nullable|date',
            'status' => 'nullable|in:pendente,validado,arquivado,expirado'
        ]);

        $data = $request->all();

        if ($request->hasFile('arquivo')) {
            // Deleta o arquivo antigo
            if ($aluno_registro->arquivo && Storage::exists(str_replace('storage/', 'public/', $aluno_registro->arquivo))) {
                Storage::delete(str_replace('storage/', 'public/', $aluno_registro->arquivo));
            }

            $path = $request->file('arquivo')->store('public/alunos/registros');
            $data['arquivo'] = str_replace('public/', 'storage/', $path);
        }

        $aluno_registro->update($data);

        return redirect()->route('aluno_registros.index')->with('success', 'Registro atualizado com sucesso!');
    }

    /**
     * Excluir registro
     */
    public function destroy(AlunoRegistro $aluno_registro)
    {
        if ($aluno_registro->arquivo && Storage::exists(str_replace('storage/', 'public/', $aluno_registro->arquivo))) {
            Storage::delete(str_replace('storage/', 'public/', $aluno_registro->arquivo));
        }

        $aluno_registro->delete();

        return redirect()->route('aluno_registros.index')->with('success', 'Registro excluído com sucesso!');
    }
}
