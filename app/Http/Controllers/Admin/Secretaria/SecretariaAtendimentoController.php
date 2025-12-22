<?php

namespace App\Http\Controllers\Admin\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SecretariaAtendimento;
use App\Models\Aluno;
use App\Models\Responsavel;
use Illuminate\Http\Request;

class SecretariaAtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = SecretariaAtendimento::with(['aluno', 'responsavel'])
            ->orderByDesc('data_atendimento')
            ->paginate(10);

        return view('admin.secretaria.atendimentos.index', compact('atendimentos'));
    }

    public function create()
    {
        $alunos = Aluno::orderBy('id')->get();
        $responsaveis = Responsavel::orderBy('id')->get();

        return view('admin.secretaria.atendimentos.create', compact('alunos', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:255',
            'status' => 'required|in:pendente,concluido,cancelado',
            'aluno_id' => 'nullable|exists:alunos,id',
            'responsavel_id' => 'nullable|exists:responsaveis,id',
            'observacao' => 'nullable|string',
            'data_atendimento' => 'required|date',
        ]);

        SecretariaAtendimento::create($request->all());

        return redirect()
            ->route('admin.secretaria.atendimentos.index')
            ->with('success', 'Atendimento registrado com sucesso.');
    }
}
