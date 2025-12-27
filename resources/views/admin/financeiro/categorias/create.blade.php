@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                ➕ Nova Categoria de Despesa
            </h1>
            <p class="text-sm text-slate-500">
                Cadastre um novo tipo de gasto para o controle financeiro.
            </p>
        </div>

        <a href="{{ route('admin.financeiro.categorias.index') }}"
           class="px-4 py-2 rounded-xl border
                  border-slate-300 dark:border-slate-700
                  text-sm font-semibold
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
                bg-white dark:bg-dax-dark/60 p-6 max-w-3xl">

        <form action="{{ route('admin.financeiro.categorias.store') }}"
              method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-5">

                {{-- NOME --}}
                <div>
                    <label class="block text-sm font-semibold mb-1
                                  text-dax-dark dark:text-dax-light">
                        Nome da categoria <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nome"
                           required
                           value="{{ old('nome') }}"
                           placeholder="Ex: Alimentação, Transporte, Material Escolar"
                           class="w-full rounded-xl px-4 py-2.5
                                  bg-white dark:bg-slate-900
                                  text-dax-dark dark:text-dax-light
                                  placeholder-slate-400 dark:placeholder-slate-500
                                  border border-slate-300 dark:border-slate-700
                                  focus:ring-2 focus:ring-dax-green">
                </div>

                {{-- DESCRIÇÃO --}}
                <div>
                    <label class="block text-sm font-semibold mb-1
                                  text-dax-dark dark:text-dax-light">
                        Descrição (opcional)
                    </label>
                    <textarea name="descricao"
                              rows="3"
                              placeholder="Detalhes adicionais sobre a categoria"
                              class="w-full rounded-xl px-4 py-2.5
                                     bg-white dark:bg-slate-900
                                     text-dax-dark dark:text-dax-light
                                     placeholder-slate-400 dark:placeholder-slate-500
                                     border border-slate-300 dark:border-slate-700
                                     focus:ring-2 focus:ring-dax-green">{{ old('descricao') }}</textarea>
                </div>

            </div>

            {{-- ================= AÇÕES ================= --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.financeiro.categorias.index') }}"
                   class="px-4 py-2 rounded-xl border
                          border-slate-300 dark:border-slate-700
                          font-semibold">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-xl
                               bg-dax-green text-white font-semibold
                               hover:bg-dax-greenSoft transition">
                    💾 Salvar Categoria
                </button>
            </div>

        </form>
    </div>

@endsection
