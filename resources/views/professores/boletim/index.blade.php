@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Boletim por Turma
                </h1>
                <p class="text-sm text-slate-500">
                    Acesse o boletim consolidado das suas turmas.
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-white dark:bg-dax-dark/60 p-6">
            @if($turmas->isEmpty())
                <p class="text-slate-500">Nenhuma turma vinculada.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-500">
                        <tr>
                            <th class="py-2 text-left">Turma</th>
                            <th class="py-2 text-left">Alunos</th>
                            <th class="py-2 text-right">Acoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($turmas as $turma)
                            <tr class="border-t border-slate-200 dark:border-slate-800">
                                <td class="py-2 font-semibold">{{ $turma->nome }}</td>
                                <td class="py-2">{{ $turma->alunos_count }}</td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('professor.boletim.turma', $turma) }}"
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
    </div>
@endsection
