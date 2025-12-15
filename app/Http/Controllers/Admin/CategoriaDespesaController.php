<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaDespesa;
use Illuminate\Http\Request;

class CategoriaDespesaController extends Controller
{
    public function index()
    {
        $categorias = CategoriaDespesa::orderBy('nome')->get();
        return view('admin.financeiro.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.financeiro.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        CategoriaDespesa::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao
        ]);

        return redirect()
            ->route('admin.financeiro.categorias.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(CategoriaDespesa $categoria)
    {
        return view('admin.financeiro.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, CategoriaDespesa $categoria)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $categoria->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao
        ]);

        return redirect()
            ->route('admin.financeiro.categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(CategoriaDespesa $categoria)
    {
        $categoria->delete();

        return redirect()
            ->route('admin.financeiro.categorias.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }
}
