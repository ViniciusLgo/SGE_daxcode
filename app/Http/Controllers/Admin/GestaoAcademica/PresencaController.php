<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Presenca;
use App\Models\PresencaAluno;
use App\Models\JustificativaFalta;
use App\Models\Turma;
use App\Models\Professor;
use App\Models\Disciplina;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * PresencaController
 *
 * Responsável por:
 * - Listagem de aulas + indicadores de presença (index)
 * - Visualização da presença consolidada (show)
 * - Edição da presença (edit/update) pelo módulo de Presenças
 * - Registro/Edição de presença a partir da Aula (editFromAula/updateFromAula)
 *
 * Regras de negócio principais:
 * 1) Justificativas:
 *    - No formulário, listar apenas justificativas ATIVAS (ativo = true)
 *    - No histórico (show), pode exibir justificativas mesmo que tenham sido desativadas depois
 * 2) Alunos desistentes/inativos:
 *    - NÃO entram em novas presenças (editFromAula sincroniza apenas ALUNOS ATIVOS)
 *    - NÃO aparecem nas telas de edição (views filtram)
 *    - PODEM aparecer no histórico (show), mantendo rastreabilidade
 * 3) Blocos:
 *    - Respeitar quantidade_blocos da aula/presença
 *    - Campos além do max são forçados para false
 * 4) Observação obrigatória:
 *    - Se a justificativa exige_observacao = true, a observação deve ser preenchida
 */
class PresencaController extends Controller
{
    /* =====================================================
     * INDEX – LISTAGEM DE AULAS + KPIs + FILTROS + GRÁFICO
     * ===================================================== */
    public function index(Request $request)
    {
        // ================= PERÍODO =================
        // Se usuário não informar, assume últimos 30 dias.
        $inicio = $request->filled('data_inicio')
            ? Carbon::createFromFormat('Y-m-d', $request->data_inicio)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $fim = $request->filled('data_fim')
            ? Carbon::createFromFormat('Y-m-d', $request->data_fim)->endOfDay()
            : now()->endOfDay();

        // ================= QUERY BASE =================
        // A ideia do index: sempre listar "aulas" e anexar "presença" se existir.
        $query = Aula::with(['turma', 'disciplina', 'professor.user', 'presenca'])
            ->whereBetween('data', [$inicio->toDateString(), $fim->toDateString()])
            ->orderByDesc('data')
            ->orderByDesc('hora_inicio');

        // ================= FILTRO SIMPLES: STATUS =================
        // status:
        // - sem_presenca: aulas que não têm presença ainda
        // - aberta/finalizada: aulas com presença e status específico
        if ($request->filled('status')) {
            if ($request->status === 'sem_presenca') {
                $query->whereDoesntHave('presenca');
            } else {
                $query->whereHas('presenca', function ($q) use ($request) {
                    $q->where('status', $request->status);
                });
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
        // Observação: clone para não interferir na query principal.
        $totalAulas = (clone $query)->count();

        $comPresenca = (clone $query)->whereHas('presenca')->count();

        $finalizadas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'finalizada'))
            ->count();

        $abertas = (clone $query)
            ->whereHas('presenca', fn($q) => $q->where('status', 'aberta'))
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

        // ================= DADOS DO GRÁFICO =================
        $chartStatus = [
            'labels' => ['Sem presença', 'Aberta', 'Finalizada'],
            'data'   => [$semPresenca, $abertas, $finalizadas],
        ];

        return view('admin.gestao_academica.presencas.index', [
            'aulas'                 => $aulas,
            'inicio'                => $inicio,
            'fim'                   => $fim,
            'totalAulas'            => $totalAulas,
            'comPresenca'           => $comPresenca,
            'semPresenca'           => $semPresenca,
            'abertas'               => $abertas,
            'finalizadas'           => $finalizadas,
            'cobertura'             => $cobertura,
            'chartStatus'           => $chartStatus,
            'aulasPendentesAntigas' => $aulasPendentesAntigas,
            'limiteDias'            => $limiteDias,

            // dados para filtros
            'turmas'       => Turma::orderBy('nome')->get(),
            'professores'  => Professor::with('user')->orderBy('id')->get(),
            'disciplinas'  => Disciplina::orderBy('nome')->get(),
        ]);
    }

    /* =====================================================
     * SHOW – VISUALIZAÇÃO CONSOLIDADA (HISTÓRICO)
     * ===================================================== */
    public function show(Presenca $presenca)
    {
        // IMPORTANTE:
        // - Aqui mostramos histórico completo da presença.
        // - Alunos desistentes podem aparecer (rastreamento histórico).
        // - Justificativas desativadas podem aparecer (rastreamento histórico).
        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel', // para exibir status no show
            'alunos.justificativa',        // histórico (pode incluir inativas)
        ]);

        return view('admin.gestao_academica.presencas.show', compact('presenca'));
    }

    /* =====================================================
     * EDIT – EDIÇÃO DA PRESENÇA (MÓDULO PRESENÇAS)
     * ===================================================== */
    public function edit(Presenca $presenca)
    {
        // Carrega dados para a tela de edição
        $presenca->load([
            'aula',
            'turma',
            'disciplina',
            'professor.user',
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel', // necessário para filtrar ativos na view
            'alunos.justificativa',        // pode carregar tudo; a view filtra o aluno
        ]);

        // No formulário: somente justificativas ATIVAS
        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit', compact(
            'presenca',
            'justificativas'
        ));
    }

    /* =====================================================
     * UPDATE – SALVA EDIÇÃO DA PRESENÇA (MÓDULO PRESENÇAS)
     * ===================================================== */
    public function update(Request $request, Presenca $presenca)
    {
        // Validação básica dos campos
        $data = $request->validate([
            'status' => 'required|in:aberta,finalizada',

            'presencas' => 'required|array',

            // Blocos possíveis (o update usa quantidade_blocos para limitar o que importa)
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

            // Atualiza status da presença
            $presenca->update([
                'status' => $data['status'],
            ]);

            // Atualiza registros por aluno
            foreach ($data['presencas'] as $alunoId => $payload) {

                // Localiza item existente (não cria aqui, pois a presença já existe)
                $item = $presenca->alunos()
                    ->where('aluno_id', $alunoId)
                    ->first();

                if (!$item) {
                    continue;
                }

                // REGRA: justificativa pode exigir observação
                if (!empty($payload['justificativa_falta_id'])) {
                    $just = JustificativaFalta::find($payload['justificativa_falta_id']);

                    if ($just && $just->exige_observacao && empty($payload['observacao'])) {
                        throw ValidationException::withMessages([
                            "presencas.$alunoId.observacao" =>
                                "A justificativa '{$just->nome}' exige observação.",
                        ]);
                    }
                }

                // Limita os blocos conforme a quantidade configurada na presença
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

    /* =====================================================
     * EDIT FROM AULA
     *
     * Abre a tela de presença da Aula.
     * - Cria Presenca automaticamente se não existir (1 por aula)
     * - Sincroniza APENAS alunos ATIVOS (evita desistentes na chamada)
     * ===================================================== */
    public function editFromAula(Aula $aula)
    {
        // Carrega contexto da aula
        // Observação: evitamos carregar turma.alunos diretamente sem filtro,
        // pois a sincronização será feita via query ->ativos().
        $aula->load(['turma', 'disciplina', 'professor.user', 'presenca']);

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

            // 2) Sincroniza APENAS alunos ATIVOS da turma em presenca_alunos
            // Usa o scopeAtivos() do model Aluno (baseado no status da matrícula).
            $alunosAtivosIds = $aula->turma
                ->alunos()
                ->ativos()
                ->pluck('id')
                ->toArray();

            foreach ($alunosAtivosIds as $alunoId) {
                PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id'    => $alunoId,
                    ],
                    [
                        // defaults (garante consistência)
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

        // Carrega itens já sincronizados (inclui aluno + matrícula para a view filtrar)
        $presenca->load([
            'alunos.aluno.user',
            'alunos.aluno.matriculaModel',
            'alunos.justificativa', // histórico (pode incluir inativas), select usa $justificativas (ativas)
        ]);

        // No formulário: somente justificativas ATIVAS
        $justificativas = JustificativaFalta::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.gestao_academica.presencas.edit_from_aula', compact(
            'aula',
            'presenca',
            'justificativas'
        ));
    }

    /* =====================================================
     * UPDATE FROM AULA
     *
     * Salva/atualiza a presença da Aula.
     * Espera inputs no formato:
     * presencas[ALUNO_ID][bloco_1] = 1
     * presencas[ALUNO_ID][justificativa_falta_id] = X
     * presencas[ALUNO_ID][observacao] = "..."
     *
     * Regras aplicadas:
     * - Só permite atualizar alunos ATIVOS da turma
     * - Justificativa pode exigir observação
     * - Limita blocos conforme quantidade_blocos
     * ===================================================== */
    public function updateFromAula(Request $request, Aula $aula)
    {
        // Carrega turma para validação de pertinência
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
            'presencas.*.observacao'             => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($aula, $data) {

            // 1) Garante existência da presença
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

            // 2) Atualiza status se veio
            if (!empty($data['status'])) {
                $presenca->update(['status' => $data['status']]);
            }

            // 3) Lista de alunos ATIVOS (blindagem de segurança)
            // Isso impede que um payload "manual" atualize desistentes.
            $idsAtivos = $aula->turma
                ->alunos()
                ->ativos()
                ->pluck('id')
                ->map(fn($id) => (int)$id)
                ->toArray();

            $idsAtivosLookup = array_flip($idsAtivos);

            // 4) Atualiza cada aluno presente no payload
            foreach ($data['presencas'] as $alunoId => $payload) {
                $alunoId = (int) $alunoId;

                // Segurança: só permite alunos ATIVOS da turma
                if (!isset($idsAtivosLookup[$alunoId])) {
                    continue;
                }

                // REGRA: justificativa exige observação
                if (!empty($payload['justificativa_falta_id'])) {
                    $just = JustificativaFalta::find($payload['justificativa_falta_id']);

                    if ($just && $just->exige_observacao && empty($payload['observacao'])) {
                        throw ValidationException::withMessages([
                            "presencas.$alunoId.observacao" =>
                                "A justificativa '{$just->nome}' exige observação.",
                        ]);
                    }
                }

                // Garante item do aluno em presenca_alunos
                $item = PresencaAluno::firstOrCreate(
                    [
                        'presenca_id' => $presenca->id,
                        'aluno_id'    => $alunoId,
                    ]
                );

                // Limita blocos conforme quantidade da presença
                $max = (int) $presenca->quantidade_blocos;

                $item->update([
                    'bloco_1' => ($max >= 1) ? (bool)($payload['bloco_1'] ?? false) : false,
                    'bloco_2' => ($max >= 2) ? (bool)($payload['bloco_2'] ?? false) : false,
                    'bloco_3' => ($max >= 3) ? (bool)($payload['bloco_3'] ?? false) : false,
                    'bloco_4' => ($max >= 4) ? (bool)($payload['bloco_4'] ?? false) : false,
                    'bloco_5' => ($max >= 5) ? (bool)($payload['bloco_5'] ?? false) : false,
                    'bloco_6' => ($max >= 6) ? (bool)($payload['bloco_6'] ?? false) : false,

                    'justificativa_falta_id' => $payload['justificativa_falta_id'] ?? null,
                    'observacao'             => $payload['observacao'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.aulas.show', $aula)
            ->with('success', 'Presenças atualizadas com sucesso.');
    }
}
