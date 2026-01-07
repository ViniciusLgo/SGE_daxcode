{{-- ============================================================================
| resources/views/admin/gestao_academica/presencas/_table.blade.php
|
| COMPONENTE DE TABELA
|
| RESPONSABILIDADE:
| - Renderizar listagem de aulas com status de presenca
| - Usado em index, relatorios e dashboards
|
| REGRA IMPORTANTE:
| - CONTAGEM DE ALUNOS CONSIDERA APENAS MATRICULAS ATIVAS
============================================================================ --}}

<table class="min-w-full text-sm">
    @php
        $routePrefix = $routePrefix ?? 'admin';
        $isProfessor = $isProfessor ?? false;
        $isAluno = $isAluno ?? false;
    @endphp
    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
    <tr>
        <th class="px-4 py-3 text-left">Data</th>
        @if(!$isProfessor)
            <th class="px-4 py-3 text-left">Professor</th>
        @endif
        <th class="px-4 py-3 text-left">Turma</th>
        <th class="px-4 py-3 text-left">Disciplina</th>
        <th class="px-4 py-3 text-center">h/a</th>
        <th class="px-4 py-3 text-center">Status</th>
        <th class="px-4 py-3 text-right">Acoes</th>
    </tr>
    </thead>

    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">

    @forelse($aulas as $aula)
        @php
            $p = $aula->presenca;
            $status = $p?->status ?? 'sem_presenca';
        @endphp

        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
            <td class="px-4 py-3 font-semibold">
                {{ $aula->data->format('d/m/Y') }}
                <div class="text-xs text-slate-500">
                    {{ $aula->hora_inicio }}  {{ $aula->hora_fim }}
                </div>
            </td>

            @if(!$isProfessor)
                <td class="px-4 py-3">{{ $aula->professor->user->name }}</td>
            @endif
            <td class="px-4 py-3">{{ $aula->turma->nome }}</td>
            <td class="px-4 py-3">{{ $aula->disciplina->nome }}</td>

            <td class="px-4 py-3 text-center font-bold">
                {{ $aula->quantidade_blocos }}
            </td>

            <td class="px-4 py-3 text-center">
                @if($status === 'finalizada')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Finalizada</span>
                @elseif($status === 'aberta')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Aberta</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">Sem presenca</span>
                @endif
            </td>

            <td class="px-4 py-3 text-right space-x-3 font-semibold">
                <a href="{{ route($routePrefix . '.aulas.show', $aula) }}" class="text-sky-600 hover:underline">Ver aula</a>
                @if(!$isAluno)
                    <a href="{{ route($routePrefix . '.aulas.presenca.edit', $aula) }}" class="text-amber-600 hover:underline">Presenca</a>
                @endif

                @if($p)
                    <a href="{{ route($routePrefix . '.presencas.show', $p) }}" class="text-slate-600 hover:underline">Ver</a>
                @elseif($isAluno)
                    <span class="text-slate-400">Aguardando</span>
                @endif
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="{{ $isProfessor ? 6 : 7 }}" class="px-4 py-8 text-center text-slate-500">
                Nenhuma aula encontrada no periodo.
            </td>
        </tr>
    @endforelse

    </tbody>
</table>

@if($aulas->hasPages())
    <div class="p-4 border-t">
        {{ $aulas->links() }}
    </div>
@endif
