@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Secretaria Escolar
            </h1>
            <p class="text-sm text-slate-500">
                Controle operacional da Secretaria.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.secretaria.atendimentos.create') }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white text-sm font-semibold hover:bg-dax-greenSoft transition">
                ➕ Novo Atendimento
            </a>
        </div>
    </div>

    {{-- ================= ALERTA ATENDIMENTOS PENDENTES ================= --}}
    @if($atendimentosPendentes > 0)
        <div class="mb-6 rounded-2xl border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-800 p-4">
            <div class="flex items-start gap-3">
                <div class="text-xl">⚠️</div>
                <div>
                    <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                        Atendimentos pendentes
                    </p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400">
                        Existem <strong>{{ $atendimentosPendentes }}</strong> atendimentos aguardando resolução.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= KPIs ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
            <span class="text-sm text-slate-500">Alunos Registrados</span>
            <div class="flex justify-between items-center mt-2">
                <div class="text-3xl font-black text-dax-dark dark:text-dax-light">
                    {{ $totalAlunos }}
                </div>
                <div class="text-3xl">🎓</div>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
            <span class="text-sm text-slate-500">Turmas Ativas</span>
            <div class="flex justify-between items-center mt-2">
                <div class="text-3xl font-black text-dax-dark dark:text-dax-light">
                    {{ $totalTurmas }}
                </div>
                <div class="text-3xl">📚</div>
            </div>
        </div>

        <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-5">
            <span class="text-sm text-slate-500">Pendências Documentais</span>
            <div class="flex justify-between items-center mt-2">
                <div class="text-3xl font-black text-red-600">
                    {{ $pendencias }}
                </div>
                <div class="text-3xl">📄</div>
            </div>
        </div>

        <a href="{{ route('admin.secretaria.atendimentos.index') }}"
           class="rounded-2xl bg-dax-green/10 border border-dax-green p-5 hover:ring-2 hover:ring-dax-green transition">
            <span class="text-sm text-dax-green font-semibold">
                Atendimentos Recentes
            </span>
            <div class="flex justify-between items-center mt-2">
                <div class="text-3xl font-black text-dax-dark dark:text-dax-light">
                    {{ count($atendimentosRecentes) }}
                </div>
                <div class="text-3xl">💬</div>
            </div>
        </a>

    </div>

    {{-- ================= ATENDIMENTOS RECENTES ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h2 class="font-semibold text-dax-dark dark:text-dax-light">
                📌 Atendimentos Recentes
            </h2>

            <a href="{{ route('admin.secretaria.atendimentos.index') }}"
               class="text-sm font-semibold text-dax-green hover:underline">
                Ver todos
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-dax-dark">
                <tr class="text-left text-slate-600 dark:text-slate-300">
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Aluno</th>
                    <th class="px-6 py-3">Data</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Ação</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($atendimentosRecentes as $a)
                    <tr>
                        <td class="px-6 py-3">{{ $a->tipo }}</td>
                        <td class="px-6 py-3">{{ $a->aluno->user->name ?? '—' }}</td>
                        <td class="px-6 py-3">{{ $a->data_atendimento->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">
                            @if($a->status === 'concluido')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Concluído
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendente
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('admin.secretaria.atendimentos.show', $a) }}"
                               class="text-dax-green font-semibold hover:underline text-sm">
                                Abrir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-slate-500">
                            Nenhum atendimento recente.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
