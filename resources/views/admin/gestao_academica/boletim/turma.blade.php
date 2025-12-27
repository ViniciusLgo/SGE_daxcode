@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                📘 Boletim da Turma
            </h1>
            <p class="text-sm text-slate-500">
                {{ $turma->nome }}
            </p>
        </div>

        <a href="{{ route('admin.turmas.show', $turma) }}"
           class="px-4 py-2 rounded-xl border
              border-slate-300 dark:border-slate-700
              hover:bg-slate-100 dark:hover:bg-slate-800">
            ← Voltar
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
            bg-white dark:bg-dax-dark/60 p-6">

        @if($boletins->isEmpty())
            <div class="text-center text-slate-500">
                Nenhum resultado lançado para esta turma ainda.
            </div>
        @else

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border
              border-slate-200 dark:border-slate-800">

                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                    <tr class="text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-2 text-left">Aluno</th>

                        @foreach($disciplinas as $disciplina)
                            <th class="px-4 py-2 text-center">{{ $disciplina->nome }}</th>
                        @endforeach

                        <th class="px-4 py-2 text-center">Média</th>
                        <th class="px-4 py-2 text-center">Situação</th>
                        <th class="px-4 py-2 text-center">Ações</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($boletins as $item)
                        @php
                            $medias = $item['disciplinas']->pluck('media');
                            $mediaGeral = $medias->count() ? round($medias->avg(), 2) : 0;

                            $situacao = match(true) {
                                $mediaGeral >= 6 => 'Aprovado',
                                $mediaGeral >= 4 => 'Recuperação',
                                default => 'Reprovado'
                            };

                            $situacaoClasses = match($situacao) {
                                'Aprovado' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'Recuperação' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                default => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            };
                        @endphp

                        <tr class="border-t border-slate-200 dark:border-slate-800">
                            <td class="px-4 py-2 font-semibold text-dax-dark dark:text-dax-light">
                                {{ $item['aluno']->user->name }}
                            </td>

                            @foreach($disciplinas as $disciplina)
                                <td class="px-4 py-2 text-center">
                                    {{ optional($item['disciplinas']->get($disciplina->id))['media'] ?? '-' }}
                                </td>
                            @endforeach

                            <td class="px-4 py-2 text-center font-black">
                                {{ number_format($mediaGeral, 2, ',', '.') }}
                            </td>

                            <td class="px-4 py-2 text-center">
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $situacaoClasses }}">
            {{ $situacao }}
        </span>
                            </td>

                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('admin.boletim.aluno', $item['aluno']) }}"
                                   class="text-blue-600 font-semibold hover:underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        @endif
    </div>

@endsection
