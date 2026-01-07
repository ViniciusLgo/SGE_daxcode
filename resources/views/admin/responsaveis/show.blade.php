@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
                <i class="bi bi-person-vcard text-dax-yellow"></i>
                Detalhes do Responsavel
            </h1>
            <p class="text-slate-500">
                Informacoes completas e alunos vinculados.
            </p>
        </div>

        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6 space-y-6">

            {{-- Dados --}}
            <div>
                <h2 class="font-semibold mb-3 flex items-center gap-2">
                    <i class="bi bi-person-fill"></i>
                    Informacoes Pessoais
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-slate-500">Nome:</span> {{ $responsavel->user->name }}</div>
                    <div><span class="text-slate-500">E-mail:</span> {{ $responsavel->user->email }}</div>
                    <div><span class="text-slate-500">Telefone:</span> {{ $responsavel->telefone ?? '' }}</div>
                    <div><span class="text-slate-500">CPF:</span> {{ $responsavel->cpf ?? '' }}</div>
                    <div><span class="text-slate-500">Grau de Parentesco:</span> {{ $responsavel->grau_parentesco ?? '' }}</div>
                </div>
            </div>

            {{-- Alunos --}}
            <div>
                <h2 class="font-semibold mb-3 flex items-center gap-2">
                    <i class="bi bi-people-fill text-dax-green"></i>
                    Alunos Vinculados
                </h2>

                @if($responsavel->alunos->isEmpty())
                    <p class="text-slate-500">Nenhum aluno vinculado.</p>
                @else
                    <div class="space-y-2">
                        @foreach($responsavel->alunos as $aluno)
                            <div class="flex justify-between items-center
                                    rounded-xl border p-4
                                    border-slate-200 dark:border-slate-800">
                                <div>
                                    <strong>{{ $aluno->user->name }}</strong><br>
                                    <span class="text-slate-500 text-sm">
                                    Turma: {{ $aluno->turma->nome ?? 'Sem turma' }}
                                </span>
                                </div>

                                <a href="{{ route('admin.alunos.show', $aluno) }}"
                                   class="text-sky-600 hover:underline">
                                    Ver Aluno
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Acoes --}}
            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.responsaveis.edit', $responsavel) }}"
                   class="px-4 py-2 rounded-xl bg-dax-yellow text-dax-dark">
                    <i class="bi bi-pencil"></i> Editar
                </a>

                <a href="{{ route('admin.responsaveis.index') }}"
                   class="px-4 py-2 rounded-xl border
                      border-slate-200 dark:border-slate-800">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

        </div>
    </div>
@endsection
