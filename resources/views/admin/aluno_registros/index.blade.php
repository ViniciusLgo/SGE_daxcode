@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                 Registros de Alunos
            </h1>
            <p class="text-sm text-slate-500">
                Gerencie atestados, declaracoes e ocorrencias dos alunos.
            </p>
        </div>

        <a href="{{ route('admin.aluno_registros.create') }}"
           class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
             Novo Registro
        </a>
    </div>

    @include('partials.alerts')

    {{-- ================= FILTROS ================= --}}
    <form method="GET"
          class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">

        <input type="text" name="tipo"
               value="{{ request('tipo') }}"
               placeholder="Tipo (Atestado, Declaracao...)"
               class="rounded-xl border border-slate-300 dark:border-slate-700
                      px-4 py-2.5 text-sm
                      bg-white dark:bg-dax-dark/60
                      text-dax-dark dark:text-dax-light">

        <input type="text" name="categoria"
               value="{{ request('categoria') }}"
               placeholder="Categoria"
               class="rounded-xl border border-slate-300 dark:border-slate-700
                      px-4 py-2.5 text-sm
                      bg-white dark:bg-dax-dark/60
                      text-dax-dark dark:text-dax-light">

        <select name="status"
                class="rounded-xl border border-slate-300 dark:border-slate-700
                       px-4 py-2.5 text-sm
                       bg-white dark:bg-dax-dark/60
                       text-dax-dark dark:text-dax-light">
            <option value="">Status</option>
            @foreach(['pendente','validado','arquivado','expirado'] as $s)
                <option value="{{ $s }}" @selected(request('status') == $s)>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>

        <select name="turma_id"
                class="rounded-xl border border-slate-300 dark:border-slate-700
                       px-4 py-2.5 text-sm
                       bg-white dark:bg-dax-dark/60
                       text-dax-dark dark:text-dax-light">
            <option value="">Turma</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}" @selected(request('turma_id') == $turma->id)>
                    {{ $turma->nome }}
                </option>
            @endforeach
        </select>

        <div class="sm:col-span-2 lg:col-span-1">
            <button type="submit"
                    class="w-full px-4 py-2.5 rounded-xl border
                           border-slate-300 dark:border-slate-700
                           font-semibold text-sm
                           hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                 Filtrar
            </button>
        </div>
    </form>

    {{-- ================= TABELA ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 overflow-x-auto">

        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-dax-dark">
            <tr class="text-left text-slate-600 dark:text-slate-300">
                <th class="px-6 py-3">Aluno</th>
                <th class="px-6 py-3">Tipo</th>
                <th class="px-6 py-3">Categoria</th>
                <th class="px-6 py-3">Data</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Turma</th>
                <th class="px-6 py-3 text-right">Acoes</th>
            </tr>
            </thead>

            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($registros as $r)
                <tr class="hover:bg-slate-50 dark:hover:bg-dax-dark/80 transition">

                    <td class="px-6 py-3 font-medium">
                        {{ $r->aluno->user->name ?? 'Nao informado' }}
                    </td>

                    <td class="px-6 py-3">{{ $r->tipo }}</td>

                    <td class="px-6 py-3">{{ $r->categoria ?? '' }}</td>

                    <td class="px-6 py-3">
                        {{ $r->data_evento
                            ? \Carbon\Carbon::parse($r->data_evento)->format('d/m/Y')
                            : ''
                        }}
                    </td>

                    <td class="px-6 py-3">
                        @php
                            $badge = match($r->status) {
                                'pendente'  => 'bg-yellow-100 text-yellow-800',
                                'validado'  => 'bg-green-100 text-green-800',
                                'arquivado' => 'bg-slate-200 text-slate-700',
                                default     => 'bg-red-100 text-red-800',
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>

                    <td class="px-6 py-3">
                        {{ $r->turma->nome ?? 'Sem turma' }}
                    </td>

                    <td class="px-6 py-3 text-right space-x-3">
                        <a href="{{ route('admin.aluno_registros.show', $r->id) }}"
                           class="text-blue-600 font-semibold hover:underline text-sm">
                            Ver
                        </a>

                        <a href="{{ route('admin.aluno_registros.edit', $r->id) }}"
                           class="text-dax-green font-semibold hover:underline text-sm">
                            Editar
                        </a>

                        <form action="{{ route('admin.aluno_registros.destroy', $r->id) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Excluir este registro?')"
                                    class="text-red-600 font-semibold hover:underline text-sm">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                        Nenhum registro encontrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- ================= PAGINACAO ================= --}}
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $registros->withQueryString()->links() }}
        </div>

    </div>

@endsection
