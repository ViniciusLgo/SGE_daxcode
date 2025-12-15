@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Nova Despesa</h4>
        <a href="{{ route('admin.financeiro.despesas.index') }}" class="btn btn-outline-secondary">
            Voltar
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ops!</strong> Verifique os campos abaixo.<br><br>
            <ul class="mb-0">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.financeiro.despesas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- DATA --}}
                    <div class="col-md-4">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" name="data" class="form-control"
                               value="{{ old('data', date('Y-m-d')) }}" required>
                    </div>

                    {{-- CATEGORIA --}}
                    <div class="col-md-4">
                        <label class="form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" @selected(old('categoria_id') == $cat->id)>
                                    {{ $cat->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CENTRO DE CUSTO --}}
                    <div class="col-md-4">
                        <label class="form-label">Centro de Custo</label>
                        <select name="centro_custo_id" class="form-select">
                            <option value="">Opcional</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id }}" @selected(old('centro_custo_id') == $centro->id)>
                                    {{ $centro->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- VALOR --}}
                    <div class="col-md-4">
                        <label class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="valor"
                               class="form-control" value="{{ old('valor') }}" required>
                    </div>

                    {{-- DESCRIÇÃO --}}
                    <div class="col-md-8">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control"
                               value="{{ old('descricao') }}"
                               placeholder="Ex: Compra de material escolar, transporte dos alunos, lanche, etc.">
                    </div>

                    {{-- ========================= --}}
                    {{-- NOVOS CAMPOS FINANCEIROS --}}
                    {{-- ========================= --}}

                    {{-- FORNECEDOR --}}
                    <div class="col-md-6">
                        <label class="form-label">Fornecedor</label>
                        <input type="text" name="fornecedor" class="form-control"
                               value="{{ old('fornecedor') }}"
                               placeholder="Nome da empresa ou pessoa física">
                    </div>

                    {{-- FORMA DE PAGAMENTO --}}
                    <div class="col-md-3">
                        <label class="form-label">Forma de Pagamento</label>
                        <select name="forma_pagamento" class="form-select">
                            <option value="">Selecione...</option>
                            <option value="pix" @selected(old('forma_pagamento') == 'pix')>Pix</option>
                            <option value="dinheiro" @selected(old('forma_pagamento') == 'dinheiro')>Dinheiro</option>
                            <option value="transferencia" @selected(old('forma_pagamento') == 'transferencia')>Transferência</option>
                            <option value="cartao" @selected(old('forma_pagamento') == 'cartao')>Cartão</option>
                            <option value="outros" @selected(old('forma_pagamento') == 'outros')>Outros</option>
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-3">
                        <label class="form-label">Status do Pagamento</label>
                        <select name="status_pagamento" class="form-select">
                            <option value="pendente" @selected(old('status_pagamento') == 'pendente')>Pendente</option>
                            <option value="pago" @selected(old('status_pagamento') == 'pago')>Pago</option>
                            <option value="reembolsado" @selected(old('status_pagamento') == 'reembolsado')>Reembolsado</option>
                            <option value="aguardando_nf" @selected(old('status_pagamento') == 'aguardando_nf')>Aguardando Nota Fiscal</option>
                        </select>
                    </div>

                    {{-- NÚMERO NF --}}
                    <div class="col-md-4">
                        <label class="form-label">Número da Nota Fiscal</label>
                        <input type="text" name="numero_nf" class="form-control"
                               value="{{ old('numero_nf') }}" placeholder="Ex: 12345-ABC">
                    </div>

                    {{-- CONTA / FUNDO --}}
                    <div class="col-md-4">
                        <label class="form-label">Conta / Fundo</label>
                        <input type="text" name="conta" class="form-control"
                               value="{{ old('conta') }}" placeholder="Ex: Fundo Educacional">
                    </div>

                    {{-- RESPONSÁVEL --}}
                    <div class="col-md-4">
                        <label class="form-label">Responsável pela Compra</label>
                        <select name="responsavel_id" class="form-select">
                            <option value="">Selecione...</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" @selected(old('responsavel_id') == $u->id)>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- COMPROVANTE --}}
                    <div class="col-md-6">
                        <label class="form-label">Comprovante (nota, recibo, foto)</label>
                        <input type="file" name="arquivo" class="form-control">
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Salvar Despesa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
