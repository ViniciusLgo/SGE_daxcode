@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                    <i class="bi bi-person-badge text-dax-yellow"></i>
                    {{ $professor->user->name }}
                </h1>
                <p class="text-slate-500">
                    Informações detalhadas do professor e suas disciplinas associadas.
                </p>
            </div>

            <a href="{{ route('admin.professores.index') }}"
               class="px-4 py-2 rounded-xl border
                  border-slate-200 dark:border-slate-800">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Dados do Professor --}}
            <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold mb-4 flex items-center gap-2">
                    <i class="bi bi-person-lines-fill"></i>
                    Dados do Professor
                </h2>

                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-slate-500">Nome</dt>
                    <dd>{{ $professor->user->name }}</dd>

                    <dt class="text-slate-500">E-mail</dt>
                    <dd>{{ $professor->user->email }}</dd>

                    <dt class="text-slate-500">Telefone</dt>
                    <dd>{{ $professor->telefone ?? '—' }}</dd>

                    <dt class="text-slate-500">Especialização</dt>
                    <dd>{{ $professor->especializacao ?? '—' }}</dd>

                    <dt class="text-slate-500">Criado em</dt>
                    <dd>{{ optional($professor->created_at)->format('d/m/Y H:i') ?? '—' }}</dd>
                </dl>
            </div>

            {{-- Disciplinas --}}
            <div class="lg:col-span-2 rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold flex items-center gap-2">
                        <i class="bi bi-book-half"></i>
                        Disciplinas ({{ $disciplinas->total() ?? $disciplinas->count() }})
                    </h2>

                    <a href="{{ route('admin.disciplinas.create') }}"
                       class="px-3 py-2 rounded-xl bg-dax-green text-white">
                        <i class="bi bi-plus-circle"></i> Nova Disciplina
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-slate-500">
                        <tr>
                            <th class="py-2">Nome</th>
                            <th class="py-2">Carga Horária</th>
                            <th class="py-2 text-right">Ações</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse($disciplinas as $disciplina)
                            <tr>
                                <td class="py-2">{{ $disciplina->nome }}</td>
                                <td class="py-2">
                                    {{ $disciplina->carga_horaria ? $disciplina->carga_horaria.'h' : '—' }}
                                </td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.disciplinas.show', $disciplina) }}"
                                       class="text-sky-600 hover:underline">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-500">
                                    Nenhuma disciplina associada a este professor.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($disciplinas instanceof \Illuminate\Pagination\LengthAwarePaginator && $disciplinas->hasPages())
                    <div class="pt-4">
                        {{ $disciplinas->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

