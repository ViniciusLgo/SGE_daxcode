<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use App\Models\User;
use App\Models\CategoriaDespesa;
use App\Models\CentroCusto;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Despesa::query()->with('categoria', 'centroCusto', 'user');

        // FILTROS DINAMICOS
        if ($request->filled('data_inicio')) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('centro_custo_id')) {
            $query->where('centro_custo_id', $request->centro_custo_id);
        }

        if ($request->filled('forma_pagamento')) {
            $query->where('forma_pagamento', $request->forma_pagamento);
        }

        if ($request->filled('status_pagamento')) {
            $query->where('status_pagamento', $request->status_pagamento);
        }

        if ($request->filled('responsavel_id')) {
            $query->where('responsavel_id', $request->responsavel_id);
        }

        if ($request->filled('fornecedor')) {
            $query->where('fornecedor', 'LIKE', '%' . $request->fornecedor . '%');
        }

        if ($request->filled('valor_min')) {
            $query->where('valor', '>=', $request->valor_min);
        }

        if ($request->filled('valor_max')) {
            $query->where('valor', '<=', $request->valor_max);
        }

        // FINAL
        $despesas = $query
            ->orderBy('data', 'desc')
            ->paginate(20)
            ->appends($request->query()); // mantem filtros na paginacao

        $categorias = CategoriaDespesa::orderBy('nome')->get();
        $centros = CentroCusto::orderBy('nome')->get();
        $usuarios = User::orderBy('name')->get();

        return view('admin.financeiro.despesas.index', compact(
            'despesas',
            'categorias',
            'centros',
            'usuarios'
        ));
    }


    public function create()
    {
        $categorias = CategoriaDespesa::orderBy('nome')->get();
        $centros = CentroCusto::orderBy('nome')->get();
        $usuarios = User::where('tipo', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.financeiro.despesas.create', compact('categorias', 'centros', 'usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias_despesas,id',
            'centro_custo_id' => 'nullable|exists:centros_custos,id',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|max:4096',

            // novos campos
            'fornecedor' => 'nullable|string|max:255',
            'forma_pagamento' => 'nullable|string|max:50',
            'status_pagamento' => 'nullable|string|max:50',
            'numero_nf' => 'nullable|string|max:100',
            'conta' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('arquivo')) {
            if ($despesa->arquivo && \Storage::disk('public')->exists($despesa->arquivo)) {
                \Storage::disk('public')->delete($despesa->arquivo);
            }
            $data['arquivo'] = $request->file('arquivo')->store('despesas', 'public');
        } else {
            unset($data['arquivo']);
        }

        // se nao vier responsavel, assume o logado
        if (empty($data['responsavel_id'])) {
            $data['responsavel_id'] = auth()->id();
        }

        // user que cadastrou
        $data['user_id'] = auth()->id();

        Despesa::create($data);

        return redirect()
            ->route('admin.financeiro.despesas.index')
            ->with('success', 'Despesa lancada com sucesso!');
    }

    public function edit(Despesa $despesa)
    {
        $categorias = CategoriaDespesa::orderBy('nome')->get();
        $centros = CentroCusto::orderBy('nome')->get();
        $usuarios = User::where('tipo', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.financeiro.despesas.edit', compact('despesa', 'categorias', 'centros', 'usuarios'));
    }

    public function update(Request $request, Despesa $despesa)
    {
        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias_despesas,id',
            'centro_custo_id' => 'nullable|exists:centros_custos,id',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|file|max:4096',

            'fornecedor' => 'nullable|string|max:255',
            'forma_pagamento' => 'nullable|string|max:50',
            'status_pagamento' => 'nullable|string|max:50',
            'numero_nf' => 'nullable|string|max:100',
            'conta' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('arquivo')) {
            $data['arquivo'] = $request->file('arquivo')->store('despesas', 'public');
        }

        if (empty($data['responsavel_id'])) {
            $data['responsavel_id'] = auth()->id();
        }

        $despesa->update($data);

        return redirect()
            ->route('admin.financeiro.despesas.index')
            ->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(Despesa $despesa)
    {
        if ($despesa->arquivo && \Storage::disk('public')->exists($despesa->arquivo)) {
            \Storage::disk('public')->delete($despesa->arquivo);
        }

        $despesa->delete();

        return redirect()
            ->route('admin.financeiro.despesas.index')
            ->with('success', 'Despesa excluida com sucesso!');
    }

    public function duplicar(Despesa $despesa)
    {
        $nova = $despesa->replicate();
        $nova->data = now();
        $nova->save();

        return back()->with('success', 'Despesa duplicada com sucesso!');
    }



}
