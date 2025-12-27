@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                💰 Nova Despesa
            </h1>
            <p class="text-sm text-slate-500">
                Registro de despesas do projeto social.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.despesas.index') }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              text-dax-dark dark:text-dax-light
              hover:bg-slate-100 dark:hover:bg-slate-800">
            ← Voltar
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

        <form action="{{ route('admin.financeiro.despesas.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Data *</label>
                    <input type="date" name="data"
                           value="{{ old('data', date('Y-m-d')) }}" required
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- CATEGORIA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Categoria *</label>
                    <select name="categoria_id" required
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value=""
                                class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                            Selecione…
                        </option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                    @selected(old('categoria_id') == $cat->id)
                                    class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CENTRO DE CUSTO --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Centro de Custo</label>
                    <select name="centro_custo_id"
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value=""
                                class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                            Opcional
                        </option>
                        @foreach($centros as $centro)
                            <option value="{{ $centro->id }}"
                                    @selected(old('centro_custo_id') == $centro->id)
                                    class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                                {{ $centro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- VALOR --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Valor (R$) *</label>
                    <input type="number" step="0.01" name="valor" required
                           value="{{ old('valor') }}"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- DESCRIÇÃO --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Descrição</label>
                    <input type="text" name="descricao"
                           value="{{ old('descricao') }}"
                           placeholder="Ex: material escolar, transporte, lanche…"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  placeholder-slate-400 dark:placeholder-slate-500
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- FORNECEDOR --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Fornecedor</label>
                    <input type="text" name="fornecedor"
                           value="{{ old('fornecedor') }}"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- FORMA PAGAMENTO --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Forma de Pagamento</label>
                    <select name="forma_pagamento"
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value="" class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                            Selecione…
                        </option>
                        @foreach(['pix','dinheiro','transferencia','cartao','outros'] as $fp)
                            <option value="{{ $fp }}"
                                    @selected(old('forma_pagamento') == $fp)
                                    class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                                {{ ucfirst($fp) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Status do Pagamento</label>
                    <select name="status_pagamento"
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        @foreach(['pendente','pago','reembolsado','aguardando_nf'] as $st)
                            <option value="{{ $st }}"
                                    @selected(old('status_pagamento') == $st)
                                    class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                                {{ ucfirst(str_replace('_',' ', $st)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NF --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Nº Nota Fiscal</label>
                    <input type="text" name="numero_nf"
                           value="{{ old('numero_nf') }}"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- CONTA --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Conta / Fundo</label>
                    <input type="text" name="conta"
                           value="{{ old('conta') }}"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                {{-- RESPONSÁVEL --}}
                <div>
                    <label class="block text-sm font-semibold mb-1">Responsável</label>
                    <select name="responsavel_id"
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value="" class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                            Selecione…
                        </option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}"
                                    @selected(old('responsavel_id') == $u->id)
                                    class="bg-white dark:bg-slate-900 text-dax-dark dark:text-dax-light">
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- COMPROVANTE --}}
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold mb-1">Comprovante</label>
                    <input type="file" name="arquivo"
                           class="block w-full text-sm
                  text-dax-dark dark:text-dax-light">
                </div>

            </div>

            {{-- AÇÕES --}}
            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="px-6 py-2 rounded-xl bg-dax-green text-white font-semibold">
                    Salvar Despesa
                </button>
            </div>

        </form>
    </div>

@endsection
