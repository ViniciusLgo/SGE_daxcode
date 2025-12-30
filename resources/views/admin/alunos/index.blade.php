@extends('layouts.app')

@section('content')

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex gap-2">
                <i class="bi bi-mortarboard"></i> Alunos
            </h1>
            <p class="text-slate-500 dark:text-slate-400">
                Gerencie os estudantes cadastrados no sistema.
            </p>
        </div>
    </div>

    {{-- BUSCA --}}
    <form method="GET"
          x-data="{ advanced: {{ request()->except('search','page') ? 'true' : 'false' }} }"
          class="mb-6 space-y-4">

        {{-- LINHA PRINCIPAL --}}
        <div class="flex flex-wrap gap-3 items-end">

            {{-- BUSCA SIMPLES --}}
            <div class="w-full md:w-80">
                <label class="text-sm font-semibold">Busca rápida</label>
                <input type="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Nome, matrícula ou e-mail"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                          bg-white dark:bg-slate-900 px-4 py-2.5">
            </div>

            {{-- STATUS --}}
            <div class="w-full md:w-56">
                <label class="text-sm font-semibold">Status</label>
                <select name="status"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                           bg-white dark:bg-slate-900 px-4 py-2.5">
                    <option value="">Todos</option>
                    @foreach(['ativo','desistente','transferido','trancado','concluido'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BOTÕES --}}
            <button class="px-5 py-2.5 rounded-xl border
                       border-dax-green text-dax-green font-bold
                       hover:bg-dax-green hover:text-white transition">
                <i class="bi bi-search"></i> Buscar
            </button>

            <button type="button"
                    @click="advanced = !advanced"
                    class="px-4 py-2.5 rounded-xl border
                       text-slate-600 dark:text-slate-300">
                <i class="bi bi-sliders"></i> Filtros avançados
            </button>

            <a href="{{ route('admin.relatorios.evasao.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
          border border-dax-green text-dax-green font-bold
          hover:bg-dax-green hover:text-white transition">
                <i class="bi bi-graph-up"></i>
                Relatório de Evasão
            </a>


        @if(request()->query())
                <a href="{{ route('admin.alunos.index') }}"
                   class="px-4 py-2.5 text-slate-500 hover:underline">
                    Limpar
                </a>
            @endif
        </div>

        {{-- PAINEL AVANÇADO --}}
        <div x-show="advanced" x-cloak
             class="rounded-2xl border border-slate-200 dark:border-slate-800
                bg-slate-50 dark:bg-dax-dark/60 p-4">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- TURMA --}}
                <div>
                    <label class="text-sm font-semibold">Turma</label>
                    <select name="turma_id"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-slate-900 px-4 py-2.5">
                        <option value="">Todas</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}"
                                {{ request('turma_id') == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ANO --}}
                <div>
                    <label class="text-sm font-semibold">Ano</label>
                    <input type="number"
                           name="ano"
                           value="{{ request('ano') }}"
                           placeholder="Ex: 2025"
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              bg-white dark:bg-slate-900 px-4 py-2.5">
                </div>

                {{-- TURNO --}}
                <div>
                    <label class="text-sm font-semibold">Turno</label>
                    <select name="turno"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-slate-900 px-4 py-2.5">
                        <option value="">Todos</option>
                        @foreach(['manhã','tarde','noite'] as $t)
                            <option value="{{ $t }}"
                                {{ request('turno') === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </form>




    {{-- TABELA --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900">
            <tr class="text-left text-slate-500">
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">Matrícula</th>
                <th class="px-4 py-3">E-mail</th>
                <th class="px-4 py-3">Turma</th>
                <th class="px-4 py-3 text-right">Ações</th>


            </tr>
            </thead>

            <tbody>
            @forelse($alunos as $aluno)
                @php
                    $status = $aluno->matriculaModel->status ?? 'ativo';

                    $statusMap = [
                        'ativo' => [
                            'label' => 'ATIVO',
                            'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                        ],
                        'desistente' => [
                            'label' => 'DESISTENTE',
                            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                        ],
                        'transferido' => [
                            'label' => 'TRANSFERIDO',
                            'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                        ],
                        'trancado' => [
                            'label' => 'TRANCADO',
                            'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'
                        ],
                        'concluido' => [
                            'label' => 'CONCLUÍDO',
                            'class' => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200'
                        ],
                    ];
                @endphp

                <tr class="border-t border-slate-200 dark:border-slate-800" x-data="{ open: false }">
                    <td class="px-4 py-3 font-semibold">
                        <div class="flex flex-col gap-1">
                            <span>{{ $aluno->user->name ?? '—' }}</span>

                            <span class="inline-flex w-fit px-2 py-0.5 rounded-full text-xs font-bold
                {{ $statusMap[$status]['class'] }}">
                {{ $statusMap[$status]['label'] }}
            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3">{{ $aluno->matricula ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $aluno->user->email ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $aluno->turma->nome ?? '—' }}</td>

                    <td class="px-4 py-3 text-right space-x-3">
                        <a href="{{ route('admin.alunos.show', $aluno) }}"
                           class="text-dax-blue hover:underline">Ver</a>

                        <a href="{{ route('admin.alunos.edit', $aluno) }}"
                           class="text-amber-500 hover:underline">Editar</a>

                        {{-- DESATIVAR / REATIVAR --}}
                        @if($status === 'ativo')
                            <button @click="open = true"
                                    class="text-red-600 hover:underline font-semibold">
                                Desativar
                            </button>
                        @elseif(in_array($status, ['desistente','trancado','transferido']))
                            <form method="POST"
                                  action="{{ route('admin.alunos.reativar', $aluno) }}"
                                  class="inline">
                                @csrf
                                <input type="hidden" name="motivo" value="Reativação administrativa">
                                <button class="text-green-600 hover:underline font-semibold">
                                    Reativar
                                </button>
                            </form>
                        @else
                            <span class="text-slate-400 italic">
        Ação indisponível
    </span>
                        @endif

                        <form action="{{ route('admin.alunos.destroy', $aluno) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Excluir este aluno?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline">
                                Excluir
                            </button>
                        </form>

                        {{-- MODAL DESISTÊNCIA --}}
                        <div x-show="open" x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center
                    bg-black/50 backdrop-blur-sm">

                            <div @click.outside="open = false"
                                 class="w-full max-w-lg rounded-2xl
                        bg-white dark:bg-dax-dark
                        border border-slate-200 dark:border-slate-800
                        shadow-xl p-6">

                                <h2 class="text-xl font-black mb-4">
                                    Desativar Matrícula
                                </h2>

                                <form method="POST"
                                      action="{{ route('admin.alunos.desistir', $aluno) }}"
                                      class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="text-sm font-semibold">
                                            Motivo <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               name="motivo"
                                               required
                                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                      bg-white dark:bg-slate-900 px-4 py-2.5">
                                    </div>

                                    <div>
                                        <label class="text-sm font-semibold">
                                            Observação
                                        </label>
                                        <textarea name="observacao"
                                                  rows="3"
                                                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                         bg-white dark:bg-slate-900 px-4 py-2.5"></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-4">
                                        <button type="button"
                                                @click="open = false"
                                                class="px-4 py-2 rounded-xl border">
                                            Cancelar
                                        </button>

                                        <button type="submit"
                                                class="px-5 py-2 rounded-xl
                                       bg-red-600 text-white font-bold
                                       hover:bg-red-700 transition">
                                            Confirmar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                        Nenhum aluno encontrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if($alunos->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $alunos->links() }}
            </div>
        @endif

    </div>

@endsection
