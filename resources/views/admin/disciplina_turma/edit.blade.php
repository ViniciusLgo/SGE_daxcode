@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{ addDisciplina: false }">

        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Gerenciar Disciplinas — {{ $turma->nome }}
                </h1>
                <p class="text-slate-500">
                    Adicione disciplinas e vincule professores à turma.
                </p>
            </div>

            <a href="{{ route('admin.turmas.show', $turma->id) }}"
               class="px-4 py-2 rounded-xl border
                  border-slate-200 dark:border-slate-800">
                Voltar
            </a>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold">Disciplinas vinculadas</h2>

                <button @click="addDisciplina = true"
                        class="px-3 py-2 rounded-xl bg-dax-green text-white">
                    Adicionar Disciplina
                </button>
            </div>

            {{-- Lista --}}
            @forelse($turma->disciplinaTurmas as $vinculo)
                <div class="border rounded-xl p-4 mb-4 dark:border-slate-800"
                     x-data="{ addProfessor: false, removeDisciplina: false }">

                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold">{{ $vinculo->disciplina->nome }}</h3>
                            <p class="text-sm text-slate-500">
                                Ano letivo: {{ $vinculo->ano_letivo ?? '—' }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Observação: {{ $vinculo->observacao ?? '—' }}
                            </p>
                        </div>

                        <button @click="removeDisciplina = true"
                                class="text-red-600 hover:underline">
                            Remover
                        </button>
                    </div>

                    {{-- Professores --}}
                    <div class="mt-4">
                        <p class="font-medium mb-1">Professores</p>

                        @if($vinculo->professores->isEmpty())
                            <p class="text-sm text-slate-500">Nenhum professor vinculado.</p>
                        @else
                            <ul class="space-y-2 mb-3">
                                @foreach($vinculo->professores as $prof)
                                    <li class="flex justify-between items-center text-sm">
                                    <span>
                                        {{ $prof->user->name }}
                                        <span class="text-slate-500">
                                            ({{ $prof->user->email }})
                                        </span>
                                    </span>

                                        <form method="POST"
                                              action="{{ route('admin.turmas.disciplinas.professores.destroy', [$turma->id, $vinculo->id, $prof->id]) }}">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Remover
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <button @click="addProfessor = true"
                                class="text-sky-600 hover:underline text-sm">
                            + Adicionar Professor
                        </button>
                    </div>

                    {{-- Modal Remover Disciplina --}}
                    <div x-show="removeDisciplina" x-cloak
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <form method="POST"
                              action="{{ route('admin.turmas.disciplinas.destroy', [$turma->id, $vinculo->id]) }}"
                              class="bg-white dark:bg-dax-dark rounded-2xl p-6 w-full max-w-md">
                            @csrf @method('DELETE')

                            <h3 class="font-semibold mb-3">Confirmar Remoção</h3>
                            <p class="text-sm mb-4">
                                Remover a disciplina
                                <strong>{{ $vinculo->disciplina->nome }}</strong> da turma?
                            </p>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="removeDisciplina = false"
                                        class="px-4 py-2 border rounded-xl">
                                    Cancelar
                                </button>
                                <button class="px-4 py-2 bg-red-600 text-white rounded-xl">
                                    Remover
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Modal Adicionar Professor --}}
                    <div x-show="addProfessor" x-cloak
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <form method="POST"
                              action="{{ route('admin.turmas.disciplinas.professores.store', [$turma->id, $vinculo->id]) }}"
                              class="bg-white dark:bg-dax-dark rounded-2xl p-6 w-full max-w-md">
                            @csrf

                            <h3 class="font-semibold mb-4">Adicionar Professor</h3>

                            <select name="professor_id" required
                                    class="w-full rounded-xl border px-4 py-2.5 mb-4
                                       bg-white dark:bg-dax-dark/60
                                       border-slate-200 dark:border-slate-800">
                                <option value="">Selecione...</option>
                                @foreach($professores as $prof)
                                    <option value="{{ $prof->id }}">
                                        {{ $prof->user->name }} — {{ $prof->user->email }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="addProfessor = false"
                                        class="px-4 py-2 border rounded-xl">
                                    Cancelar
                                </button>
                                <button class="px-4 py-2 bg-dax-green text-white rounded-xl">
                                    Adicionar
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @empty
                <p class="text-center text-slate-500 py-6">
                    Nenhuma disciplina vinculada.
                </p>
            @endforelse
        </div>

        {{-- Modal Adicionar Disciplina --}}
        <div x-show="addDisciplina" x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <form method="POST"
                  action="{{ route('admin.turmas.disciplinas.store', $turma->id) }}"
                  class="bg-white dark:bg-dax-dark rounded-2xl p-6 w-full max-w-md">
                @csrf

                <h3 class="font-semibold mb-4">Adicionar Disciplina</h3>

                <select name="disciplina_id" required
                        class="w-full rounded-xl border px-4 py-2.5 mb-3
                           bg-white dark:bg-dax-dark/60
                           border-slate-200 dark:border-slate-800">
                    <option value="">Selecione...</option>
                    @foreach($todasDisciplinas as $d)
                        <option value="{{ $d->id }}">{{ $d->nome }}</option>
                    @endforeach
                </select>

                <input type="text" name="ano_letivo"
                       value="{{ date('Y') }}"
                       class="w-full rounded-xl border px-4 py-2.5 mb-3
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800"
                       placeholder="Ano letivo">

                <textarea name="observacao" rows="2"
                          class="w-full rounded-xl border px-4 py-2.5 mb-4
                             bg-white dark:bg-dax-dark/60
                             border-slate-200 dark:border-slate-800"
                          placeholder="Observação"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="addDisciplina = false"
                            class="px-4 py-2 border rounded-xl">
                        Cancelar
                    </button>
                    <button class="px-4 py-2 bg-dax-green text-white rounded-xl">
                        Adicionar
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
