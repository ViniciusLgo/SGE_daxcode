<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\AvaliacaoResultado;
use Illuminate\Support\Collection;

class BoletimController extends Controller
{
    public function index()
    {
        $aluno = auth()->user()?->aluno;
        abort_unless($aluno, 403);

        $aluno->load(['user', 'turma']);

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
            'routePrefix' => 'aluno',
            'isAluno' => true,
        ]);
    }
}
