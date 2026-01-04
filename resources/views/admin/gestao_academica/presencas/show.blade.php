@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    📋 Presença da Aula
                </h1>
                <p class="text-sm text-slate-500">
                    Visualização consolidada da lista de presença
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.presencas.edit', $presenca) }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl
                      bg-amber-500 text-white font-semibold
                      hover:bg-amber-600 transition">
                    ✏️ Editar
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

        {{-- ================= DADOS DA AULA ================= --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                p-6">

            <h2 class="font-semibold text-lg mb-4">
                📘 Dados da Aula
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
                    <dt class="text-slate-500">Carga horária</dt>
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

        {{-- ================= LISTA DE ALUNOS ================= --}}
        <div class="rounded-2xl border
                bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800
                overflow-hidden">

            <h2 class="font-semibold text-lg px-6 pt-6">
                👥 Alunos
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
                    <th class="px-4 py-3 text-left">Observação</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($presenca->alunos as $item)
                    <tr>

                        {{-- Aluno --}}
                        <td class="px-4 py-3 font-semibold">
                            {{ $item->aluno->user->name }}
                        </td>

                        {{-- Blocos --}}
                        @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                            @php
                                $campo = 'bloco_'.$i;
                            @endphp
                            <td class="px-4 py-3 text-center">
                                @if($item->$campo)
                                    <span class="text-green-600 font-bold">✔</span>
                                @else
                                    <span class="text-red-500 font-bold">✖</span>
                                @endif
                            </td>
                        @endfor

                        {{-- Justificativa --}}
                        <td class="px-4 py-3">
                            {{ $item->justificativa?->nome ?? '—' }}
                        </td>

                        {{-- Observação --}}
                        <td class="px-4 py-3 text-slate-500">
                            {{ $item->observacao ?? '—' }}
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
