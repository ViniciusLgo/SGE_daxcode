<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AvaliacaoResultado;
use App\Models\DisciplinaTurma;
use App\Models\Turma;
use Illuminate\Support\Collection;

class BoletimController extends Controller
{
    private function professorId(): int
    {
        $professor = auth()->user()?->professor;
        abort_unless($professor, 403);
        return (int) $professor->id;
    }

    private function turmaPermitida(Turma $turma, int $professorId): bool
    {
        return DisciplinaTurma::where('turma_id', $turma->id)
            ->whereHas('professores', function ($q) use ($professorId) {
                $q->where('professor_id', $professorId);
            })
            ->exists();
    }

    public function index()
    {
        $professorId = $this->professorId();

        $turmas = Turma::whereHas('disciplinaTurmas.professores', function ($q) use ($professorId) {
            $q->where('professor_id', $professorId);
        })
            ->withCount('alunos')
            ->orderBy('nome')
            ->get();

        return view('professores.boletim.index', compact('turmas'));
    }

    public function aluno(Aluno $aluno)
    {
        $professorId = $this->professorId();
        $aluno->load(['user', 'turma']);

        abort_unless($aluno->turma && $this->turmaPermitida($aluno->turma, $professorId), 403);

        $resultados = AvaliacaoResultado::select([
            'id',
            'avaliacao_id',
            'aluno_id',
            'nota',
            'entregue',
        ])->with(['avaliacao.disciplina'])
            ->where('aluno_id', $aluno->id)
            ->get();

        $boletim = $resultados
            ->groupBy(fn ($r) => $r->avaliacao->disciplina->id)
            ->map(function (Collection $items) {
                $disciplina = $items->first()->avaliacao->disciplina;

                $notas = $items->map(function ($item) {
                    if (!$item->entregue || $item->nota === null) {
                        return 0;
                    }
                    return (float) $item->nota;
                });

                $media = $notas->count() > 0 ? round($notas->avg(), 2) : 0;

                $situacao = match (true) {
                    $media >= 6 => 'Aprovado',
                    $media >= 4 => 'Recuperacao',
                    default     => 'Reprovado',
                };

                return [
                    'disciplina' => $disciplina,
                    'avaliacoes' => $items,
                    'media'      => $media,
                    'situacao'   => $situacao,
                ];
            });

        return view('admin.gestao_academica.boletim.aluno', [
            'aluno' => $aluno,
            'boletim' => $boletim,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }

    public function turma(Turma $turma)
    {
        $professorId = $this->professorId();
        abort_unless($this->turmaPermitida($turma, $professorId), 403);

        $turma->load('alunos.user');

        $resultados = AvaliacaoResultado::select([
            'id',
            'avaliacao_id',
            'aluno_id',
            'nota',
            'entregue',
        ])->with([
            'avaliacao.disciplina',
            'aluno.user'
        ])
            ->whereHas('aluno', fn ($q) =>
                $q->where('turma_id', $turma->id)
            )
            ->get();

        $disciplinas = $resultados
            ->pluck('avaliacao.disciplina')
            ->unique('id')
            ->sortBy('nome')
            ->values();

        $boletins = $resultados
            ->groupBy('aluno_id')
            ->map(function ($itemsPorAluno) {
                $aluno = $itemsPorAluno->first()->aluno;

                $porDisciplina = $itemsPorAluno
                    ->groupBy(fn ($r) => $r->avaliacao->disciplina->id)
                    ->map(function ($items) {
                        $disciplina = $items->first()->avaliacao->disciplina;

                        $notas = $items->map(function ($item) {
                            if (!$item->entregue || $item->nota === null) {
                                return 0;
                            }
                            return (float) $item->nota;
                        });

                        $media = $notas->count() ? round($notas->avg(), 2) : 0;

                        $situacao = match (true) {
                            $media >= 6 => 'Aprovado',
                            $media >= 4 => 'Recuperacao',
                            default     => 'Reprovado',
                        };

                        return [
                            'disciplina' => $disciplina,
                            'media'      => $media,
                            'situacao'   => $situacao,
                            'avaliacoes' => $items,
                        ];
                    });

                return [
                    'aluno'       => $aluno,
                    'disciplinas' => $porDisciplina,
                ];
            });

        return view('admin.gestao_academica.boletim.turma', [
            'turma' => $turma,
            'boletins' => $boletins,
            'disciplinas' => $disciplinas,
            'routePrefix' => 'professor',
            'isProfessor' => true,
        ]);
    }
}
