<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentroCusto;
use Illuminate\Http\Request;

class CentroCustoController extends Controller
{
    public function index()
    {
        $centros = CentroCusto::orderBy('nome')->get();
        return view('admin.financeiro.centros.index', compact('centros'));
    }

    public function create()
    {
        return view('admin.financeiro.centros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        CentroCusto::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao
        ]);

        return redirect()
            ->route('admin.financeiro.centros.index')
            ->with('success', 'Centro de custo criado com sucesso!');
    }

    public function edit(CentroCusto $centro)
    {
        return view('admin.financeiro.centros.edit', compact('centro'));
    }

    public function update(Request $request, CentroCusto $centro)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $centro->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao
        ]);

        return redirect()
            ->route('admin.financeiro.centros.index')
            ->with('success', 'Centro de custo atualizado com sucesso!');
    }

    public function destroy(CentroCusto $centro)
    {
        $centro->delete();

        return redirect()
            ->route('admin.financeiro.centros.index')
            ->with('success', 'Centro de custo excluido com sucesso!');
    }
}
