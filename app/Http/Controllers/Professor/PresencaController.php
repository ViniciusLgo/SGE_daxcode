<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Disciplina;
use App\Models\JustificativaFalta;
use App\Models\Presenca;
use App\Models\PresencaAluno;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PresencaController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    public function index(Request $request)
    {
        $professorId = $this->professorId();

        $inicio = $request->filled('data_inicio')
            ? Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $fim = $request->filled('data_fim')
            ? Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay()
            : now()->endOfDay();

        $query = Aula::with(['turma', 'disciplina', 'professor.user', 'presenca'])
            ->where('professor_id', $professorId)
            ->whereBetween('data', [$inicio->toDateString(), $fim->toDateString()])
            ->orderByDesc('data')
            ->orderByDesc('hora_inicio');

        if ($request->filled('status')) {
            if ($request->status === 'sem_presenca') {
                $query->whereDoesntHave('presenca');
            } else {
                $query->whereHas('presenca', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
            }
        }

        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        $aulas = $query->paginate(15)->withQueryString();

        $totalAulas = (clone $query)->count();
        $comPresenca = (clone $query)->whereHas('presenca')->count();
        $finalizadas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'finalizada'))
            ->count();
        $abertas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'aberta'))
            ->count();

        $semPresenca = max($totalAulas - $comPresenca, 0);
        $cobertura = $totalAulas > 0 ? round(($comPresenca / $totalAulas) * 100, 1) : 0;

        $limiteDias = 7;
        $aulasPendentesAntigas = (clone $query)
            ->whereDoesntHave('presenca')
            ->where('data', '<=', now()->subDays($limiteDias)->toDateString())
            ->count();

        $chartStatus = [
            'labels' => ['Sem presenca', 'Aberta', 'Finalizada'],
            'data'   => [$semPresenca, $abertas, $finalizadas],
        ];

        $turmas = Turma::whereHas('disciplinaTurmas.professores', function ($q) use ($professorId) {
            $q->where('professor_id', $professorId);
        })->orderBy('nome')->get();

        $disciplinas = Disciplina::whereHas('professores', function ($q) use ($professorId) {
            $q->where('professores.id', $professorId);
        })->orderBy('nome')->get();

        return view('admin.gestao_academica.presencas.index', [
            'aulas' => $aulas,
            'inicio' => $inicio,
            'fim' => $fim,
            'totalAulas' => $totalAulas,
            'comPresenca' => $comPresenca,
            'semPresenca' => $semPresenca,
            'abertas' => $abertas,
            'finalizadas' => $finalizadas,
            'cobertura' => $cobertura,
            'chartStatus' => $chartStatus,
            'aulasPendentesAntigas' => $aulasPendentesAntigas,
            'limiteDias' => $limiteDias,
            'turmas' => $turmas,
            'professores' => collect(),
            'disciplinas' => $disciplinas,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function show(Presenca $presenca)
    {
        $professorId = $this->professorId();
        abort_unless($presenca->professor_id == $professorId, 403);

        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel',
            'alunos.justificativa',
        ]);

        return view('admin.gestao_academica.presencas.show', [
            'presenca' => $presenca,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function edit(Presenca $presenca)
    {
        $professorId = $this->professorId();
        abort_unless($presenca->professor_id == $professorId, 403);

        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel',
            'alunos.justificativa',
        ]);

        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit', [
            'presenca' => $presenca,
            'justificativas' => $justificativas,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function update(Request $request, Presenca $presenca)
    {
        $professorId = $this->professorId();
        abort_unless($presenca->professor_id == $professorId, 403);

        $data = $request->validate([
            'status' => 'required|in:aberta,finalizada',
            'presencas' => 'required|array',
            'presencas.*.bloco_1' => 'nullable|boolean',
            'presencas.*.bloco_2' => 'nullable|boolean',
            'presencas.*.bloco_3' => 'nullable|boolean',
            'presencas.*.bloco_4' => 'nullable|boolean',
            'presencas.*.bloco_5' => 'nullable|boolean',
            'presencas.*.bloco_6' => 'nullable|boolean',
            'presencas.*.justificativa_falta_id' => 'nullable|exists:justificativa_faltas,id',
            'presencas.*.observacao' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($presenca, $data) {
            $presenca->update(['status' => $data['status']]);

            foreach ($data['presencas'] as $alunoId => $payload) {
                $item = $presenca->alunos()->where('aluno_id', $alunoId)->first();
                if (!$item) {
                    continue;
                }

                if (!empty($payload['justificativa_falta_id'])) {
                    $just = JustificativaFalta::find($payload['justificativa_falta_id']);
                    if ($just && $just->exige_observacao && empty($payload['observacao'])) {
                        throw ValidationException::withMessages([
                            "presencas.$alunoId.observacao" =>
                                "A justificativa '{$just->nome}' exige observacao.",
                        ]);
                    }
                }

                $max = (int) $presenca->quantidade_blocos;

                $item->update([
                    'bloco_1' => $max >= 1 ? (bool)($payload['bloco_1'] ?? false) : false,
                    'bloco_2' => $max >= 2 ? (bool)($payload['bloco_2'] ?? false) : false,
                    'bloco_3' => $max >= 3 ? (bool)($payload['bloco_3'] ?? false) : false,
                    'bloco_4' => $max >= 4 ? (bool)($payload['bloco_4'] ?? false) : false,
                    'bloco_5' => $max >= 5 ? (bool)($payload['bloco_5'] ?? false) : false,
                    'bloco_6' => $max >= 6 ? (bool)($payload['bloco_6'] ?? false) : false,
                    'justificativa_falta_id' => $payload['justificativa_falta_id'] ?? null,
                    'observacao' => $payload['observacao'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('professor.presencas.show', $presenca)
            ->with('success', 'Presenca atualizada com sucesso.');
    }

    public function editFromAula(Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $aula->load(['turma', 'disciplina', 'professor.user', 'presenca']);

        $presenca = DB::transaction(function () use ($aula) {
            $presenca = Presenca::firstOrCreate(
                ['aula_id' => $aula->id],
                [
                    'turma_id' => $aula->turma_id,
                    'disciplina_id' => $aula->disciplina_id,
                    'professor_id' => $aula->professor_id,
                    'data' => $aula->data,
                    'quantidade_blocos' => $aula->quantidade_blocos,
                    'status' => 'aberta',
                ]
            );

            $alunosAtivosIds = $aula->turma
                ->alunos()
                ->ativos()
                ->pluck('id')
                ->toArray();

            foreach ($alunosAtivosIds as $alunoId) {
                PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id' => $alunoId,
                    ],
                    [
                        'bloco_1' => false,
                        'bloco_2' => false,
                        'bloco_3' => false,
                        'bloco_4' => false,
                        'bloco_5' => false,
                        'bloco_6' => false,
                    ]
                );
            }

            return $presenca;
        });

        $presenca->load([
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel',
            'alunos.justificativa',
        ]);

        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit_from_aula', [
            'aula' => $aula,
            'presenca' => $presenca,
            'justificativas' => $justificativas,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function updateFromAula(Request $request, Aula $aula)
    {
        $professorId = $this->professorId();
        abort_unless($aula->professor_id == $professorId, 403);

        $aula->load(['turma']);

        $data = $request->validate([
            'status' => 'nullable|in:aberta,finalizada',
            'presencas' => 'required|array',
            'presencas.*.bloco_1' => 'nullable|boolean',
            'presencas.*.bloco_2' => 'nullable|boolean',
            'presencas.*.bloco_3' => 'nullable|boolean',
            'presencas.*.bloco_4' => 'nullable|boolean',
            'presencas.*.bloco_5' => 'nullable|boolean',
            'presencas.*.bloco_6' => 'nullable|boolean',
            'presencas.*.justificativa_falta_id' => 'nullable|exists:justificativa_faltas,id',
            'presencas.*.observacao' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($aula, $data) {
            $presenca = Presenca::firstOrCreate(
                ['aula_id' => $aula->id],
                [
                    'turma_id' => $aula->turma_id,
                    'disciplina_id' => $aula->disciplina_id,
                    'professor_id' => $aula->professor_id,
                    'data' => $aula->data,
                    'quantidade_blocos' => $aula->quantidade_blocos,
                    'status' => 'aberta',
                ]
            );

            if (!empty($data['status'])) {
                $presenca->update(['status' => $data['status']]);
            }

            $idsAtivos = $aula->turma
                ->alunos()
                ->ativos()
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            $idsAtivosLookup = array_flip($idsAtivos);

            foreach ($data['presencas'] as $alunoId => $payload) {
                $alunoId = (int) $alunoId;
                if (!isset($idsAtivosLookup[$alunoId])) {
                    continue;
                }

                if (!empty($payload['justificativa_falta_id'])) {
                    $just = JustificativaFalta::find($payload['justificativa_falta_id']);
                    if ($just && $just->exige_observacao && empty($payload['observacao'])) {
                        throw ValidationException::withMessages([
                            "presencas.$alunoId.observacao" =>
                                "A justificativa '{$just->nome}' exige observacao.",
                        ]);
                    }
                }

                $item = PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id' => $alunoId,
                    ]
                );

                $max = (int) $presenca->quantidade_blocos;

                $item->update([
                    'bloco_1' => ($max >= 1) ? (bool)($payload['bloco_1'] ?? false) : false,
                    'bloco_2' => ($max >= 2) ? (bool)($payload['bloco_2'] ?? false) : false,
                    'bloco_3' => ($max >= 3) ? (bool)($payload['bloco_3'] ?? false) : false,
                    'bloco_4' => ($max >= 4) ? (bool)($payload['bloco_4'] ?? false) : false,
                    'bloco_5' => ($max >= 5) ? (bool)($payload['bloco_5'] ?? false) : false,
                    'bloco_6' => ($max >= 6) ? (bool)($payload['bloco_6'] ?? false) : false,
                    'justificativa_falta_id' => $payload['justificativa_falta_id'] ?? null,
                    'observacao' => $payload['observacao'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('professor.aulas.show', $aula)
            ->with('success', 'Presencas atualizadas com sucesso.');
    }
}
