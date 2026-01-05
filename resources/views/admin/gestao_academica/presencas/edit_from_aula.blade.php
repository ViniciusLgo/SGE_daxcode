{{-- resources/views/admin/gestao_academica/presencas/edit_from_aula.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- ============================================================
            HEADER
            - Contexto da Aula
            - Ação de voltar para a aula
        ============================================================ --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    📋 Registrar Presença
                </h1>
                <p class="text-sm text-slate-500">
                    Aula de {{ $aula->disciplina->nome }} —
                    Turma {{ $aula->turma->nome }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.aulas.show', $aula) }}"
                   class="inline-flex items-center gap-2
                      px-4 py-2 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Voltar para Aula
                </a>
            </div>
        </div>

        {{-- ============================================================
            FORM
            - Salva presença via rota da Aula
            - Mantém integridade: 1 presença por aula
        ============================================================ --}}
        <form method="POST"
              action="{{ route('admin.aulas.presenca.update', $aula) }}"
              class="rounded-2xl
                 bg-white dark:bg-dax-dark/60
                 border border-slate-200 dark:border-slate-800
                 p-6 space-y-6">

            @csrf
            @method('PUT')

            {{-- ============================================================
                FLASH / ERROS
                - Exibe erros gerais e por campo (especialmente observação)
            ============================================================ --}}
            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <div class="font-semibold mb-2">Verifique os campos destacados:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ============================================================
                DADOS DA AULA
                - Exibe data, horário, professor e carga horária (blocos)
            ============================================================ --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                    📘 Dados da Aula
                </h2>

                <dl class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4 text-sm">

                    <div>
                        <dt class="text-slate-500">Data</dt>
                        <dd class="font-semibold">
                            {{ $aula->data->format('d/m/Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Horário</dt>
                        <dd class="font-semibold">
                            {{ $aula->hora_inicio }} → {{ $aula->hora_fim }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Professor</dt>
                        <dd class="font-semibold">
                            {{ $aula->professor->user->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-slate-500">Carga horária</dt>
                        <dd class="font-bold">
                            {{ $presenca->quantidade_blocos }} h/a
                        </dd>
                    </div>

                </dl>
            </div>

            {{-- ============================================================
                STATUS
                - Aberta / Finalizada
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
                REGRA IMPORTANTE:
                - Aqui a view NÃO deve listar alunos desistentes.
                - Blindagem feita em dois níveis:
                  (1) Controller sincroniza apenas ATIVOS
                  (2) View filtra novamente por segurança (anti-bug / anti-lixo)
                OBS:
                - Para funcionar, o controller deve ter carregado:
                  'alunos.aluno.matriculaModel'
            ============================================================ --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Aluno</th>

                        {{-- Blocos conforme quantidade_blocos --}}
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

                    @php
                        /**
                         * Filtra itens para garantir que apenas alunos ATIVOS apareçam no registro.
                         * - matrículaModel.status === 'ativo'
                         * Se por algum motivo matrículaModel vier nulo, tratamos como "não ativo"
                         * para não permitir aparecer na chamada.
                         */
                        $itensAtivos = $presenca->alunos->filter(function ($item) {
                            return ($item->aluno->matriculaModel?->status ?? null) === 'ativo';
                        });

                        /**
                         * Em alguns cenários pode existir presença criada e nenhum aluno ativo sincronizado.
                         * Ex.: turma sem alunos ativos.
                         */
                    @endphp

                    @if($itensAtivos->isEmpty())
                        <tr>
                            <td colspan="{{ 3 + (int)$presenca->quantidade_blocos }}"
                                class="px-4 py-8 text-center text-slate-500">
                                Nenhum aluno ativo encontrado para registrar presença nesta turma.
                            </td>
                        </tr>
                    @endif

                    @foreach($itensAtivos as $item)
                        <tr>

                            {{-- ============================================================
                                ALUNO
                            ============================================================ --}}
                            <td class="px-4 py-3 font-semibold">
                                {{ $item->aluno->user->name }}
                            </td>

                            {{-- ============================================================
                                BLOCOS DE PRESENÇA
                                - checkbox envia "1" quando marcado
                                - quando desmarcado, não envia
                                - o controller trata default como false (payload ?? false)
                            ============================================================ --}}
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

                            {{-- ============================================================
                                JUSTIFICATIVA
                                - lista somente justificativas ATIVAS (vem do controller)
                                - adiciona data-exige-observacao para regra no JS
                                - o histórico do item pode ter justificativa inativa,
                                  mas no formulário só permitimos escolher ativas.
                            ============================================================ --}}
                            <td class="px-4 py-3">
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

                                {{-- Erro específico por aluno (observação) pode ser mostrado abaixo se desejar --}}
                            </td>

                            {{-- ============================================================
                                OBSERVAÇÃO
                                - pode ser opcional
                                - vira obrigatória conforme justificativa (front)
                                - backend também valida (controller) para segurança
                            ============================================================ --}}
                            <td class="px-4 py-3">
                                <input type="text"
                                       name="presencas[{{ $item->aluno_id }}][observacao]"
                                       value="{{ old("presencas.$item->aluno_id.observacao", $item->observacao) }}"
                                       placeholder="Opcional"
                                       class="w-full rounded-xl border
                                          border-slate-300 dark:border-slate-700
                                          px-3 py-2
                                          bg-white dark:bg-dax-dark text-sm">

                                {{-- Exibe erro específico desta observação (se ValidationException disparar) --}}
                                @error("presencas.$item->aluno_id.observacao")
                                <div class="mt-1 text-xs text-red-600 font-semibold">
                                    {{ $message }}
                                </div>
                                @enderror
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            {{-- ============================================================
                AÇÕES
                - Salvar presença
            ============================================================ --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
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
        SCRIPT – Regras de UI para obrigatoriedade da Observação
        - Se justificativa exige_observacao = true => Observação obrigatória
        - Isso é UX (front)
        - Regra REAL é garantida no backend (controller)
    ============================================================ --}}
    @push('scripts')
        <script>
            /**
             * Aplica obrigatoriedade da observação por linha.
             * Lê:
             * - select option selecionada -> data-exige-observacao="1|0"
             * Ajusta:
             * - input required
             * - placeholder
             * - destaque visual
             */
            document.querySelectorAll('select[name*="[justificativa_falta_id]"]').forEach(select => {

                const row = select.closest('tr');
                const observacaoInput = row.querySelector('input[name*="[observacao]"]');

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
