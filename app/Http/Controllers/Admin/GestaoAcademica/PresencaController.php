<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Carbon\Carbon;
use App\Models\Presenca;
use App\Models\PresencaAluno;
use App\Models\JustificativaFalta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Turma;
use App\Models\Professor;
use App\Models\Disciplina;


class PresencaController extends Controller
{

    public function index(Request $request)
    {
        // ================= PERÍODO =================
        $inicio = $request->filled('data_inicio')
            ? Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $fim = $request->filled('data_fim')
            ? Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay()
            : now()->endOfDay();

        // ================= QUERY BASE =================
        $query = Aula::with(['turma', 'disciplina', 'professor.user', 'presenca'])
            ->whereBetween('data', [$inicio->toDateString(), $fim->toDateString()])
            ->orderByDesc('data')
            ->orderByDesc('hora_inicio');

        // ================= FILTRO SIMPLES: STATUS =================
        if ($request->filled('status')) {
            if ($request->status === 'sem_presenca') {
                $query->whereDoesntHave('presenca');
            } else {
                $query->whereHas('presenca', fn ($q) =>
                $q->where('status', $request->status)
                );
            }
        }

        // ================= FILTROS AVANÇADOS =================
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('professor_id')) {
            $query->where('professor_id', $request->professor_id);
        }

        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }

        // ================= PAGINAÇÃO =================
        $aulas = $query->paginate(15)->withQueryString();

        // ================= KPIs =================
        $totalAulas = (clone $query)->count();

        $comPresenca = (clone $query)->whereHas('presenca')->count();

        $finalizadas = (clone $query)
            ->whereHas('presenca', fn ($q) => $q->where('status', 'finalizada'))
            ->count();

        $abertas = (clone $query)
            ->whereHas('presenca', fn ($q) => $q->where('status', 'aberta'))
            ->count();

        $semPresenca = max($totalAulas - $comPresenca, 0);

        $cobertura = $totalAulas > 0
            ? round(($comPresenca / $totalAulas) * 100, 1)
            : 0;

        // ================= ALERTA: AULAS SEM PRESENÇA HÁ X DIAS =================
        $limiteDias = 7;

        $aulasPendentesAntigas = (clone $query)
            ->whereDoesntHave('presenca')
            ->where('data', '<=', now()->subDays($limiteDias)->toDateString())
            ->count();

        // ================= GRÁFICO =================
        $chartStatus = [
            'labels' => ['Sem presença', 'Aberta', 'Finalizada'],
            'data'   => [$semPresenca, $abertas, $finalizadas],
        ];

        return view('admin.gestao_academica.presencas.index', [
            'aulas'        => $aulas,
            'inicio'       => $inicio,
            'fim'          => $fim,
            'totalAulas'   => $totalAulas,
            'comPresenca'  => $comPresenca,
            'semPresenca'  => $semPresenca,
            'abertas'      => $abertas,
            'finalizadas'  => $finalizadas,
            'cobertura'    => $cobertura,
            'chartStatus'  => $chartStatus,
            'aulasPendentesAntigas' => $aulasPendentesAntigas,
            'limiteDias'            => $limiteDias,

            // dados para filtros
            'turmas'       => Turma::orderBy('nome')->get(),
            'professores'  => Professor::with('user')->orderBy('id')->get(),
            'disciplinas'  => Disciplina::orderBy('nome')->get(),

        ]);
    }




    public function show(Presenca $presenca)
    {
        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.justificativa',
        ]);

        return view('admin.gestao_academica.presencas.show', compact('presenca'));
    }

    public function edit(Presenca $presenca)
    {
        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.justificativa',
        ]);

        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit', compact(
            'presenca',
            'justificativas'
        ));
    }

    public function update(Request $request, Presenca $presenca)
    {
        $data = $request->validate([
            'status' => 'required|in:aberta,finalizada',

            'presencas' => 'required|array',

            'presencas.*.bloco_1' => 'nullable|boolean',
            'presencas.*.bloco_2' => 'nullable|boolean',
            'presencas.*.bloco_3' => 'nullable|boolean',
            'presencas.*.bloco_4' => 'nullable|boolean',
            'presencas.*.bloco_5' => 'nullable|boolean',
            'presencas.*.bloco_6' => 'nullable|boolean',

            'presencas.*.justificativa_falta_id'
            => 'nullable|exists:justificativa_faltas,id',

            'presencas.*.observacao'
            => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($presenca, $data) {

            $presenca->update([
                'status' => $data['status'],
            ]);

            foreach ($data['presencas'] as $alunoId => $payload) {

                $item = $presenca->alunos()
                    ->where('aluno_id', $alunoId)
                    ->first();

                if (!$item) {
                    continue;
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
                    'observacao'             => $payload['observacao'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.presencas.show', $presenca)
            ->with('success', 'Presença atualizada com sucesso.');
    }


    /**
     * Abre a tela de presença da Aula.
     * - Cria Presenca automaticamente se não existir (1 por aula)
     * - Garante que todos os alunos da turma existam em presenca_alunos
     */
    public function editFromAula(Aula $aula)
    {
        $aula->load(['turma.alunos.user', 'disciplina', 'professor.user', 'presenca']);

        $presenca = DB::transaction(function () use ($aula) {

            // 1) Cria (ou pega) a presença desta aula
            $presenca = Presenca::firstOrCreate(
                ['aula_id' => $aula->id],
                [
                    'turma_id'          => $aula->turma_id,
                    'disciplina_id'     => $aula->disciplina_id,
                    'professor_id'      => $aula->professor_id,
                    'data'              => $aula->data,
                    'quantidade_blocos' => $aula->quantidade_blocos,
                    'status'            => 'aberta',
                ]
            );

            // 2) Sincroniza alunos da turma em presenca_alunos
            $alunosIds = $aula->turma->alunos->pluck('id')->toArray();

            foreach ($alunosIds as $alunoId) {
                PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id'    => $alunoId,
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

        // Carrega itens já sincronizados
        $presenca->load([
            'alunos.aluno.user',
            'alunos.justificativa',
        ]);

        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit_from_aula', compact(
            'aula',
            'presenca',
            'justificativas'
        ));
    }

    /**
     * Salva/atualiza a presença da Aula.
     * Espera inputs no formato:
     * presencas[ALUNO_ID][bloco_1] = 1
     * presencas[ALUNO_ID][justificativa_falta_id] = X
     * presencas[ALUNO_ID][observacao] = "..."
     */
    public function updateFromAula(Request $request, Aula $aula)
    {
        $aula->load(['turma.alunos']);

        $data = $request->validate([
            'status' => 'nullable|in:aberta,finalizada',

            'presencas' => 'required|array',

            // Cada aluno dentro do array
            'presencas.*.bloco_1' => 'nullable|boolean',
            'presencas.*.bloco_2' => 'nullable|boolean',
            'presencas.*.bloco_3' => 'nullable|boolean',
            'presencas.*.bloco_4' => 'nullable|boolean',
            'presencas.*.bloco_5' => 'nullable|boolean',
            'presencas.*.bloco_6' => 'nullable|boolean',

            'presencas.*.justificativa_falta_id' => 'nullable|exists:justificativa_faltas,id',
            'presencas.*.observacao'             => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($aula, $data) {

            $presenca = Presenca::firstOrCreate(
                ['aula_id' => $aula->id],
                [
                    'turma_id'          => $aula->turma_id,
                    'disciplina_id'     => $aula->disciplina_id,
                    'professor_id'      => $aula->professor_id,
                    'data'              => $aula->data,
                    'quantidade_blocos' => $aula->quantidade_blocos,
                    'status'            => 'aberta',
                ]
            );

            // Atualiza status, se veio
            if (!empty($data['status'])) {
                $presenca->update(['status' => $data['status']]);
            }

            // Atualiza cada aluno
            foreach ($data['presencas'] as $alunoId => $payload) {

                // Segurança: só permite alunos que pertencem à turma da aula
                $pertence = $aula->turma->alunos->contains('id', (int) $alunoId);
                if (!$pertence) {
                    continue;
                }

                $item = PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id'    => (int) $alunoId,
                    ]
                );

                // Limita blocos conforme a quantidade da aula (ex: 2 h/a -> só bloco_1 e bloco_2 importam)
                $max = (int) $presenca->quantidade_blocos;

                $updates = [
                    'bloco_1' => ($max >= 1) ? (bool)($payload['bloco_1'] ?? false) : false,
                    'bloco_2' => ($max >= 2) ? (bool)($payload['bloco_2'] ?? false) : false,
                    'bloco_3' => ($max >= 3) ? (bool)($payload['bloco_3'] ?? false) : false,
                    'bloco_4' => ($max >= 4) ? (bool)($payload['bloco_4'] ?? false) : false,
                    'bloco_5' => ($max >= 5) ? (bool)($payload['bloco_5'] ?? false) : false,
                    'bloco_6' => ($max >= 6) ? (bool)($payload['bloco_6'] ?? false) : false,

                    'justificativa_falta_id' => $payload['justificativa_falta_id'] ?? null,
                    'observacao'             => $payload['observacao'] ?? null,
                ];

                $item->update($updates);
            }
        });

        return redirect()
            ->route('admin.aulas.show', $aula)
            ->with('success', 'Presenças atualizadas com sucesso.');
    }
}
