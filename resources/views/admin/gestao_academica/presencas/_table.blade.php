<table class="min-w-full text-sm">
    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
    <tr>
        <th class="px-4 py-3 text-left">Data</th>
        <th class="px-4 py-3 text-left">Turma</th>
        <th class="px-4 py-3 text-left">Disciplina</th>
        <th class="px-4 py-3 text-left">Professor</th>
        <th class="px-4 py-3 text-center">h/a</th>
        <th class="px-4 py-3 text-center">Alunos</th>
        <th class="px-4 py-3 text-center">Status</th>
        <th class="px-4 py-3 text-right">Ações</th>
    </tr>
    </thead>

    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
    @forelse($presencas as $presenca)
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">

            {{-- Data --}}
            <td class="px-4 py-3 font-semibold">
                {{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}
            </td>

            {{-- Turma --}}
            <td class="px-4 py-3">
                {{ $presenca->turma->nome }}
            </td>

            {{-- Disciplina --}}
            <td class="px-4 py-3">
                {{ $presenca->disciplina->nome }}
            </td>

            {{-- Professor --}}
            <td class="px-4 py-3">
                {{ $presenca->professor->user->name }}
            </td>

            {{-- Hora-aula --}}
            <td class="px-4 py-3 text-center font-bold">
                {{ $presenca->quantidade_blocos }}
            </td>

            {{-- Total alunos --}}
            <td class="px-4 py-3 text-center">
                {{ $presenca->alunos->count() }}
            </td>

            {{-- Status --}}
            <td class="px-4 py-3 text-center">
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                    {{ $presenca->status === 'finalizada'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($presenca->status) }}
                </span>
            </td>

            {{-- Ações --}}
            <td class="px-4 py-3 text-right space-x-3 font-semibold">
                <a href="{{ route('admin.presencas.show', $presenca) }}"
                   class="text-sky-600 hover:underline">
                    Ver
                </a>

                <a href="{{ route('admin.presencas.edit', $presenca) }}"
                   class="text-amber-600 hover:underline">
                    Editar
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8"
                class="px-4 py-8 text-center text-slate-500">
                Nenhuma presença registrada até o momento.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
