@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                ✏️ Editar Avaliação
            </h1>
            <p class="text-sm text-slate-500">
                Atualização dos dados da avaliação.
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              hover:bg-slate-100 dark:hover:bg-slate-800">
            ← Voltar
        </a>
    </div>

    {{-- ================= ALERTA ================= --}}
    @if($avaliacao->status === 'encerrada')
        <div class="mb-6 p-4 rounded-xl border
                border-yellow-300 dark:border-yellow-700
                bg-yellow-50 dark:bg-yellow-900/30
                text-dax-dark dark:text-dax-light">
            <div class="flex justify-between items-center">
                <div class="text-sm">
                    <strong>Avaliação encerrada.</strong>
                    Para editar ou lançar novos resultados, é necessário reabrir a avaliação.
                </div>

                <form method="POST"
                      action="{{ route('admin.gestao_academica.avaliacoes.reabrir', $avaliacao) }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold">
                        🔓 Reabrir
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- ================= CARD ================= --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6">

        <form method="POST"
              action="{{ route('admin.gestao_academica.avaliacoes.update', $avaliacao) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Turma --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Turma
                    </label>
                    <select name="turma_id" required
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700
                       focus:ring-2 focus:ring-dax-green">
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}"
                                @selected($avaliacao->turma_id == $turma->id)>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Disciplina --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Disciplina
                    </label>
                    <select name="disciplina_id" required
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700
                       focus:ring-2 focus:ring-dax-green">
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}"
                                @selected($avaliacao->disciplina_id == $disciplina->id)>
                                {{ $disciplina->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Título --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Título
                    </label>
                    <input type="text"
                           name="titulo"
                           value="{{ $avaliacao->titulo }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      placeholder-slate-400 dark:placeholder-slate-500
                      border border-slate-300 dark:border-slate-700
                      focus:ring-2 focus:ring-dax-green">
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Tipo
                    </label>
                    <select name="tipo"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700
                       focus:ring-2 focus:ring-dax-green">
                        @foreach(['prova','trabalho','atividade','recuperacao'] as $tipo)
                            <option value="{{ $tipo }}"
                                @selected($avaliacao->tipo === $tipo)>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data --}}
                <div>
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Data da Avaliação
                    </label>
                    <input type="date"
                           name="data_avaliacao"
                           value="{{ $avaliacao->data_avaliacao->format('Y-m-d') }}"
                           class="w-full rounded-xl px-4 py-2.5
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      border border-slate-300 dark:border-slate-700
                      focus:ring-2 focus:ring-dax-green">
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1 text-dax-dark dark:text-dax-light">
                        Status
                    </label>
                    <select name="status"
                            class="w-full rounded-xl px-4 py-2.5
                       bg-white dark:bg-slate-900
                       text-dax-dark dark:text-dax-light
                       border border-slate-300 dark:border-slate-700
                       focus:ring-2 focus:ring-dax-green">
                        <option value="aberta" @selected($avaliacao->status === 'aberta')>
                            Aberta
                        </option>
                        <option value="encerrada" @selected($avaliacao->status === 'encerrada')>
                            Encerrada
                        </option>
                    </select>
                </div>

            </div>

            {{-- AÇÕES --}}
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
                   class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700">
                    Cancelar
                </a>

                <button class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
                    💾 Atualizar Avaliação
                </button>
            </div>

        </form>

        <hr class="my-6 border-slate-200 dark:border-slate-800">

        {{-- EXCLUSÃO --}}
        <form method="POST"
              action="{{ route('admin.gestao_academica.avaliacoes.destroy', $avaliacao) }}"
              onsubmit="return confirm('Deseja excluir esta avaliação? Esta ação não pode ser desfeita.')">
            @csrf
            @method('DELETE')

            <button class="px-4 py-2 rounded-xl
               border border-red-500
               text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                🗑️ Excluir Avaliação
            </button>
        </form>

    </div>
@endsection
