@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Painel do Aluno
            </h1>
            <p class="text-sm text-slate-500">
                Bem-vindo, {{ auth()->user()->name }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">
            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    DISCIPLINAS
                </p>
                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $disciplinas ?? 0 }}
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">
            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    AULAS HOJE
                </p>
                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $aulasHoje ?? 0 }}
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                    border border-slate-200 dark:border-slate-800
                    p-6 flex flex-col justify-between">
            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-500">
                    AVALIACOES ABERTAS
                </p>
                <div class="text-4xl font-black text-dax-green mt-1">
                    {{ $avaliacoesAbertas ?? 0 }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-black text-dax-dark dark:text-dax-light">
                Proximas aulas
            </h2>
            <a href="{{ route('aluno.aulas.index') }}"
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
                        <th class="py-2 text-left">Disciplina</th>
                        <th class="py-2 text-left">Horario</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proximasAulas as $aula)
                        <tr class="border-t border-slate-200 dark:border-slate-800">
                            <td class="py-2">{{ $aula->data?->format('d/m/Y') }}</td>
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
@endsection
