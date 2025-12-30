<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Turma;
use Illuminate\Http\Request;

class RelatoriosEvasaoController extends Controller
{
    public function index(Request $request)
    {
        // -----------------------------
        // Filtros (GET)
        // -----------------------------
        $turmaId = $request->input('turma_id');
        $ano     = $request->input('ano');
        $turno   = $request->input('turno');
        $inicio  = $request->input('inicio'); // YYYY-MM-DD
        $fim     = $request->input('fim');    // YYYY-MM-DD

        // Base: matrículas + turma
        $matriculas = Matricula::query()->with('turma');

        // Filtro por turma (turma atual da matrícula)
        if ($turmaId) {
            $matriculas->where('turma_id', $turmaId);
        }

        // Filtros por atributos da turma (ano/turno)
        if ($ano) {
            $matriculas->whereHas('turma', fn ($q) => $q->where('ano', $ano));
        }
        if ($turno) {
            $matriculas->whereHas('turma', fn ($q) => $q->where('turno', $turno));
        }

        // KPIs gerais (com filtros aplicados)
        $total = (clone $matriculas)->count();
        $ativos = (clone $matriculas)->where('status', 'ativo')->count();
        $desistentes = (clone $matriculas)->where('status', 'desistente')->count();
        $taxaEvasao = $total > 0 ? round(($desistentes / $total) * 100, 1) : 0;

        // -----------------------------
        // Evasão por turma (ranking)
        // -----------------------------
        // Considera o status atual da matrícula (com filtros).
        $evasaoPorTurma = (clone $matriculas)
            ->selectRaw('turma_id,
                     SUM(CASE WHEN status = "desistente" THEN 1 ELSE 0 END) as desistentes,
                     COUNT(*) as total')
            ->groupBy('turma_id')
            ->get()
            ->map(function ($row) {
                $row->taxa = $row->total > 0 ? round(($row->desistentes / $row->total) * 100, 1) : 0;
                return $row;
            })
            ->sortByDesc('taxa')
            ->values();

        // Carrega nome da turma para exibir
        $turmasMap = Turma::whereIn('id', $evasaoPorTurma->pluck('turma_id')->filter())->get()->keyBy('id');
        $evasaoPorTurma = $evasaoPorTurma->map(function ($row) use ($turmasMap) {
            $row->turma_nome = $turmasMap[$row->turma_id]->nome ?? '—';
            return $row;
        });

        // -----------------------------
        // Motivos mais frequentes (historico)
        // -----------------------------
        // Base: matricula_historicos (evento de status -> desistente)
        $historicos = \App\Models\MatriculaHistorico::query()
            ->where('tipo_evento', 'status')
            ->where('status_novo', 'desistente');

        // aplicar filtros via relação com matriculas/turmas
        if ($turmaId) {
            $historicos->whereHas('matricula', fn ($q) => $q->where('turma_id', $turmaId));
        }
        if ($ano) {
            $historicos->whereHas('matricula.turma', fn ($q) => $q->where('ano', $ano));
        }
        if ($turno) {
            $historicos->whereHas('matricula.turma', fn ($q) => $q->where('turno', $turno));
        }

        // Filtro de período (created_at do evento)
        if ($inicio) {
            $historicos->whereDate('created_at', '>=', $inicio);
        }
        if ($fim) {
            $historicos->whereDate('created_at', '<=', $fim);
        }

        $topMotivos = (clone $historicos)
            ->selectRaw('COALESCE(NULLIF(motivo, ""), "Não informado") as motivo_label, COUNT(*) as total')
            ->groupBy('motivo_label')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // -----------------------------
        // Série temporal (Chart.js) - desistências por dia
        // -----------------------------
        $serie = (clone $historicos)
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $chartLabels = $serie->pluck('dia')->map(fn ($d) => (string)$d)->toArray();
        $chartData   = $serie->pluck('total')->toArray();

        // Para selects
        $turmas = Turma::orderBy('nome')->get();

        // -----------------------------
// GRÁFICO POR TURMA (BARRAS)
// -----------------------------
        $evasaoPorTurmaChart = (clone $matriculas)
            ->selectRaw('turma_id,
        SUM(CASE WHEN status = "desistente" THEN 1 ELSE 0 END) as desistentes')
            ->groupBy('turma_id')
            ->get();

        $labelsTurma = [];
        $dataTurma = [];

        $turmasChartMap = Turma::whereIn('id', $evasaoPorTurmaChart->pluck('turma_id')->filter())
            ->get()
            ->keyBy('id');

        foreach ($evasaoPorTurmaChart as $row) {
            $labelsTurma[] = $turmasChartMap[$row->turma_id]->nome ?? '—';
            $dataTurma[]   = (int) $row->desistentes;
        }

// -----------------------------
// EVASÃO POR MÊS (LINHA)
// -----------------------------
        $serieMensal = (clone $historicos)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $labelsMes = $serieMensal->pluck('mes')->toArray();
        $dataMes   = $serieMensal->pluck('total')->toArray();

// -----------------------------
// LISTA DE DESISTENTES (DETALHE)
// -----------------------------
        $listaDesistentes = \App\Models\Matricula::with(['aluno.user', 'turma'])
            ->where('status', 'desistente')
            ->when($turmaId, fn ($q) => $q->where('turma_id', $turmaId))
            ->orderByDesc('data_status')
            ->limit(20)
            ->get();


        return view('admin.relatorios.evasao.index', compact(
            'total', 'ativos', 'desistentes', 'taxaEvasao',
            'turmas',
            'evasaoPorTurma',
            'topMotivos',
            'chartLabels',
            'chartData',
            'turmaId', 'ano', 'turno', 'inicio', 'fim',
            'labelsTurma',
            'dataTurma',
            'labelsMes',
            'dataMes',
            'listaDesistentes'

        ));
    }

}
