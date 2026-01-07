<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AlunoRegistro;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlunoRegistroController extends Controller
{

    /**
     * =========================================================
     * LISTAGEM COM FILTROS
     * =========================================================
     */
    public function index(Request $request)
    {
        $query = AlunoRegistro::with(['aluno.user', 'turma', 'responsavel']);

        // filtros simples
        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);
        if ($request->filled('categoria')) $query->where('categoria', $request->categoria);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('turma_id')) $query->where('turma_id', $request->turma_id);

        $registros = $query->latest()->paginate(10);
        $turmas = Turma::orderBy('nome')->get();

        return view('admin.aluno_registros.index', compact('registros', 'turmas'));
    }



    /**
     * =========================================================
     * FORMULARIO DE CRIACAO
     * =========================================================
     */
    public function create()
    {
        // Lista ordenada corretamente de alunos
        $alunos = Aluno::with('user')
            ->join('users', 'alunos.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('alunos.*')
            ->get();

        // Turma agora e preenchida automaticamente  nao precisa listar
        return view('admin.aluno_registros.create', compact('alunos'));
    }



    /**
     * =========================================================
     * SALVAR NOVO REGISTRO
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'tipo' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'data_evento' => 'nullable|date',
        ]);

        $aluno = Aluno::with('turma')->findOrFail($request->aluno_id);

        $data = $validated;
        $data['turma_id'] = $aluno->turma_id; // turma automatica
        $data['responsavel_id'] = auth()->id();
        $data['status'] = 'pendente';

        // upload do arquivo
        if ($request->hasFile('arquivo')) {
            $file = $request->file('arquivo');
            $nome = uniqid('registro_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/alunos/registros', $nome);
            $data['arquivo'] = 'storage/alunos/registros/' . $nome;
        }

        AlunoRegistro::create($data);

        return redirect()->route('admin.aluno_registros.index')
            ->with('success', 'Registro adicionado com sucesso!');
    }



    /**
     * =========================================================
     * EXIBIR REGISTRO INDIVIDUAL
     * =========================================================
     */
    public function show(AlunoRegistro $aluno_registro)
    {
        return view('admin.aluno_registros.show', compact('aluno_registro'));
    }



    /**
     * =========================================================
     * FORMULARIO DE EDICAO
     * =========================================================
     */
    public function edit(AlunoRegistro $aluno_registro)
    {
        $alunos = Aluno::with('user')
            ->join('users', 'alunos.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->select('alunos.*')
            ->get();

        return view('admin.aluno_registros.edit', compact('aluno_registro', 'alunos'));
    }



    /**
     * =========================================================
     * ATUALIZAR O REGISTRO
     * =========================================================
     */
    public function update(Request $request, AlunoRegistro $aluno_registro)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'tipo' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'data_evento' => 'nullable|date',
            'status' => 'required|in:pendente,validado,arquivado,expirado'
        ]);

        $aluno = Aluno::findOrFail($request->aluno_id);

        $data = $validated;
        $data['turma_id'] = $aluno->turma_id;

        if ($request->hasFile('arquivo')) {
            if ($aluno_registro->arquivo) {
                Storage::delete(str_replace('storage/', 'public/', $aluno_registro->arquivo));
            }

            $file = $request->file('arquivo');
            $nome = uniqid('registro_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/alunos/registros', $nome);
            $data['arquivo'] = 'storage/alunos/registros/' . $nome;
        }

        $aluno_registro->update($data);

        return redirect()->route('admin.aluno_registros.index')
            ->with('success', 'Registro atualizado com sucesso!');
    }



    /**
     * =========================================================
     * APAGAR REGISTRO
     * =========================================================
     */
    public function destroy(AlunoRegistro $aluno_registro)
    {
        if ($aluno_registro->arquivo) {
            Storage::delete(str_replace('storage/', 'public/', $aluno_registro->arquivo));
        }

        $aluno_registro->delete();

        return redirect()->route('admin.aluno_registros.index')
            ->with('success', 'Registro excluido com sucesso!');
    }



    /**
     * =========================================================
     * AJAX  BUSCAR TURMA AUTOMATICA DO ALUNO
     * =========================================================
     */
    public function buscarTurmaAluno(Aluno $aluno)
    {
        // Aluno sem turma
        if (!$aluno->turma) {
            return response()->json([
                'sem_turma' => true
            ]);
        }

        return response()->json([
            'turma_id' => $aluno->turma->id,
            'turma'    => $aluno->turma->nome,
        ]);
    }
}
