{{-- ============================================================================
| resources/views/admin/gestao_academica/presencas/edit.blade.php
|
| VIEW: Editar Presença
|
| Finalidade:
| - Editar uma presença JÁ EXISTENTE
| - Permitir correções posteriores (ex: justificativa, observação, blocos)
|
| REGRAS IMPORTANTES:
| 1) Histórico:
|    - Pode exibir alunos que hoje estão inativos/desistentes
|    - Pode exibir justificativas que hoje estão inativas
|
| 2) Edição:
|    - Apenas alunos ATIVOS devem ser editáveis
|    - Alunos INATIVOS aparecem bloqueados (somente leitura)
|
| 3) Justificativas:
|    - Select mostra SOMENTE justificativas ATIVAS
|    - Se já houver justificativa inativa salva, ela é exibida como texto
|
| 4) Observação:
|    - Pode ser obrigatória conforme justificativa (exige_observacao)
|    - Validação garantida também no backend (controller)
============================================================================ --}}

@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ============================================================
            HEADER
        ============================================================ --}}
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

        {{-- ============================================================
            FORM
        ============================================================ --}}
        <form method="POST"
              action="{{ route('admin.presencas.update', $presenca) }}"
              class="rounded-2xl
                 bg-white dark:bg-dax-dark/60
                 border border-slate-200 dark:border-slate-800
                 p-6 space-y-6">

            @csrf
            @method('PUT')

            {{-- ============================================================
                ERROS GERAIS
            ============================================================ --}}
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <div class="font-semibold mb-2">Verifique os campos abaixo:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ============================================================
                DADOS DA AULA
            ============================================================ --}}
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

            {{-- ============================================================
                STATUS
            ============================================================ --}}
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

            {{-- ============================================================
                TABELA DE ALUNOS
            ============================================================ --}}
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

                        @php
                            $ativo = ($item->aluno->matriculaModel?->status ?? null) === 'ativo';
                        @endphp

                        <tr class="{{ !$ativo ? 'opacity-60 bg-slate-50 dark:bg-slate-900/40' : '' }}">

                            {{-- ====================================================
                                ALUNO
                            ==================================================== --}}
                            <td class="px-4 py-3 font-semibold">
                                {{ $item->aluno->user->name }}
                                @if(!$ativo)
                                    <div class="text-xs text-red-600 font-semibold">
                                        (Aluno inativo)
                                    </div>
                                @endif
                            </td>

                            {{-- ====================================================
                                BLOCOS
                                - Inativo: somente leitura
                            ==================================================== --}}
                            @for($i = 1; $i <= $presenca->quantidade_blocos; $i++)
                                @php $campo = 'bloco_'.$i; @endphp
                                <td class="px-4 py-3 text-center">
                                    @if($ativo)
                                        <input type="checkbox"
                                               name="presencas[{{ $item->aluno_id }}][{{ $campo }}]"
                                               value="1"
                                               {{ $item->$campo ? 'checked' : '' }}
                                               class="rounded border-slate-300 dark:border-slate-700">
                                    @else
                                        {{ $item->$campo ? '✔' : '✖' }}
                                    @endif
                                </td>
                            @endfor

                            {{-- ====================================================
                                JUSTIFICATIVA
                                - Ativo: select
                                - Inativo: texto fixo
                            ==================================================== --}}
                            <td class="px-4 py-3">
                                @if($ativo)
                                    <select name="presencas[{{ $item->aluno_id }}][justificativa_falta_id]"
                                            class="w-full rounded-xl border
                                               border-slate-300 dark:border-slate-700
                                               px-3 py-2
                                               bg-white dark:bg-dax-dark text-sm">
                                        <option value="">— Selecione —</option>

                                        @foreach($justificativas as $just)
                                            <option value="{{ $just->id }}"
                                                    data-exige-observacao="{{ $just->exige_observacao ? '1' : '0' }}"
                                                {{ (string)$item->justificativa_falta_id === (string)$just->id ? 'selected' : '' }}>
                                                {{ $just->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $item->justificativa?->nome ?? '—' }}
                                @endif
                            </td>

                            {{-- ====================================================
                                OBSERVAÇÃO
                            ==================================================== --}}
                            <td class="px-4 py-3">
                                @if($ativo)
                                    <input type="text"
                                           name="presencas[{{ $item->aluno_id }}][observacao]"
                                           value="{{ old("presencas.$item->aluno_id.observacao", $item->observacao) }}"
                                           placeholder="Opcional"
                                           class="w-full rounded-xl border
                                              border-slate-300 dark:border-slate-700
                                              px-3 py-2
                                              bg-white dark:bg-dax-dark text-sm">

                                    @error("presencas.$item->aluno_id.observacao")
                                    <div class="mt-1 text-xs text-red-600 font-semibold">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                @else
                                    {{ $item->observacao ?? '—' }}
                                @endif
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            {{-- ============================================================
                AÇÕES
            ============================================================ --}}
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

    {{-- ============================================================
        SCRIPT – Obrigatoriedade da Observação (UX)
    ============================================================ --}}
    @push('scripts')
        <script>
            document.querySelectorAll('select[name*="[justificativa_falta_id]"]').forEach(select => {

                const row = select.closest('tr');
                const observacaoInput = row.querySelector('input[name*="[observacao]"]');

                if (!observacaoInput) return;

                function toggleObrigatoriedade() {
                    const exige = select.selectedOptions[0]?.dataset.exigeObservacao === '1';

                    if (exige) {
                        observacaoInput.required = true;
                        observacaoInput.placeholder = 'Observação obrigatória';
                        observacaoInput.classList.add('border-red-400');
                    } else {
                        observacaoInput.required = false;
                        observacaoInput.placeholder = 'Opcional';
                        observacaoInput.classList.remove('border-red-400');
                    }
                }

                select.addEventListener('change', toggleObrigatoriedade);
                toggleObrigatoriedade();
            });
        </script>
    @endpush

@endsection
