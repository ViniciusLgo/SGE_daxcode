@extends('layouts.app')

@section('content')
    @php
        $routePrefix = $routePrefix ?? 'admin';
        $isAluno = $isAluno ?? false;
    @endphp
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                     Detalhes da Aula
                </h1>
                <p class="text-sm text-slate-500">
                    Registro completo da atividade academica realizada
                    <span class="block text-xs mt-1">
                     Calculo baseado em <strong>hora-aula (50 minutos)</strong>
                </span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if(!$isAluno)
                    <a href="{{ route($routePrefix . '.aulas.edit', $aula) }}"
                       class="inline-flex items-center gap-2
                          px-4 py-2 rounded-xl
                          bg-amber-500 text-white font-semibold
                          hover:bg-amber-600 transition">
                         Editar
                    </a>
                @endif

                <a href="{{ route($routePrefix . '.aulas.index') }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Voltar
                </a>
            </div>
        </div>

        {{-- ================= CARD PRINCIPAL ================= --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                p-6 space-y-8">

            {{-- ================= DADOS GERAIS ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                     Dados Gerais
                </h2>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 text-sm">

                    <div>
                        <dt class="text-slate-500">Data</dt>
                        <dd class="font-semibold">
                            {{ $aula->data->format('d/m/Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Horario</dt>
                        <dd class="font-semibold">
                            {{ $aula->hora_inicio }}  {{ $aula->hora_fim }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Professor</dt>
                        <dd class="font-semibold">
                            {{ $aula->professor->user->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Turma</dt>
                        <dd class="font-semibold">
                            {{ $aula->turma->nome }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Disciplina</dt>
                        <dd class="font-semibold">
                            {{ $aula->disciplina->nome }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Tipo</dt>
                        <dd>
                        <span class="inline-flex items-center
                                     px-3 py-1 rounded-full
                                     text-xs font-semibold
                                     bg-slate-200 dark:bg-slate-700">
                            {{ ucfirst($aula->tipo) }}
                        </span>
                        </dd>
                    </div>

                    {{-- CARGA HORARIA DIDATICA --}}
                    <div>
                        <dt class="text-slate-500">Carga horaria</dt>
                        <dd class="font-bold">
                            {{ $aula->quantidade_blocos }} h/a
                            <span class="block text-xs font-normal text-slate-500">
                            ({{ $aula->quantidade_blocos }}  50 minutos)
                        </span>
                        </dd>
                    </div>

                </dl>
            </div>

            {{-- ================= CONTEUDO TRABALHADO ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-2">
                     Conteudo Trabalhado
                </h2>

                <div class="rounded-xl
                        bg-slate-50 dark:bg-slate-900/40
                        border border-slate-200 dark:border-slate-800
                        p-4 text-sm text-slate-600 dark:text-slate-300">
                    {{ $aula->conteudo ?: '' }}
                </div>
            </div>

            {{-- ================= OBSERVACOES ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-2">
                     Atividades / Observacoes
                </h2>

                <div class="rounded-xl
                        bg-slate-50 dark:bg-slate-900/40
                        border border-slate-200 dark:border-slate-800
                        p-4 text-sm text-slate-600 dark:text-slate-300">
                    {{ $aula->observacoes ?: '' }}
                </div>
            </div>

            {{-- ================= PRESENCA ================= --}}
            <div class="rounded-2xl border
            bg-white dark:bg-dax-dark/60
            border-slate-200 dark:border-slate-800
            p-6 space-y-4">

                <h2 class="font-semibold text-lg">
                     Presenca
                </h2>

                @php
                    $presenca = $aula->presenca;
                @endphp

                @if($presenca)
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600 dark:text-slate-300">
                            <strong>Status:</strong>
                            <span class="ml-1 inline-flex px-3 py-1 rounded-full text-xs font-semibold
                    {{ $presenca->status === 'finalizada'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($presenca->status) }}
                </span>
                        </div>

                        @if(!$isAluno)
                            <a href="{{ route($routePrefix . '.aulas.presenca.edit', $aula) }}"
                               class="inline-flex items-center gap-2
                          px-4 py-2 rounded-xl
                          bg-sky-600 text-white font-semibold
                          hover:bg-sky-700 transition">
                                 Editar Presenca
                            </a>
                        @endif
                    </div>
                @else
                    @if(!$isAluno)
                        <a href="{{ route($routePrefix . '.aulas.presenca.edit', $aula) }}"
                           class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl
                      bg-dax-green text-white font-semibold
                      hover:bg-dax-greenSoft transition">
                             Registrar Presenca
                        </a>
                    @else
                        <div class="text-sm text-slate-500">
                            Presenca ainda nao registrada.
                        </div>
                    @endif
                @endif
            </div>


        </div>
    </div>
@endsection
