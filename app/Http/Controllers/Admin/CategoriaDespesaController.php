<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaDespesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaDespesaController extends Controller
{
    public function index()
    {
        $categorias = CategoriaDespesa::withCount('despesas')
            ->withSum('despesas as total_gasto', 'valor')
            ->orderBy('nome')
            ->get();

        // Dados para grafico
        $graficoCategorias = $categorias->map(fn ($c) => [
            'nome'  => $c->nome,
            'total' => $c->total_gasto ?? 0,
        ]);

        return view(
            'admin.financeiro.categorias.index',
            compact('categorias', 'graficoCategorias')
        );
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
            ->with('success', 'Categoria excluida com sucesso!');
    }

    public function show(CategoriaDespesa $categoria)
    {
        // Carrega despesas vinculadas + centro de custo
        $categoria->load([
            'despesas.centroCusto'
        ]);

        return view(
            'admin.financeiro.categorias.show',
            compact('categoria')
        );
    }
}
