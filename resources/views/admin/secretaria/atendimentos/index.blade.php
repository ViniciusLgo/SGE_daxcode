@extends('layouts.app')

@section('content')

    @php
        // Pendências APENAS de atendimentos (status = pendente)
        $pendentes = $atendimentos->where('status', 'pendente')->count();
    @endphp

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex flex-col gap-4">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Atendimentos da Secretaria
                </h1>
                <p class="text-sm text-slate-500">
                    Histórico de solicitações administrativas registradas.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.secretaria.dashboard') }}"
                   class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                          text-dax-dark dark:text-dax-light hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                    ← Voltar
                </a>

                <a href="{{ route('admin.secretaria.atendimentos.create') }}"
                   class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
                    ➕ Novo Atendimento
                </a>
            </div>
        </div>

        {{-- ================= ALERTA DE PENDÊNCIAS ================= --}}
        @if($pendentes > 0)
            <div class="rounded-2xl border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-800 p-4">
                <div class="flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <p class="font-semibold text-yellow-800 dark:text-yellow-300">
                            Atendimentos pendentes
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            Existem <strong>{{ $pendentes }}</strong> atendimentos aguardando resolução.
                        </p>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- ================= LISTAGEM ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-dax-dark">
                <tr class="text-left text-slate-600 dark:text-slate-300">
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Aluno</th>
                    <th class="px-6 py-3">Responsável</th>
                    <th class="px-6 py-3">Data</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($atendimentos as $a)
                    <tr class="hover:bg-slate-50 dark:hover:bg-dax-dark/80 transition">

                        <td class="px-6 py-3 font-medium">
                            {{ $a->tipo }}
                        </td>

                        <td class="px-6 py-3">
                            {{ $a->aluno->user->name ?? '—' }}
                        </td>

                        <td class="px-6 py-3">
                            {{ $a->responsavel->user->name ?? '—' }}
                        </td>

                        <td class="px-6 py-3">
                            {{ $a->data_atendimento->format('d/m/Y') }}
                        </td>

                        <td class="px-6 py-3">
                            @if($a->status === 'concluido')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Concluído
                                </span>
                            @elseif($a->status === 'pendente')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pendente
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-200 text-slate-700">
                                    Cancelado
                                </span>
                            @endif
                        </td>

                        {{-- ================= AÇÕES ================= --}}
                        <td class="px-6 py-3 text-right space-x-3">

                            <a href="{{ route('admin.secretaria.atendimentos.show', $a) }}"
                               class="text-blue-600 font-semibold hover:underline text-sm">
                                Ver
                            </a>

                            <a href="{{ route('admin.secretaria.atendimentos.edit', $a) }}"
                               class="text-dax-green font-semibold hover:underline text-sm">
                                Editar
                            </a>

                            <form action="{{ route('admin.secretaria.atendimentos.destroy', $a) }}"
                                  method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Excluir atendimento?')"
                                        class="text-red-600 font-semibold hover:underline text-sm">
                                    Excluir
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-slate-500">
                            Nenhum atendimento registrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= PAGINAÇÃO ================= --}}
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $atendimentos->links() }}
        </div>

    </div>

@endsection
