{{-- ============================================================================
| resources/views/admin/gestao_academica/presencas/show.blade.php
|
| VIEW: Visualizacao / Historico da Presenca
|
| FINALIDADE:
| - Exibir a presenca CONSOLIDADA de uma aula
| - Servir como registro historico oficial
|
| REGRAS DE NEGOCIO APLICADAS NESTA VIEW:
| 1) ALUNOS INATIVOS (desistentes, transferidos, etc.)
|    - NAO DEVEM SER EXIBIDOS
|    - Sao completamente ocultados (hidden logico)
|
| 2) JUSTIFICATIVAS
|    - Podem aparecer mesmo que hoje estejam inativas
|    - Isso preserva o historico fiel do que foi registrado
|
| 3) BLOCO DE PRESENCA
|    - Mostra  ou  conforme o valor salvo
|
| 4) VIEW SOMENTE LEITURA
|    - Nenhum campo editavel
|    - A edicao ocorre exclusivamente via edit.blade.php
============================================================================ --}}

@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ============================================================
            HEADER
        ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                     Presenca da Aula
                </h1>
                <p class="text-sm text-slate-500">
                    Visualizacao consolidada da lista de presenca
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.presencas.edit', $presenca) }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl
                      bg-amber-500 text-white font-semibold
                      hover:bg-amber-600 transition">
                     Editar
                </a>

                <a href="{{ route('admin.presencas.index') }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Voltar
                </a>
            </div>
        </div>

        {{-- ============================================================
            DADOS DA AULA
        ============================================================ --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                p-6">

            <h2 class="font-semibold text-lg mb-4">
                 Dados da Aula
            </h2>

            <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5 text-sm">

                <div>
                    <dt class="text-slate-500">Data</dt>
                    <dd class="font-semibold">
                        {{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Turma</dt>
                    <dd class="font-semibold">
                        {{ $presenca->turma->nome }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Disciplina</dt>
                    <dd class="font-semibold">
                        {{ $presenca->disciplina->nome }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Professor</dt>
                    <dd class="font-semibold">
                        {{ $presenca->professor->user->name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Carga horaria</dt>
                    <dd class="font-bold">
                        {{ $presenca->quantidade_blocos }} h/a
                    </dd>
                </div>

                <div>
                    <dt class="text-slate-500">Status</dt>
                    <dd>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                            {{ $presenca->status === 'finalizada'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($presenca->status) }}
                        </span>
                    </dd>
                </div>

            </dl>
        </div>

        {{-- ============================================================
            LISTA DE ALUNOS (APENAS ATIVOS)
            REGRA:
            - Aluno com matricula != ativo e OCULTADO (nao renderiza)
        ============================================================ --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                overflow-hidden">

            <h2 class="font-semibold text-lg px-6 pt-6">
                 Alunos
            </h2>

            <table class="min-w-full text-sm mt-4">
                <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Aluno</th>

                    @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                        <th class="px-4 py-3 text-center">
                            Bloco {{ $i }}
                        </th>
                    @endfor

                    <th class="px-4 py-3 text-left">Justificativa</th>
                    <th class="px-4 py-3 text-left">Observacao</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

                @php
                    /**
                     * Filtra APENAS alunos ativos.
                     * Tudo que nao for status === 'ativo' e ocultado.
                     */
                    $alunosAtivos = $presenca->alunos->filter(function ($item) {
                        return ($item->aluno->matriculaModel?->status ?? null) === 'ativo';
                    });
                @endphp

                @if($alunosAtivos->isEmpty())
                    <tr>
                        <td colspan="{{ 3 + (int)$presenca->quantidade_blocos }}"
                            class="px-4 py-8 text-center text-slate-500">
                            Nenhum aluno ativo encontrado nesta presenca.
                        </td>
                    </tr>
                @endif

                @foreach($alunosAtivos as $item)
                    <tr>

                        {{-- ====================================================
                            ALUNO
                        ==================================================== --}}
                        <td class="px-4 py-3 font-semibold">
                            {{ $item->aluno->user->name }}
                        </td>

                        {{-- ====================================================
                            BLOCOS ( / )
                        ==================================================== --}}
                        @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                            @php $campo = 'bloco_'.$i; @endphp
                            <td class="px-4 py-3 text-center">
                                @if($item->$campo)
                                    <span class="text-green-600 font-bold"></span>
                                @else
                                    <span class="text-red-500 font-bold"></span>
                                @endif
                            </td>
                        @endfor

                        {{-- ====================================================
                            JUSTIFICATIVA (historico)
                        ==================================================== --}}
                        <td class="px-4 py-3">
                            {{ $item->justificativa?->nome ?? '' }}
                        </td>

                        {{-- ====================================================
                            OBSERVACAO
                        ==================================================== --}}
                        <td class="px-4 py-3 text-slate-500">
                            {{ $item->observacao ?? '' }}
                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    </div>
@endsection
