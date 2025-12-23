<?php

namespace App\Http\Controllers\Admin\GestaoAcademica;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\AvaliacaoResultado;
use App\Models\Turma;
use Illuminate\Support\Collection;

class BoletimController extends Controller
{
    /**
     * 📘 Boletim detalhado de um aluno
     */
    public function aluno(Aluno $aluno)
    {
        // Carrega relações necessárias
        $aluno->load(['user', 'turma']);

        // Busca TODOS os resultados do aluno
        $resultados = AvaliacaoResultado::with(['avaliacao.disciplina'])
            ->where('aluno_id', $aluno->id)
            ->get();

        /**
         * Agrupa resultados por disciplina
         */
        $boletim = $resultados
            ->groupBy(fn ($r) => $r->avaliacao->disciplina->id)
            ->map(function (Collection $items) {

                $disciplina = $items->first()->avaliacao->disciplina;

                // Notas: não entregue ou nulo = 0
                $notas = $items->map(function ($item) {
                    if (!$item->entregue || $item->nota === null) {
                        return 0;
                    }
                    return (float) $item->nota;
                });

                $media = $notas->count() > 0
                    ? round($notas->avg(), 2)
                    : 0;

                // Situação do aluno na disciplina
                $situacao = match (true) {
                    $media >= 6 => 'Aprovado',
                    $media >= 4 => 'Recuperação',
                    default     => 'Reprovado',
                };

                return [
                    'disciplina' => $disciplina,
                    'avaliacoes' => $items,
                    'media'      => $media,
                    'situacao'   => $situacao,
                ];
            });

        return view(
            'admin.gestao_academica.boletim.aluno',
            compact('aluno', 'boletim')
        );
    }

    /**
     * 📘 Boletim consolidado da turma (visão da secretaria)
     */
    public function turma(Turma $turma)
    {
        // Carrega alunos e usuários
        $turma->load('alunos.user');

        // Busca todos os resultados dos alunos da turma
        $resultados = AvaliacaoResultado::with([
            'avaliacao.disciplina',
            'aluno.user'
        ])
            ->whereHas('aluno', fn ($q) =>
            $q->where('turma_id', $turma->id)
            )
            ->get();

        /**
         * Lista de disciplinas da turma
         * (usada como colunas na tabela)
         */
        $disciplinas = $resultados
            ->pluck('avaliacao.disciplina')
            ->unique('id')
            ->sortBy('nome')
            ->values();

        /**
         * Agrupa por aluno -> disciplina
         */
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

                        $media = $notas->count()
                            ? round($notas->avg(), 2)
                            : 0;

                        $situacao = match (true) {
                            $media >= 6 => 'Aprovado',
                            $media >= 4 => 'Recuperação',
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

        return view(
            'admin.gestao_academica.boletim.turma',
            compact('turma', 'boletins', 'disciplinas')
        );
    }
}
