@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- CABECALHO --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Indicadores e Relatorios
                </h1>
                <p class="text-slate-500">
                    Visao geral do sistema com filtros por turma e periodo.
                </p>
            </div>
        </div>

        {{-- FILTROS --}}
        <form method="GET"
              class="rounded-2xl bg-white dark:bg-dax-dark/60
                     border border-slate-200 dark:border-slate-800 p-4">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Turma</label>
                    <select name="turma_id"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                   bg-white dark:bg-slate-900 px-4 py-2.5">
                        <option value="">Todas</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}" {{ (string)$turmaId === (string)$t->id ? 'selected' : '' }}>
                                {{ $t->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Turno</label>
                    <select name="turno"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                   bg-white dark:bg-slate-900 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach(['manha','tarde','noite'] as $t)
                            <option value="{{ $t }}" {{ $turno === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Inicio</label>
                    <input type="date" name="inicio" value="{{ $inicio }}"
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                  bg-white dark:bg-slate-900 px-4 py-2.5">
                </div>

                <div>
                    <label class="text-sm font-semibold">Fim</label>
                    <input type="date" name="fim" value="{{ $fim }}"
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                  bg-white dark:bg-slate-900 px-4 py-2.5">
                </div>

                <div class="flex gap-3">
                    <button class="px-5 py-2.5 rounded-xl border border-dax-green
                                   text-dax-green font-bold hover:bg-dax-green hover:text-white transition">
                        <i class="bi bi-funnel"></i> Aplicar
                    </button>
                    @if(request()->query())
                        <a href="{{ route('admin.relatorios.index') }}"
                           class="px-4 py-2.5 text-slate-500 hover:underline">
                            Limpar
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- KPIs PRINCIPAIS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Alunos</div>
                <div class="text-2xl font-black">{{ $alunosTotal }}</div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Alunos ativos</div>
                <div class="text-2xl font-black">{{ $alunosAtivos }}</div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Desistentes</div>
                <div class="text-2xl font-black">{{ $matriculasDesistentes }}</div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Professores</div>
                <div class="text-2xl font-black">{{ $professoresTotal }}</div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Disciplinas</div>
                <div class="text-2xl font-black">{{ $disciplinasTotal }}</div>
            </div>
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-4">
                <div class="text-xs text-slate-500">Turmas</div>
                <div class="text-2xl font-black">{{ $turmasTotal }}</div>
            </div>
        </div>

        {{-- BLOCOS POR AREA --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ACADEMICO --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-6">
                <h2 class="text-lg font-black mb-4">Academico</h2>
                <ul class="text-sm space-y-2 mb-4">
                    <li>Alunos sem documentos: <strong>{{ $alunosSemDocumentos }}</strong></li>
                    <li>Documentos enviados: <strong>{{ $documentosEnviados }}</strong></li>
                </ul>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="{{ route('admin.relatorios.evasao.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Evasao
                    </a>
                    <a href="{{ route('admin.relatorios.carga_horaria_professores.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Carga Horaria
                    </a>
                    <a href="{{ route('admin.relatorios.horas.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Horas
                    </a>
                </div>
            </div>

            {{-- SECRETARIA --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-6">
                <h2 class="text-lg font-black mb-4">Secretaria</h2>
                <ul class="text-sm space-y-2 mb-4">
                    <li>Atendimentos pendentes: <strong>{{ $atendimentosPendentes }}</strong></li>
                </ul>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="{{ route('admin.secretaria.atendimentos.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Atendimentos
                    </a>
                    <a href="{{ route('admin.documentos.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Documentos
                    </a>
                </div>
            </div>

            {{-- FINANCEIRO --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-6">
                <h2 class="text-lg font-black mb-4">Financeiro</h2>
                <ul class="text-sm space-y-2 mb-4">
                    <li>Despesas no periodo: <strong>R$ {{ number_format($totalDespesasPeriodo, 2, ',', '.') }}</strong></li>
                </ul>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="{{ route('admin.financeiro.dashboard') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.financeiro.despesas.index') }}"
                       class="px-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        Despesas
                    </a>
                </div>
            </div>
        </div>

        {{-- LISTAS --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- TOP TURMAS --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-6">
                <h2 class="text-lg font-black mb-4">Top Turmas (mais alunos)</h2>
                <ul class="space-y-2 text-sm">
                    @forelse($topTurmas as $turma)
                        <li class="flex items-center justify-between">
                            <span class="font-semibold">{{ $turma->nome }}</span>
                            <span class="text-slate-500">{{ $turma->alunos_count }}</span>
                        </li>
                    @empty
                        <li class="text-slate-500">Nenhuma turma encontrada</li>
                    @endforelse
                </ul>
            </div>

            {{-- PROXIMOS ANIVERSARIOS --}}
            <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border p-6">
                <h2 class="text-lg font-black mb-4">Proximos Aniversarios</h2>
                <ul class="space-y-2 text-sm">
                    @forelse($proximosAniversarios as $aluno)
                        <li class="flex items-center justify-between">
                            <span class="font-semibold">{{ $aluno->user->name ?? '-' }}</span>
                            <span class="text-slate-500">{{ $aluno->proximo_aniversario->format('d/m') }}</span>
                        </li>
                    @empty
                        <li class="text-slate-500">Nenhum aniversario nos proximos dias</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
@endsection
