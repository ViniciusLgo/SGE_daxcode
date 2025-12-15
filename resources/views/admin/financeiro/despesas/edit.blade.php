@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Editar Despesa</h4>
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
            <form action="{{ route('admin.financeiro.despesas.update', $despesa) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- DATA --}}
                    <div class="col-md-4">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" name="data" class="form-control"
                               value="{{ old('data', $despesa->data) }}" required>
                    </div>

                    {{-- CAT --}}
                    <div class="col-md-4">
                        <label class="form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" @selected(old('categoria_id', $despesa->categoria_id) == $cat->id)>
                                    {{ $cat->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CENTRO --}}
                    <div class="col-md-4">
                        <label class="form-label">Centro de Custo</label>
                        <select name="centro_custo_id" class="form-select">
                            <option value="">Opcional</option>
                            @foreach($centros as $centro)
                                <option value="{{ $centro->id }}" @selected(old('centro_custo_id', $despesa->centro_custo_id) == $centro->id)>
                                    {{ $centro->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- VALOR --}}
                    <div class="col-md-4">
                        <label class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="valor" class="form-control"
                               value="{{ old('valor', $despesa->valor) }}" required>
                    </div>

                    {{-- DESC --}}
                    <div class="col-md-8">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control"
                               value="{{ old('descricao', $despesa->descricao) }}">
                    </div>

                    {{-- NOVOS CAMPOS --}}

                    <div class="col-md-6">
                        <label class="form-label">Fornecedor</label>
                        <input type="text" name="fornecedor" class="form-control"
                               value="{{ old('fornecedor', $despesa->fornecedor) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Forma de Pagamento</label>
                        <select name="forma_pagamento" class="form-select">
                            <option value="">Selecione...</option>
                            <option value="pix" @selected(old('forma_pagamento', $despesa->forma_pagamento) == 'pix')>Pix</option>
                            <option value="dinheiro" @selected(old('forma_pagamento', $despesa->forma_pagamento) == 'dinheiro')>Dinheiro</option>
                            <option value="transferencia" @selected(old('forma_pagamento', $despesa->forma_pagamento) == 'transferencia')>Transferência</option>
                            <option value="cartao" @selected(old('forma_pagamento', $despesa->forma_pagamento) == 'cartao')>Cartão</option>
                            <option value="outros" @selected(old('forma_pagamento', $despesa->forma_pagamento) == 'outros')>Outros</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status_pagamento" class="form-select">
                            <option value="pendente" @selected(old('status_pagamento', $despesa->status_pagamento) == 'pendente')>Pendente</option>
                            <option value="pago" @selected(old('status_pagamento', $despesa->status_pagamento) == 'pago')>Pago</option>
                            <option value="reembolsado" @selected(old('status_pagamento', $despesa->status_pagamento) == 'reembolsado')>Reembolsado</option>
                            <option value="aguardando_nf" @selected(old('status_pagamento', $despesa->status_pagamento) == 'aguardando_nf')>Aguardando NF</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Número NF</label>
                        <input type="text" name="numero_nf" class="form-control"
                               value="{{ old('numero_nf', $despesa->numero_nf) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Conta / Fundo</label>
                        <input type="text" name="conta" class="form-control"
                               value="{{ old('conta', $despesa->conta) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Responsável</label>
                        <select name="responsavel_id" class="form-select">
                            <option value="">Selecione...</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" @selected(old('responsavel_id', $despesa->responsavel_id) == $u->id)>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ARQUIVO --}}
                    <div class="col-md-6">
                        <label class="form-label">Comprovante</label>
                        <input type="file" name="arquivo" class="form-control">

                        @if($despesa->arquivo)
                            <small class="d-block mt-1">
                                Arquivo atual:
                                <a href="{{ asset('storage/'.$despesa->arquivo) }}" target="_blank">
                                    Ver comprovante
                                </a>
                            </small>
                        @endif
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Atualizar Despesa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
