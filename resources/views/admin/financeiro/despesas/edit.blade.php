@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                 Editar Despesa
            </h1>
            <p class="text-sm text-slate-500">
                Atualizacao dos dados da despesa financeira.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.despesas.index') }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              hover:bg-slate-100 dark:hover:bg-slate-800">
             Voltar
        </a>
    </div>

    {{-- ================= ERROS ================= --}}
    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-300 dark:border-red-700
                bg-red-50 dark:bg-red-900/30 p-4
                text-dax-dark dark:text-dax-light">
            <strong>Ops!</strong> Verifique os campos abaixo.
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= CARD ================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6">

        <form action="{{ route('admin.financeiro.despesas.update', $despesa) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Data <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="data" required
                           value="{{ old('data', $despesa->data) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- CATEGORIA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <select name="categoria_id" required
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                @selected(old('categoria_id', $despesa->categoria_id) == $cat->id)>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CENTRO DE CUSTO --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Centro de Custo
                    </label>
                    <select name="centro_custo_id"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        <option value="">Opcional</option>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}"
                                @selected(old('centro_custo_id', $despesa->centro_custo_id) == $centro->id)>
                                {{ $centro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- VALOR --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Valor (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="valor" required
                           value="{{ old('valor', $despesa->valor) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- DESCRICAO --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Descricao
                    </label>
                    <input type="text" name="descricao"
                           value="{{ old('descricao', $despesa->descricao) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- FORNECEDOR --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Fornecedor
                    </label>
                    <input type="text" name="fornecedor"
                           value="{{ old('fornecedor', $despesa->fornecedor) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- FORMA PAGAMENTO --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Forma de Pagamento
                    </label>
                    <select name="forma_pagamento"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        <option value="">Selecione...</option>
                        @foreach(['pix','dinheiro','transferencia','cartao','outros'] as $fp)
                            <option value="{{ $fp }}"
                                @selected(old('forma_pagamento', $despesa->forma_pagamento) == $fp)>
                                {{ ucfirst($fp) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Status do Pagamento
                    </label>
                    <select name="status_pagamento"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        @foreach(['pendente','pago','reembolsado','aguardando_nf'] as $st)
                            <option value="{{ $st }}"
                                @selected(old('status_pagamento', $despesa->status_pagamento) == $st)>
                                {{ ucfirst(str_replace('_',' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NF --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        No Nota Fiscal
                    </label>
                    <input type="text" name="numero_nf"
                           value="{{ old('numero_nf', $despesa->numero_nf) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- CONTA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Conta / Fundo
                    </label>
                    <input type="text" name="conta"
                           value="{{ old('conta', $despesa->conta) }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700">
                </div>

                {{-- RESPONSAVEL --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Responsavel
                    </label>
                    <select name="responsavel_id"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700">
                        <option value="">Selecione...</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}"
                                @selected(old('responsavel_id', $despesa->responsavel_id) == $u->id)>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- COMPROVANTE --}}
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Comprovante
                    </label>
                    <input type="file" name="arquivo"
                           class="block w-full text-sm
                      text-dax-dark dark:text-dax-light">

                    @if($despesa->arquivo)
                        <p class="mt-1 text-sm">
                            Arquivo atual:
                            <a href="{{ asset('storage/'.$despesa->arquivo) }}"
                               target="_blank"
                               class="text-blue-600 hover:underline">
                                Ver comprovante
                            </a>
                        </p>
                    @endif
                </div>

            </div>

            {{-- ACOES --}}
            <div class="flex justify-end mt-6 gap-3">
                <a href="{{ route('admin.financeiro.despesas.index') }}"
                   class="px-4 py-2 rounded-xl border">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-xl bg-dax-green text-white font-semibold">
                    Atualizar Despesa
                </button>
            </div>

        </form>
    </div>

@endsection
