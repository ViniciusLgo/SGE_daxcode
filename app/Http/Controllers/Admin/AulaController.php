<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Turma;
use App\Models\Disciplina;
use App\Models\Professor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AulaController extends Controller
{
    /**
     * LISTAGEM DE AULAS
     */
    public function index(Request $request)
    {
        $query = Aula::with([
            'turma',
            'disciplina',
            'professor.user'
        ]);

        // Ordem cronológica
        $ordem = $request->get('ordem', 'asc');

        $query->orderBy('data', $ordem)
            ->orderBy('hora_inicio', $ordem);

        // Filtros
        if ($request->filled('professor_id')) {
            $query->where('professor_id', $request->professor_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data')) {
            $data = Carbon::createFromFormat('d/m/Y', $request->data)->format('Y-m-d');
            $query->whereDate('data', $data);
        }

        $aulas = $query->paginate(15)->withQueryString();
        $professores = Professor::with('user')->orderBy('id')->get();

        return view('admin.aulas.index', compact('aulas', 'professores'));
    }

    /**
     * FORMULÁRIO DE CADASTRO
     */
    public function create()
    {
        return view('admin.aulas.create', [
            'turmas'      => Turma::orderBy('nome')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
            'professores' => Professor::with('user')->orderBy('id')->get(),
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'turma_id'          => 'required|exists:turmas,id',
            'disciplina_id'     => 'required|exists:disciplinas,id',
            'professor_id'      => 'required|exists:professores,id',
            'tipo'              => 'required|string',
            'data'              => 'required|date_format:d/m/Y',
            'hora_inicio'       => 'required|date_format:H:i',
            'quantidade_blocos' => 'required|integer|min:1|max:6',
            'conteudo'          => 'nullable|string|max:255',
            'observacoes'       => 'nullable|string',
            'atividade_casa'    => 'nullable|boolean',
        ]);

        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');

        $duracaoMinutos = $validated['quantidade_blocos'] * 50;

        $horaFim = Carbon::createFromFormat('H:i', $validated['hora_inicio'])
            ->addMinutes($duracaoMinutos)
            ->format('H:i');

        Aula::create([
            'turma_id'          => $validated['turma_id'],
            'disciplina_id'     => $validated['disciplina_id'],
            'professor_id'      => $validated['professor_id'],
            'tipo'              => $validated['tipo'],
            'data'              => $data,
            'hora_inicio'       => $validated['hora_inicio'],
            'hora_fim'          => $horaFim,
            'quantidade_blocos' => $validated['quantidade_blocos'],
            'duracao_minutos'   => $duracaoMinutos,
            'conteudo'          => $validated['conteudo'] ?? null,
            'observacoes'       => $validated['observacoes'] ?? null,
            'atividade_casa'    => $request->boolean('atividade_casa'),
            'user_id_registro'  => auth()->id(),
        ]);

        return redirect()
            ->route('admin.aulas.index')
            ->with('success', 'Aula registrada com sucesso.');
    }

    /**
     * SHOW
     */
    public function show(Aula $aula)
    {
        $aula->load(['turma', 'disciplina', 'professor.user']);
        return view('admin.aulas.show', compact('aula'));
    }

    /**
     * EDIT
     */
    public function edit(Aula $aula)
    {
        return view('admin.aulas.edit', [
            'aula'        => $aula,
            'turmas'      => Turma::orderBy('nome')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
            'professores' => Professor::with('user')->orderBy('id')->get(),
        ]);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Aula $aula)
    {
        $validated = $request->validate([
            'turma_id'          => 'required|exists:turmas,id',
            'disciplina_id'     => 'required|exists:disciplinas,id',
            'professor_id'      => 'required|exists:professores,id',
            'tipo'              => 'required|string',
            'data'              => 'required|date_format:d/m/Y',
            'hora_inicio'       => 'required|date_format:H:i',
            'quantidade_blocos' => 'required|integer|min:1|max:6',
            'conteudo'          => 'nullable|string|max:255',
            'observacoes'       => 'nullable|string',
            'atividade_casa'    => 'nullable|boolean',
        ]);

        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');

        $duracaoMinutos = $validated['quantidade_blocos'] * 50;

        $horaFim = Carbon::createFromFormat('H:i', $validated['hora_inicio'])
            ->addMinutes($duracaoMinutos)
            ->format('H:i');

        $aula->update([
            'turma_id'          => $validated['turma_id'],
            'disciplina_id'     => $validated['disciplina_id'],
            'professor_id'      => $validated['professor_id'],
            'tipo'              => $validated['tipo'],
            'data'              => $data,
            'hora_inicio'       => $validated['hora_inicio'],
            'hora_fim'          => $horaFim,
            'quantidade_blocos' => $validated['quantidade_blocos'],
            'duracao_minutos'   => $duracaoMinutos,
            'conteudo'          => $validated['conteudo'] ?? null,
            'observacoes'       => $validated['observacoes'] ?? null,
            'atividade_casa'    => $request->boolean('atividade_casa'),
        ]);

        return redirect()
            ->route('admin.aulas.show', $aula)
            ->with('success', 'Aula atualizada com sucesso.');
    }

    /**
     * DESTROY
     */
    public function destroy(Aula $aula)
    {
        $aula->delete();

        return redirect()
            ->route('admin.aulas.index')
            ->with('success', 'Registro de aula removido com sucesso.');
    }
}
