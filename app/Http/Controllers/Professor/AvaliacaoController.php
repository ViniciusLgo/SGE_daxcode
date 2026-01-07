<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use App\Models\Disciplina;
use App\Models\DisciplinaTurma;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliacaoController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    private function temVinculo(int $professorId, int $turmaId, int $disciplinaId): bool
    {
        return DisciplinaTurma::where('turma_id', $turmaId)
            ->where('disciplina_id', $disciplinaId)
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->exists();
    }

    private function baseQuery(int $professorId)
    {
        return Avaliacao::with(['turma', 'disciplina'])
            ->whereExists(function ($q) use ($professorId) {
                $q->select(DB::raw(1))
                    ->from('disciplina_turmas')
                    ->join(
                        'disciplina_turma_professor',
                        'disciplina_turma_professor.disciplina_turma_id',
                        '=',
                        'disciplina_turmas.id'
                    )
                    ->whereColumn('disciplina_turmas.turma_id', 'avaliacoes.turma_id')
                    ->whereColumn('disciplina_turmas.disciplina_id', 'avaliacoes.disciplina_id')
                    ->where('disciplina_turma_professor.professor_id', $professorId);
            });
    }

    public function index()
    {
        $professorId = $this->professorId();

        $avaliacoes = $this->baseQuery($professorId)
            ->orderBy('data_avaliacao', 'desc')
            ->paginate(10);

        return view('admin.gestao_academica.avaliacoes.index', [
            'avaliacoes' => $avaliacoes,
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

        return view('admin.gestao_academica.avaliacoes.create', [
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

        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'titulo'         => 'required|string|max:255',
            'tipo'           => 'required|in:prova,trabalho,atividade,recuperacao',
            'data_avaliacao' => 'required|date',
        ]);

        if (!$this->temVinculo($professorId, (int) $request->turma_id, (int) $request->disciplina_id)) {
            return back()
                ->withInput()
                ->withErrors(['disciplina_id' => 'Disciplina nao vinculada a esta turma para voce.']);
        }

        Avaliacao::create([
            'turma_id'       => $request->turma_id,
            'disciplina_id'  => $request->disciplina_id,
            'titulo'         => $request->titulo,
            'tipo'           => $request->tipo,
            'data_avaliacao' => $request->data_avaliacao,
            'status'         => 'aberta',
        ]);

        return redirect()
            ->route('professor.avaliacoes.index')
            ->with('success', 'Avaliacao criada com sucesso.');
    }

    public function edit(Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->temVinculo($professorId, $avaliacao->turma_id, $avaliacao->disciplina_id), 403);

        $vinculos = DisciplinaTurma::with(['turma', 'disciplina'])
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->get();

        $turmas = $vinculos->pluck('turma')->unique('id')->values();
        $disciplinas = $vinculos->pluck('disciplina')->unique('id')->values();

        return view('admin.gestao_academica.avaliacoes.edit', [
            'avaliacao' => $avaliacao,
            'turmas' => $turmas,
            'disciplinas' => $disciplinas,
            'professores' => collect(),
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function update(Request $request, Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->temVinculo($professorId, $avaliacao->turma_id, $avaliacao->disciplina_id), 403);

        $request->validate([
            'turma_id'        => 'required|exists:turmas,id',
            'disciplina_id'   => 'required|exists:disciplinas,id',
            'titulo'          => 'required|string|max:255',
            'tipo'            => 'required|in:prova,trabalho,atividade,recuperacao',
            'data_avaliacao'  => 'required|date',
            'status'          => 'required|in:aberta,encerrada',
        ]);

        if (!$this->temVinculo($professorId, (int) $request->turma_id, (int) $request->disciplina_id)) {
            return back()
                ->withInput()
                ->withErrors(['disciplina_id' => 'Disciplina nao vinculada a esta turma para voce.']);
        }

        $avaliacao->update($request->only([
            'turma_id',
            'disciplina_id',
            'titulo',
            'tipo',
            'data_avaliacao',
            'status',
        ]));

        return redirect()
            ->route('professor.avaliacoes.index')
            ->with('success', 'Avaliacao atualizada com sucesso.');
    }

    public function encerrar(Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->temVinculo($professorId, $avaliacao->turma_id, $avaliacao->disciplina_id), 403);

        $avaliacao->update(['status' => 'encerrada']);

        return back()->with('success', 'Avaliacao encerrada.');
    }

    public function reabrir(Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->temVinculo($professorId, $avaliacao->turma_id, $avaliacao->disciplina_id), 403);

        if ($avaliacao->status === 'encerrada') {
            $avaliacao->update(['status' => 'aberta']);
        }

        return redirect()
            ->route('professor.avaliacoes.edit', $avaliacao)
            ->with('success', 'Avaliacao reaberta com sucesso.');
    }

    public function destroy(Avaliacao $avaliacao)
    {
        $professorId = $this->professorId();
        abort_unless($this->temVinculo($professorId, $avaliacao->turma_id, $avaliacao->disciplina_id), 403);

        if ($avaliacao->resultados()->exists()) {
            return redirect()
                ->route('professor.avaliacoes.index')
                ->with('error', 'Nao e possivel excluir uma avaliacao que ja possui resultados.');
        }

        $avaliacao->delete();

        return redirect()
            ->route('professor.avaliacoes.index')
            ->with('success', 'Avaliacao excluida com sucesso.');
    }
}
