@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    ✏️ Editar Presença
                </h1>
                <p class="text-sm text-slate-500">
                    Edição completa da lista de presença da aula
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.presencas.show', $presenca) }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Voltar
                </a>
            </div>
        </div>

        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('admin.presencas.update', $presenca) }}"
              class="rounded-2xl
                 bg-white dark:bg-dax-dark/60
                 border border-slate-200 dark:border-slate-800
                 p-6 space-y-6">

            @csrf
            @method('PUT')

            {{-- ================= DADOS DA AULA ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                    📘 Dados da Aula
                </h2>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4 text-sm">

                    <div>
                        <dt class="text-slate-500">Data</dt>
                        <dd class="font-semibold">
                            {{ \Carbon\Carbon::parse($presenca->data)->format('d/m/Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Turma</dt>
                        <dd class="font-semibold">
                            {{ $presenca->turma->nome }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Disciplina</dt>
                        <dd class="font-semibold">
                            {{ $presenca->disciplina->nome }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Professor</dt>
                        <dd class="font-semibold">
                            {{ $presenca->professor->user->name }}
                        </dd>
                    </div>

                </dl>
            </div>

            {{-- ================= STATUS ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    Status da presença
                </label>
                <select name="status"
                        class="w-full md:w-64 rounded-xl border
                           border-slate-300 dark:border-slate-700
                           px-4 py-2.5
                           bg-white dark:bg-dax-dark">
                    <option value="aberta" {{ $presenca->status === 'aberta' ? 'selected' : '' }}>
                        Aberta
                    </option>
                    <option value="finalizada" {{ $presenca->status === 'finalizada' ? 'selected' : '' }}>
                        Finalizada
                    </option>
                </select>
            </div>

            {{-- ================= TABELA DE ALUNOS ================= --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Aluno</th>

                        @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                            <th class="px-4 py-3 text-center">
                                Bloco {{ $i }}
                            </th>
                        @endfor

                        <th class="px-4 py-3 text-left">Justificativa</th>
                        <th class="px-4 py-3 text-left">Observação</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($presenca->alunos as $item)
                        <tr>

                            {{-- Aluno --}}
                            <td class="px-4 py-3 font-semibold">
                                {{ $item->aluno->user->name }}
                            </td>

                            {{-- Blocos --}}
                            @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                                @php $campo = 'bloco_'.$i; @endphp
                                <td class="px-4 py-3 text-center">
                                    <input type="checkbox"
                                           name="presencas[{{ $item->aluno_id }}][{{ $campo }}]"
                                           value="1"
                                           {{ $item->$campo ? 'checked' : '' }}
                                           class="rounded border-slate-300 dark:border-slate-700">
                                </td>
                            @endfor

                            {{-- Justificativa --}}
                            <td class="px-4 py-3">
                                <select name="presencas[{{ $item->aluno_id }}][justificativa_falta_id]"
                                        class="w-full rounded-xl border
                                           border-slate-300 dark:border-slate-700
                                           px-3 py-2
                                           bg-white dark:bg-dax-dark text-sm">
                                    <option value="">—</option>
                                    @foreach($justificativas as $just)
                                        <option value="{{ $just->id }}"
                                            {{ $item->justificativa_falta_id == $just->id ? 'selected' : '' }}>
                                            {{ $just->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            {{-- Observação --}}
                            <td class="px-4 py-3">
                                <input type="text"
                                       name="presencas[{{ $item->aluno_id }}][observacao]"
                                       value="{{ $item->observacao }}"
                                       placeholder="Opcional"
                                       class="w-full rounded-xl border
                                          border-slate-300 dark:border-slate-700
                                          px-3 py-2
                                          bg-white dark:bg-dax-dark text-sm">
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ================= AÇÕES ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.presencas.show', $presenca) }}"
                   class="px-4 py-2.5 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl
                           bg-dax-green text-white font-bold
                           hover:bg-dax-greenSoft transition">
                    Salvar Presença
                </button>
            </div>

        </form>
    </div>
@endsection
