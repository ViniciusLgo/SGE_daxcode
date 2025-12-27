@extends('layouts.app')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                📝 Resultados
            </h1>
            <p class="text-sm text-slate-500">
                {{ $avaliacao->titulo }} —
                {{ $avaliacao->disciplina->nome }}
                | Turma {{ $avaliacao->turma->nome }}
            </p>
        </div>

        <a href="{{ route('admin.gestao_academica.avaliacoes.index') }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              hover:bg-slate-100 dark:hover:bg-slate-800">
            ← Voltar
        </a>
    </div>

    {{-- CARD --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6">

        <form method="POST"
              action="{{ route('admin.gestao_academica.avaliacoes.resultados.store', $avaliacao) }}"
              enctype="multipart/form-data">
            @csrf

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 dark:bg-slate-900">
                    <tr class="text-slate-600 dark:text-slate-300">
                        <th class="p-3 text-left">Aluno</th>
                        <th class="p-3 text-center">Nota</th>
                        <th class="p-3">Arquivo</th>
                        <th class="p-3">Observação</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($alunos as $aluno)
                        @php $resultado = $resultados[$aluno->id] ?? null; @endphp

                        <tr class="border-t border-slate-200 dark:border-slate-800">
                            {{-- Aluno --}}
                            <td class="p-3 font-semibold text-dax-dark dark:text-dax-light">
                                {{ $aluno->user->name }}
                            </td>

                            {{-- Nota --}}
                            <td class="p-3 text-center">
                                <input type="number"
                                       name="resultados[{{ $aluno->id }}][nota]"
                                       min="0" max="10" step="0.01"
                                       value="{{ $resultado->nota ?? '' }}"
                                       {{ $somenteLeitura ? 'disabled' : '' }}
                                       class="w-20 text-center rounded-xl px-2 py-1
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      placeholder-slate-400 dark:placeholder-slate-500
                      border border-slate-300 dark:border-slate-700
                      focus:ring-2 focus:ring-dax-green">
                            </td>

                            {{-- Arquivo --}}
                            <td class="p-3 space-y-1">
                                @if($resultado?->arquivo)
                                    <a href="{{ asset('storage/'.$resultado->arquivo) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline">
                                        📄 Ver arquivo
                                    </a>
                                @endif

                                @unless($somenteLeitura)
                                    <label class="inline-flex items-center gap-2
                          px-3 py-2 rounded-xl cursor-pointer
                          bg-slate-200 dark:bg-slate-800
                          text-dax-dark dark:text-dax-light
                          border border-slate-300 dark:border-slate-700
                          hover:bg-slate-300 dark:hover:bg-slate-700">
                                        📎 Anexar
                                        <input type="file"
                                               name="resultados[{{ $aluno->id }}][arquivo]"
                                               class="hidden">
                                    </label>
                                @endunless
                            </td>

                            {{-- Observação --}}
                            <td class="p-3">
                                <input type="text"
                                       name="resultados[{{ $aluno->id }}][observacao]"
                                       value="{{ $resultado->observacao ?? '' }}"
                                       {{ $somenteLeitura ? 'disabled' : '' }}
                                       class="w-full rounded-xl px-3 py-2
                      bg-white dark:bg-slate-900
                      text-dax-dark dark:text-dax-light
                      placeholder-slate-400 dark:placeholder-slate-500
                      border border-slate-300 dark:border-slate-700
                      focus:ring-2 focus:ring-dax-green">
                            </td>

                            {{-- Status --}}
                            <td class="p-3 text-center">
                                @if($resultado?->entregue)
                                    <span class="text-emerald-500 font-semibold">
                Entregue ✓
            </span>
                                @else
                                    <span class="text-slate-400">
                Pendente
            </span>
                                @endif

                                @unless($somenteLeitura)
                                    <div class="mt-1">
                                        <input type="checkbox"
                                               name="resultados[{{ $aluno->id }}][entregue]"
                                               class="accent-dax-green"
                                            @checked($resultado?->entregue)>
                                    </div>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @unless($somenteLeitura)
                <div class="flex justify-end mt-6">
                    <button class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold">
                        💾 Salvar Resultados
                    </button>
                </div>
            @endunless

        </form>
    </div>
@endsection
