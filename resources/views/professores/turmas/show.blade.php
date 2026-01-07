@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Turma {{ $turma->nome }}
                </h1>
                <p class="text-sm text-slate-500">
                    Visao geral da turma e disciplinas vinculadas a voce.
                </p>
            </div>
            <a href="{{ route('professor.turmas.index') }}"
               class="px-4 py-2 rounded-xl border
                      border-slate-200 dark:border-slate-800">
                Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 rounded-2xl bg-white dark:bg-dax-dark/60
                        border border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-black mb-4">Alunos</h2>
                @if($turma->alunos->isEmpty())
                    <p class="text-slate-500">Nenhum aluno nesta turma.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-slate-500">
                            <tr>
                                <th class="py-2 text-left">Aluno</th>
                                <th class="py-2 text-right">Boletim</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($turma->alunos as $aluno)
                                <tr class="border-t border-slate-200 dark:border-slate-800">
                                    <td class="py-2">{{ $aluno->user->name ?? '-' }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('professor.boletim.aluno', $aluno) }}"
                                           class="text-dax-green hover:underline">
                                            Ver boletim
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                        border border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-black mb-4">Disciplinas</h2>
                @if($disciplinas->isEmpty())
                    <p class="text-slate-500">Nenhuma disciplina vinculada.</p>
                @else
                    <ul class="space-y-2 text-sm">
                        @foreach($disciplinas as $vinculo)
                            <li class="flex items-center justify-between">
                                <span class="font-semibold">{{ $vinculo->disciplina->nome }}</span>
                                <span class="text-slate-500">{{ $vinculo->ano_letivo }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
