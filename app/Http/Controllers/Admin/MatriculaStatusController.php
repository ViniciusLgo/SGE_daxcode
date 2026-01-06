<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;

class MatriculaStatusController extends Controller
{
    /**
     * DESISTIR
     */
    public function desistir(Request $request, Aluno $aluno)
    {
        $request->validate([
            'motivo'      => 'required|string|max:255',
            'observacao'  => 'nullable|string',
        ]);

        $matricula = $aluno->matriculaModel;

        if (!$matricula) {
            return redirect()
                ->back()
                ->with('error', 'Este aluno nao possui matricula ativa.');
        }

        $matricula->desistir(
            $request->motivo,
            $request->observacao,
            auth()->id()
        );

        return redirect()
            ->back()
            ->with('success', 'Aluno marcado como desistente com sucesso.');
    }

    /**
     * REATIVAR
     */
    public function reativar(Request $request, Aluno $aluno)
    {
        $request->validate([
            'motivo'      => 'nullable|string|max:255',
            'observacao'  => 'nullable|string',
        ]);

        $matricula = $aluno->matriculaModel;

        if (!$matricula) {
            return redirect()
                ->back()
                ->with('error', 'Este aluno nao possui matricula para reativacao.');
        }

        $matricula->reativar(
            $request->motivo,
            $request->observacao,
            auth()->id()
        );

        return redirect()
            ->back()
            ->with('success', 'Aluno reativado com sucesso.');
    }
}
