<?php

namespace App\Http\Controllers\Admin\Relatorios;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Despesa;
use App\Models\Disciplina;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\SecretariaAtendimento;
use App\Models\Turma;
use App\Models\UserDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RelatoriosDashboardController extends Controller
{
    public function index(Request $request)
    {
        $turmaId = $request->input('turma_id');
        $turno = $request->input('turno');
        $inicio = $request->input('inicio');
        $fim = $request->input('fim');

        $turmas = Turma::orderBy('nome')->get();

        $alunosQuery = Aluno::query()
            ->when($turmaId, fn($q) => $q->where('turma_id', $turmaId))
            ->when($turno, fn($q) => $q->whereHas('turma', fn($t) => $t->where('turno', $turno)))
            ->when($inicio, fn($q) => $q->whereDate('created_at', '>=', $inicio))
            ->when($fim, fn($q) => $q->whereDate('created_at', '<=', $fim));

        $alunosTotal = (clone $alunosQuery)->count();
        $alunosAtivos = (clone $alunosQuery)
            ->whereHas('matriculaModel', fn($q) => $q->where('status', 'ativo'))
            ->count();

        $matriculasDesistentes = Matricula::query()
            ->where('status', 'desistente')
            ->when($turmaId, fn($q) => $q->where('turma_id', $turmaId))
            ->when($inicio, fn($q) => $q->whereDate('data_status', '>=', $inicio))
            ->when($fim, fn($q) => $q->whereDate('data_status', '<=', $fim))
            ->count();

        $alunosSemDocumentos = Aluno::query()
            ->when($turmaId, fn($q) => $q->where('turma_id', $turmaId))
            ->when($turno, fn($q) => $q->whereHas('turma', fn($t) => $t->where('turno', $turno)))
            ->whereDoesntHave('documentos')
            ->count();

        $documentosEnviados = UserDocument::query()
            ->when($turmaId, fn($q) => $q->whereHas('aluno', fn($a) => $a->where('turma_id', $turmaId)))
            ->when($turno, fn($q) => $q->whereHas('aluno.turma', fn($t) => $t->where('turno', $turno)))
            ->when($inicio, fn($q) => $q->whereDate('created_at', '>=', $inicio))
            ->when($fim, fn($q) => $q->whereDate('created_at', '<=', $fim))
            ->count();

        $atendimentosPendentes = SecretariaAtendimento::query()
            ->where('status', 'pendente')
            ->when($inicio, fn($q) => $q->whereDate('created_at', '>=', $inicio))
            ->when($fim, fn($q) => $q->whereDate('created_at', '<=', $fim))
            ->count();

        $totalDespesasPeriodo = Despesa::query()
            ->when($inicio, fn($q) => $q->whereDate('data', '>=', $inicio))
            ->when($fim, fn($q) => $q->whereDate('data', '<=', $fim))
            ->sum('valor');

        $disciplinasTotal = Disciplina::count();
        $professoresTotal = Professor::count();
        $turmasTotal = Turma::count();

        $topTurmas = Turma::withCount('alunos')
            ->when($turno, fn($q) => $q->where('turno', $turno))
            ->orderByDesc('alunos_count')
            ->limit(5)
            ->get();

        $proximosAniversarios = $this->proximosAniversarios();

        return view('admin.relatorios.index', compact(
            'turmas',
            'turmaId',
            'turno',
            'inicio',
            'fim',
            'alunosTotal',
            'alunosAtivos',
            'matriculasDesistentes',
            'alunosSemDocumentos',
            'documentosEnviados',
            'atendimentosPendentes',
            'totalDespesasPeriodo',
            'disciplinasTotal',
            'professoresTotal',
            'turmasTotal',
            'topTurmas',
            'proximosAniversarios'
        ));
    }

    private function proximosAniversarios(): array
    {
        $hoje = Carbon::today();
        $limite = Carbon::today()->addDays(7);

        return Aluno::with('user')
            ->whereNotNull('data_nascimento')
            ->get()
            ->map(function ($aluno) use ($hoje) {
                $nasc = Carbon::parse($aluno->data_nascimento);
                $proximo = $nasc->copy()->year($hoje->year);
                if ($proximo->lt($hoje)) {
                    $proximo->addYear();
                }
                $aluno->proximo_aniversario = $proximo;
                return $aluno;
            })
            ->filter(fn($aluno) => $aluno->proximo_aniversario->lte($limite))
            ->sortBy('proximo_aniversario')
            ->take(6)
            ->values()
            ->all();
    }
}
