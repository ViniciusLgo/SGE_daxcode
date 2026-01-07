@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Painel do Professor
            </h1>
            <p class="text-sm text-slate-500">
                Bem-vindo, {{ auth()->user()->name }}
            </p>
        </div>
    </div>

    {{-- ================= KPIs ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- ================= DISCIPLINAS ================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="flex items-start justify-between">
                <span class="text-3xl"></span>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    DISCIPLINAS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $disciplinas ?? 0 }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Disciplinas atribuidas
                </p>
            </div>
        </div>

        {{-- ================= TURMAS ================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="flex items-start justify-between">
                <span class="text-3xl"></span>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    TURMAS
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $turmas ?? 0 }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Turmas vinculadas
                </p>
            </div>
        </div>

        {{-- ================= AULAS HOJE ================= --}}
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">

            <div class="flex items-start justify-between">
                <span class="text-3xl"></span>
            </div>

            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    AULAS HOJE
                </p>

                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $aulasHoje ?? 0 }}
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Atividades programadas
                </p>
            </div>
        </div>

    </div>

    {{-- ================= PROXIMAS AULAS ================= --}}
    <div class="mt-6 rounded-2xl bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-black text-dax-dark dark:text-dax-light">
                Proximas aulas
            </h2>
            <a href="{{ route('professor.aulas.index') }}"
               class="text-sm font-semibold text-dax-green hover:underline">
                Ver aulas
            </a>
        </div>

        @if(!empty($proximasAulas) && $proximasAulas->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-slate-500">
                    <tr>
                        <th class="py-2 text-left">Data</th>
                        <th class="py-2 text-left">Turma</th>
                        <th class="py-2 text-left">Disciplina</th>
                        <th class="py-2 text-left">Horario</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proximasAulas as $aula)
                        <tr class="border-t border-slate-200 dark:border-slate-800">
                            <td class="py-2">{{ $aula->data?->format('d/m/Y') }}</td>
                            <td class="py-2">{{ $aula->turma->nome ?? '-' }}</td>
                            <td class="py-2">{{ $aula->disciplina->nome ?? '-' }}</td>
                            <td class="py-2">{{ $aula->hora_inicio }} - {{ $aula->hora_fim }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-slate-500 text-sm">Nenhuma aula programada.</p>
        @endif
    </div>

    {{-- ================= ALERTAS ================= --}}
    <div class="mt-6 rounded-2xl bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800 p-6">
        <h2 class="font-black text-dax-dark dark:text-dax-light mb-4">
            Alertas
        </h2>
        <ul class="space-y-2 text-sm">
            <li>Aulas sem presenca: <strong>{{ $aulasSemPresenca ?? 0 }}</strong></li>
            <li>Presencas abertas: <strong>{{ $presencasAbertas ?? 0 }}</strong></li>
            <li>Avaliacoes abertas: <strong>{{ $avaliacoesAbertas ?? 0 }}</strong></li>
        </ul>
    </div>

@endsection
