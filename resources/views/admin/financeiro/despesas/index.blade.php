@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Despesas do Projeto Social</h4>
            <p class="text-muted mb-0">Lançamentos de gastos, por categoria e centro de custo.</p>
        </div>
        <a href="{{ route('admin.financeiro.despesas.create') }}" class="btn btn-primary">
            + Nova Despesa
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($despesas->count() == 0)
        <div class="alert alert-info">
            Nenhuma despesa lançada ainda.
        </div>
    @else
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-0">

                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-body">

                        @php
                            $filtrosAtivos = request()->anyFilled([
                                'data_inicio','data_fim','categoria_id','centro_custo_id',
                                'forma_pagamento','status_pagamento','responsavel_id',
                                'fornecedor','valor_min','valor_max'
                            ]);
                        @endphp

                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-body">

                                <form method="GET" class="row g-3">

                                    {{-- ========================= --}}
                                    {{-- FILTROS SIMPLES --}}
                                    {{-- ========================= --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Data início</label>
                                        <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Data fim</label>
                                        <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Categoria</label>
                                        <select name="categoria_id" class="form-select">
                                            <option value="">Todas</option>
                                            @foreach($categorias as $c)
                                                <option value="{{ $c->id }}" @selected(request('categoria_id') == $c->id)>
                                                    {{ $c->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary w-100">Filtrar</button>
                                    </div>

                                    {{-- BOTÃO EXPANDIR --}}
                                    <div class="col-12 text-end mt-2">
                                        <a class="text-primary fw-semibold d-inline-flex align-items-center"
                                           data-bs-toggle="collapse"
                                           href="#filtrosAvancados"
                                           aria-expanded="{{ $filtrosAtivos ? 'true' : 'false' }}"
                                           id="toggleFiltrosAvancados">

                    <span id="textoToggle">
                        {{ $filtrosAtivos ? 'Ocultar filtros avançados' : 'Mostrar filtros avançados' }}
                    </span>

                                            <i class="ms-1 bi" id="iconeToggle" style="transition: .3s;"></i>
                                        </a>
                                    </div>

                                    {{-- ========================= --}}
                                    {{-- FILTROS AVANÇADOS --}}
                                    {{-- ========================= --}}
                                    <div class="collapse {{ $filtrosAtivos ? 'show' : '' }} mt-4" id="filtrosAvancados">
                                        <div class="row g-3">

                                            <div class="col-md-3">
                                                <label class="form-label">Centro de Custo</label>
                                                <select name="centro_custo_id" class="form-select">
                                                    <option value="">Todos</option>
                                                    @foreach($centros as $centro)
                                                        <option value="{{ $centro->id }}" @selected(request('centro_custo_id') == $centro->id)>
                                                            {{ $centro->nome }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Forma Pagamento</label>
                                                <select name="forma_pagamento" class="form-select">
                                                    <option value="">Todas</option>
                                                    <option value="pix" @selected(request('forma_pagamento') == 'pix')>Pix</option>
                                                    <option value="dinheiro" @selected(request('forma_pagamento') == 'dinheiro')>Dinheiro</option>
                                                    <option value="transferencia" @selected(request('forma_pagamento') == 'transferencia')>Transferência</option>
                                                    <option value="cartao" @selected(request('forma_pagamento') == 'cartao')>Cartão</option>
                                                    <option value="outros" @selected(request('forma_pagamento') == 'outros')>Outros</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                                <select name="status_pagamento" class="form-select">
                                                    <option value="">Todos</option>
                                                    <option value="pendente" @selected(request('status_pagamento') == 'pendente')>Pendente</option>
                                                    <option value="pago" @selected(request('status_pagamento') == 'pago')>Pago</option>
                                                    <option value="reembolsado" @selected(request('status_pagamento') == 'reembolsado')>Reembolsado</option>
                                                    <option value="aguardando_nf" @selected(request('status_pagamento') == 'aguardando_nf')>Aguardando NF</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Responsável</label>
                                                <select name="responsavel_id" class="form-select">
                                                    <option value="">Todos</option>
                                                    @foreach($usuarios as $u)
                                                        <option value="{{ $u->id }}" @selected(request('responsavel_id') == $u->id)>
                                                            {{ $u->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Fornecedor</label>
                                                <input type="text" name="fornecedor" value="{{ request('fornecedor') }}" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Valor mín</label>
                                                <input type="number" step="0.01" name="valor_min"
                                                       value="{{ request('valor_min') }}" class="form-control">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Valor máx</label>
                                                <input type="number" step="0.01" name="valor_max"
                                                       value="{{ request('valor_max') }}" class="form-control">
                                            </div>

                                            <div class="col-md-3 d-flex align-items-end">
                                                <a href="{{ route('admin.financeiro.despesas.index') }}"
                                                   class="btn btn-outline-secondary w-100">
                                                    Limpar filtros
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                        {{-- Seta animada --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const collapse = document.getElementById('filtrosAvancados');
                                const icon = document.getElementById('iconeToggle');
                                const texto = document.getElementById('textoToggle');

                                function atualizarIcone() {
                                    if (collapse.classList.contains('show')) {
                                        texto.innerText = "Ocultar filtros avançados";
                                        icon.className = "bi bi-chevron-up";
                                    } else {
                                        texto.innerText = "Mostrar filtros avançados";
                                        icon.className = "bi bi-chevron-down";
                                    }
                                }

                                collapse.addEventListener('shown.bs.collapse', atualizarIcone);
                                collapse.addEventListener('hidden.bs.collapse', atualizarIcone);

                                atualizarIcone();
                            });
                        </script>

                    </div>
                </div>

                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Categoria</th>
                        <th>Centro de Custo</th>
                        <th class="text-end">Valor</th>
                        <th>Lançado por</th>
                        <th class="text-end">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($despesas as $despesa)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($despesa->data)->format('d/m/Y') }}</td>
                            <td>{{ $despesa->categoria?->nome }}</td>
                            <td>{{ $despesa->centroCusto?->nome ?? '-' }}</td>
                            <td class="text-end">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                            <td>{{ $despesa->user?->name ?? '-' }}</td>

                            <td class="text-end">
                                <a href="{{ route('admin.financeiro.despesas.edit', $despesa) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Editar
                                </a>
                                <form action="{{ route('admin.financeiro.despesas.destroy', $despesa) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir esta despesa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Excluir
                                    </button>
                                </form>
                                @if($despesa->arquivo)
                                    <a href="{{ asset('storage/'.$despesa->arquivo) }}" target="_blank"
                                       class="btn btn-sm btn-outline-secondary">
                                        Comprovante
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{ $despesas->links() }}
    @endif
@endsection
