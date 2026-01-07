<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Disciplina;
use App\Models\DisciplinaTurma;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    private function validarVinculo(int $professorId, int $turmaId, int $disciplinaId): bool
    {
        return DisciplinaTurma::where('turma_id', $turmaId)
            ->where('disciplina_id', $disciplinaId)
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->exists();
    }

    public function index(Request $request)
    {
        $professorId = $this->professorId();

        $query = Aula::with(['turma', 'disciplina', 'professor.user'])
            ->where('professor_id', $professorId);

        $ordem = $request->get('ordem', 'asc');
        $query->orderBy('data', $ordem)->orderBy('hora_inicio', $ordem);

        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data')) {
            $data = Carbon::createFromFormat('d/m/Y', $request->data)->format('Y-m-d');
            $query->whereDate('data', $data);
        }

        $aulas = $query->paginate(15)->withQueryString();

        $turmas = Turma::whereHas('disciplinaTurmas.professores', function ($q) use ($professorId) {
            $q->where('professor_id', $professorId);
        })->orderBy('nome')->get();

        $disciplinas = Disciplina::whereHas('professores', function ($q) use ($professorId) {
            $q->where('professores.id', $professorId);
        })->orderBy('nome')->get();

        return view('admin.aulas.index', [
            'aulas' => $aulas,
            'professores' => collect(),
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function create()
    {
        $professorId = $this->professorId();

        $vinculos = DisciplinaTurma::with(['turma', 'disciplina'])
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->get();

        $turmas = $vinculos->pluck('turma')->unique('id')->values();
        $disciplinas = $vinculos->pluck('disciplina')->unique('id')->values();

        return view('admin.aulas.create', [
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'professores' => collect(),
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function store(Request $request)
    {
        $professorId = $this->professorId();

        $validated = $request->validate([
            'turma_id'          => 'required|exists:turmas,id',
            'disciplina_id'     => 'required|exists:disciplinas,id',
            'tipo'              => 'required|string',
            'data'              => 'required|date_format:d/m/Y',
            'hora_inicio'       => 'required|date_format:H:i',
            'quantidade_blocos' => 'required|integer|min:1|max:6',
            'conteudo'          => 'nullable|string|max:255',
            'observacoes'       => 'nullable|string',
            'atividade_casa'    => 'nullable|boolean',
        ]);

        if (!$this->validarVinculo($professorId, (int) $validated['turma_id'], (int) $validated['disciplina_id'])) {
            return back()
                ->withInput()
                ->withErrors(['disciplina_id' => 'Disciplina nao vinculada a esta turma para voce.']);
        }

        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');
        $duracaoMinutos = $validated['quantidade_blocos'] * 50;
        $horaFim = Carbon::createFromFormat('H:i', $validated['hora_inicio'])
            ->addMinutes($duracaoMinutos)
            ->format('H:i');

        Aula::create([
            'turma_id'          => $validated['turma_id'],
            'disciplina_id'     => $validated['disciplina_id'],
            'professor_id'      => $professorId,
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
            ->route('professor.aulas.index')
            ->with('success', 'Aula registrada com sucesso.');
    }

    public function show(Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $aula->load(['turma', 'disciplina', 'professor.user']);

        return view('admin.aulas.show', [
            'aula' => $aula,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function edit(Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $vinculos = DisciplinaTurma::with(['turma', 'disciplina'])
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->get();

        $turmas = $vinculos->pluck('turma')->unique('id')->values();
        $disciplinas = $vinculos->pluck('disciplina')->unique('id')->values();

        return view('admin.aulas.edit', [
            'aula' => $aula,
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'professores' => collect(),
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function update(Request $request, Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $validated = $request->validate([
            'turma_id'          => 'required|exists:turmas,id',
            'disciplina_id'     => 'required|exists:disciplinas,id',
            'tipo'              => 'required|string',
            'data'              => 'required|date_format:d/m/Y',
            'hora_inicio'       => 'required|date_format:H:i',
            'quantidade_blocos' => 'required|integer|min:1|max:6',
            'conteudo'          => 'nullable|string|max:255',
            'observacoes'       => 'nullable|string',
            'atividade_casa'    => 'nullable|boolean',
        ]);

        if (!$this->validarVinculo($professorId, (int) $validated['turma_id'], (int) $validated['disciplina_id'])) {
            return back()
                ->withInput()
                ->withErrors(['disciplina_id' => 'Disciplina nao vinculada a esta turma para voce.']);
        }

        $data = Carbon::createFromFormat('d/m/Y', $validated['data'])->format('Y-m-d');
        $duracaoMinutos = $validated['quantidade_blocos'] * 50;
        $horaFim = Carbon::createFromFormat('H:i', $validated['hora_inicio'])
            ->addMinutes($duracaoMinutos)
            ->format('H:i');

        $aula->update([
            'turma_id'          => $validated['turma_id'],
            'disciplina_id'     => $validated['disciplina_id'],
            'professor_id'      => $professorId,
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
            ->route('professor.aulas.show', $aula)
            ->with('success', 'Aula atualizada com sucesso.');
    }

    public function destroy(Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $aula->delete();

        return redirect()
            ->route('professor.aulas.index')
            ->with('success', 'Registro de aula removido com sucesso.');
    }
}
