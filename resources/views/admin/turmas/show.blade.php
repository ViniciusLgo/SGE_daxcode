@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{ modalAluno: false }">

        <!-- ========================== -->
        <!-- HEADER -->
        <!-- ========================== -->
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    {{ $turma->nome }}
                </h1>
                <p class="text-slate-500">
                    Visão geral da turma, disciplinas, professores e alunos.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.turmas.disciplinas', $turma->id) }}"
                   class="px-4 py-2 rounded-xl bg-dax-green text-white">
                    <i class="bi bi-collection"></i> Gerenciar Disciplinas
                </a>

                <a href="{{ route('admin.turmas.index') }}"
                   class="px-4 py-2 rounded-xl border
                      border-slate-200 dark:border-slate-800">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <!-- ========================== -->
        <!-- INFO + DISCIPLINAS -->
        <!-- ========================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- CARD INFORMAÇÕES -->
            <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold mb-4">📌 Informações da Turma</h2>

                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-slate-500">Nome</dt>
                    <dd>{{ $turma->nome }}</dd>

                    <dt class="text-slate-500">Ano letivo</dt>
                    <dd>{{ $turma->ano }}</dd>

                    <dt class="text-slate-500">Turno</dt>
                    <dd>{{ $turma->turno }}</dd>

                    <dt class="text-slate-500">Criada em</dt>
                    <dd>{{ $turma->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="text-slate-500">Atualizada em</dt>
                    <dd>{{ $turma->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>

                <div class="mt-4">
                    <h3 class="font-medium">📝 Descrição</h3>
                    <p class="text-slate-500 mt-1">
                        {{ $turma->descricao ?: 'Nenhuma descrição informada.' }}
                    </p>
                </div>
            </div>

            <!-- CARD DISCIPLINAS -->
            <div class="lg:col-span-2 rounded-2xl border bg-white dark:bg-dax-dark/60
                    border-slate-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold mb-4">📚 Disciplinas da Turma</h2>

                @forelse ($turma->disciplinaTurmas as $vinculo)
                    <div class="border rounded-xl p-4 mb-4 dark:border-slate-800">
                        <h3 class="font-semibold">{{ $vinculo->disciplina->nome }}</h3>

                        <p class="text-sm text-slate-500">
                            <strong>Ano letivo:</strong> {{ $vinculo->ano_letivo ?? '—' }} <br>
                            <strong>Observação:</strong> {{ $vinculo->observacao ?? '—' }}
                        </p>

                        <div class="mt-2">
                            <p class="font-medium">👨‍🏫 Professores</p>

                            @if($vinculo->professores->isEmpty())
                                <p class="text-sm text-slate-500">Nenhum professor vinculado.</p>
                            @else
                                <ul class="text-sm space-y-1">
                                    @foreach($vinculo->professores as $prof)
                                        <li>
                                            <strong>{{ $prof->user->name }}</strong>
                                            <span class="text-slate-500">
                                            ({{ $prof->user->email }})
                                        </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500">
                        Nenhuma disciplina vinculada a esta turma.
                    </p>
                @endforelse
            </div>
        </div>

        <!-- ========================== -->
        <!-- ALUNOS DA TURMA -->
        <!-- ========================== -->
        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold">🧑‍🎓 Alunos da Turma</h2>

                <button @click="modalAluno = true"
                        class="px-3 py-2 rounded-xl bg-dax-green text-white text-sm">
                    <i class="bi bi-plus-circle"></i> Atribuir Aluno
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500">
                    <tr>
                        <th class="py-2">Nome</th>
                        <th class="py-2">Matrícula</th>
                        <th class="py-2">Email</th>
                        <th class="py-2 text-right">Ações</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($alunos as $aluno)
                        <tr>
                            <td class="py-2">{{ $aluno->user->name }}</td>
                            <td class="py-2">{{ $aluno->matricula }}</td>
                            <td class="py-2">{{ $aluno->user->email }}</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('admin.alunos.show', $aluno) }}"
                                   class="text-sky-600 hover:underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="py-6 text-center text-slate-500">
                                Nenhum aluno cadastrado nesta turma.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($alunos->hasPages())
                <div class="mt-4">
                    {{ $alunos->links() }}
                </div>
            @endif
        </div>

        <!-- ========================== -->
        <!-- MODAL ATRIBUIR ALUNO -->
        <!-- ========================== -->
        <div x-show="modalAluno" x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <form method="POST"
                  action="{{ route('admin.turmas.atribuirAluno', $turma->id) }}"
                  class="bg-white dark:bg-dax-dark rounded-2xl p-6 w-full max-w-xl">
                @csrf

                <h3 class="font-semibold mb-3">Atribuir Aluno à Turma</h3>

                <!-- FILTROS -->
                <div class="space-y-2 mb-3">
                    <input id="filtroAlunoNome"
                           type="text"
                           placeholder="Buscar por nome, matrícula ou turma..."
                           class="w-full rounded-xl border px-3 py-2
                              bg-white dark:bg-dax-dark/60
                              border-slate-200 dark:border-slate-800">

                    <div class="flex gap-2">
                        <select id="ordenarAlunos"
                                class="flex-1 rounded-xl border px-3 py-2
                                   bg-white dark:bg-dax-dark/60
                                   border-slate-200 dark:border-slate-800">
                            <option value="">Ordenar por...</option>
                            <option value="nome">Nome</option>
                            <option value="matricula">Matrícula</option>
                            <option value="turma">Turma</option>
                        </select>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" id="filtroSemTurma">
                            Sem turma
                        </label>
                    </div>

                    <button type="button"
                            id="btnLimparFiltrosAlunos"
                            class="text-sm text-sky-600 hover:underline">
                        Limpar filtros
                    </button>
                </div>

                <select id="selectAluno"
                        name="aluno_id"
                        required
                        class="w-full rounded-xl border px-3 py-2 mb-4
                           bg-white dark:bg-dax-dark/60
                           border-slate-200 dark:border-slate-800">
                    <option value="">Selecione...</option>

                    @foreach($alunosDisponiveis as $a)
                        @php
                            $nomeLower = mb_strtolower($a->user->name, 'UTF-8');
                            $matriculaLower = mb_strtolower($a->matricula ?? '', 'UTF-8');
                            $turmaNome = $a->turma ? $a->turma->nome : '';
                            $turmaLower = mb_strtolower($turmaNome, 'UTF-8');
                        @endphp

                        <option value="{{ $a->id }}"
                                data-nome="{{ $nomeLower }}"
                                data-matricula="{{ $matriculaLower }}"
                                data-turma="{{ $turmaLower }}"
                                data-sem-turma="{{ $a->turma ? '0' : '1' }}">
                            {{ $a->user->name }}
                            @if($a->matricula) — {{ $a->matricula }} @endif
                            @if($turmaNome) — {{ $turmaNome }} @endif
                        </option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-2">
                    <button type="button"
                            @click="modalAluno = false"
                            class="px-4 py-2 rounded-xl border">
                        Cancelar
                    </button>
                    <button class="px-4 py-2 rounded-xl bg-dax-green text-white">
                        Atribuir
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- ========================== -->
    <!-- SCRIPT – FILTRO AO VIVO -->
    <!-- ========================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputBusca = document.getElementById('filtroAlunoNome');
            const selectOrdenar = document.getElementById('ordenarAlunos');
            const checkSemTurma = document.getElementById('filtroSemTurma');
            const btnLimpar = document.getElementById('btnLimparFiltrosAlunos');
            const selectAluno = document.getElementById('selectAluno');

            if (!selectAluno) return;

            const originalOptions = Array.from(selectAluno.options)
                .filter(opt => opt.value !== '')
                .map(opt => opt.cloneNode(true));

            function aplicarFiltros() {
                const termo = (inputBusca.value || '').toLowerCase();
                const ordenar = selectOrdenar.value;
                const apenasSemTurma = checkSemTurma.checked;

                let filtrados = originalOptions.filter(opt => {
                    const nome = opt.dataset.nome || '';
                    const matricula = opt.dataset.matricula || '';
                    const turma = opt.dataset.turma || '';
                    const semTurma = opt.dataset.semTurma === '1';

                    if (apenasSemTurma && !semTurma) return false;
                    if (!termo) return true;

                    return nome.includes(termo) ||
                        matricula.includes(termo) ||
                        turma.includes(termo);
                });

                if (ordenar) {
                    filtrados.sort((a, b) =>
                        (a.dataset[ordenar] || '').localeCompare(b.dataset[ordenar] || '')
                    );
                }

                selectAluno.innerHTML = '<option value="">Selecione...</option>';
                filtrados.forEach(opt => selectAluno.appendChild(opt));
            }

            inputBusca.addEventListener('input', aplicarFiltros);
            selectOrdenar.addEventListener('change', aplicarFiltros);
            checkSemTurma.addEventListener('change', aplicarFiltros);
            btnLimpar.addEventListener('click', () => {
                inputBusca.value = '';
                selectOrdenar.value = '';
                checkSemTurma.checked = false;
                aplicarFiltros();
            });

            aplicarFiltros();
        });
    </script>

@endsection
