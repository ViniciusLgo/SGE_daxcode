@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    {{ $disciplina->nome }}
                </h1>
                <p class="text-slate-500">
                    Informacoes da disciplina e professores responsaveis.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.disciplinas.edit', $disciplina) }}"
                   class="px-4 py-2 rounded-xl bg-dax-green text-white">
                    Editar
                </a>
                <a href="{{ route('admin.disciplinas.index') }}"
                   class="px-4 py-2 rounded-xl border
                      border-slate-200 dark:border-slate-800">
                    Voltar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Dados da Disciplina --}}
            <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold mb-4">Dados da Disciplina</h2>

                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-slate-500">Nome</dt>
                    <dd>{{ $disciplina->nome }}</dd>

                    <dt class="text-slate-500">Carga Horaria</dt>
                    <dd>
                        {{ $disciplina->carga_horaria ? $disciplina->carga_horaria.' horas' : '' }}
                    </dd>

                    <dt class="text-slate-500">Criada em</dt>
                    <dd>{{ $disciplina->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="text-slate-500">Atualizada em</dt>
                    <dd>{{ $disciplina->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>

                <div class="mt-4">
                    <h3 class="font-medium">Descricao</h3>
                    <p class="text-slate-500 mt-1">
                        {{ $disciplina->descricao ?: 'Nenhuma descricao informada.' }}
                    </p>
                </div>
            </div>

            {{-- Professores --}}
            <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold mb-4">Professores Responsaveis</h2>

                @forelse($disciplina->professores as $professor)
                    <div class="mb-4 last:mb-0">
                        <p class="font-medium">{{ $professor->nome }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $professor->email }} 
                            {{ $professor->telefone ?? 'sem telefone' }}
                        </p>

                        @if($professor->especializacao)
                            <p class="text-xs italic text-slate-500">
                                {{ $professor->especializacao }}
                            </p>
                        @endif

                        <a href="{{ route('admin.professores.show', $professor) }}"
                           class="inline-block mt-2 text-sky-600 hover:underline text-sm">
                            Ver perfil
                        </a>
                    </div>
                @empty
                    <p class="text-slate-500">
                        Nenhum professor vinculado.
                    </p>
                @endforelse
            </div>

        </div>

    </div>
@endsection
