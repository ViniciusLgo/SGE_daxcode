@extends('layouts.app')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                ➕ Nova Avaliação
            </h1>
            <p class="text-sm text-slate-500">
                Cadastro de avaliação por turma e disciplina.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              hover:bg-slate-100 dark:hover:bg-slate-800">
            ← Voltar
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6">

        <form method="POST"
              action="{{ route('admin.gestao_academica.avaliacoes.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                @foreach([
                    ['label'=>'Turma','name'=>'turma_id','type'=>'select','options'=>$turmas],
                    ['label'=>'Disciplina','name'=>'disciplina_id','type'=>'select','options'=>$disciplinas]
                ] as $field)
                    <div>
                        <label class="block text-sm font-semibold mb-1">{{ $field['label'] }}</label>
                        <select name="{{ $field['name'] }}" required
                                class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                            <option value="">Selecione</option>
                            @foreach($field['options'] as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Título</label>
                    <input type="text" name="titulo" required
                           placeholder="Ex: Prova Bimestral"
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Tipo</label>
                    <select name="tipo" required
                            class="w-full rounded-xl px-4 py-2.5
                   bg-white dark:bg-slate-900
                   text-dax-dark dark:text-dax-light
                   border border-slate-300 dark:border-slate-700">
                        <option value="prova">Prova</option>
                        <option value="trabalho">Trabalho</option>
                        <option value="atividade">Atividade</option>
                        <option value="recuperacao">Recuperação</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Data da Avaliação</label>
                    <input type="date" name="data_avaliacao" required
                           class="w-full rounded-xl px-4 py-2.5
                  bg-white dark:bg-slate-900
                  text-dax-dark dark:text-dax-light
                  border border-slate-300 dark:border-slate-700">
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
                   class="px-4 py-2 rounded-xl border">
                    Cancelar
                </a>
                <button class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
                    💾 Salvar Avaliação
                </button>
            </div>

        </form>
    </div>
@endsection
