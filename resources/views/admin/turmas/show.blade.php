@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">{{ $turma->nome }}</h3>
            <p class="text-muted mb-0">Visão geral da turma, disciplinas, professores e alunos.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.turmas.disciplinas', $turma->id) }}" class="btn btn-primary">
                <i class="bi bi-collection"></i> Gerenciar Disciplinas
            </a>

            <a href="{{ route('admin.turmas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row g-3">

        <!-- ========================== -->
        <!-- CARD 1 - INFORMAÇÕES -->
        <!-- ========================== -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5 class="card-title mb-3">📌 Informações da Turma</h5>

                    <dl class="row mb-0 text-muted">
                        <dt class="col-sm-5 fw-semibold">Nome</dt>
                        <dd class="col-sm-7">{{ $turma->nome }}</dd>

                        <dt class="col-sm-5 fw-semibold">Ano letivo</dt>
                        <dd class="col-sm-7">{{ $turma->ano }}</dd>

                        <dt class="col-sm-5 fw-semibold">Turno</dt>
                        <dd class="col-sm-7">{{ $turma->turno }}</dd>

                        <dt class="col-sm-5 fw-semibold">Criada em</dt>
                        <dd class="col-sm-7">{{ $turma->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5 fw-semibold">Atualizada em</dt>
                        <dd class="col-sm-7">{{ $turma->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>

                    <hr>

                    <h6 class="fw-bold">📝 Descrição</h6>
                    <p class="text-muted">
                        {{ $turma->descricao ?: 'Nenhuma descrição informada.' }}
                    </p>

                </div>
            </div>
        </div>

        <!-- ========================== -->
        <!-- CARD 2 - DISCIPLINAS -->
        <!-- ========================== -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5 class="card-title mb-3">📚 Disciplinas da Turma</h5>

                    @forelse ($turma->disciplinaTurmas as $vinculo)
                        <div class="p-3 border rounded mb-3 bg-light">

                            <h6 class="fw-bold mb-1">{{ $vinculo->disciplina->nome }}</h6>

                            <small class="text-muted d-block">
                                <strong>Ano letivo:</strong> {{ $vinculo->ano_letivo ?? '—' }}
                            </small>

                            <small class="text-muted d-block mb-2">
                                <strong>Observação:</strong> {{ $vinculo->observacao ?? '—' }}
                            </small>

                            <div class="mt-2">
                                <strong>👨‍🏫 Professores:</strong>

                                @if($vinculo->professores->isEmpty())
                                    <p class="text-muted mb-1">Nenhum professor vinculado.</p>
                                @else
                                    <ul class="mb-0">
                                        @foreach($vinculo->professores as $prof)
                                            <li>
                                                <strong>{{ $prof->user->name }}</strong>
                                                <small class="text-muted">
                                                    ({{ $prof->user->email }})
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-muted py-3">
                            Nenhuma disciplina vinculada a esta turma.
                        </p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

    <!-- ========================== -->
    <!-- ALUNOS DA TURMA -->
    <!-- ========================== -->
    <div class="row mt-3">
        <div class="col-12">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">🧑‍🎓 Alunos da Turma</h5>

                        <button class="btn btn-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAtribuirAluno">
                            <i class="bi bi-plus-circle"></i> Atribuir Aluno
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Matrícula</th>
                                <th>Email</th>
                                <th class="text-end">Ações</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($alunos as $aluno)
                                <tr>
                                    <td>{{ $aluno->user->name }}</td>
                                    <td>{{ $aluno->matricula }}</td>
                                    <td>{{ $aluno->user->email }}</td>

                                    <td class="text-end">
                                        <a href="{{ route('admin.alunos.show', $aluno) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Nenhum aluno cadastrado nesta turma.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                @if($alunos->hasPages())
                    <div class="card-footer bg-white">
                        {{ $alunos->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>


    <!-- ========================== -->
    <!-- MODAL – ATRIBUIR ALUNO -->
    <!-- ========================== -->
    <div class="modal fade" id="modalAtribuirAluno" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST"
                  action="{{ route('admin.turmas.atribuirAluno', $turma->id) }}"
                  class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Atribuir Aluno à Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- FILTROS (AO VIVO NO FRONT) -->
                    <div class="mb-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">
                                Use os filtros para localizar um aluno rapidamente.
                            </span>

                            <span id="statusFiltrosAlunos"
                                  class="badge bg-info-subtle text-info-emphasis d-none">
                                Filtros ativos
                            </span>
                        </div>

                        <div class="row g-2">

                            <div class="col-md-6">
                                <input
                                    type="text"
                                    id="filtroAlunoNome"
                                    class="form-control"
                                    placeholder="Buscar por nome, matrícula ou turma...">
                            </div>

                            <div class="col-md-4">
                                <select id="ordenarAlunos" class="form-select">
                                    <option value="">Ordenar por...</option>
                                    <option value="nome">Nome</option>
                                    <option value="matricula">Matrícula</option>
                                    <option value="turma">Turma</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check mt-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="filtroSemTurma">
                                    <label class="form-check-label small" for="filtroSemTurma">
                                        Sem turma
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="mt-2 text-end">
                            <button type="button"
                                    id="btnLimparFiltrosAlunos"
                                    class="btn btn-outline-secondary btn-sm">
                                Limpar filtros
                            </button>
                        </div>

                    </div>

                    <label class="form-label">Selecione o aluno</label>

                    <select id="selectAluno" name="aluno_id" class="form-select" required>
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
                                @if($a->matricula)
                                    — {{ $a->matricula }}
                                @endif
                                @if($turmaNome)
                                    — {{ $turmaNome }}
                                @endif
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn btn-primary">
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
            const badgeStatus = document.getElementById('statusFiltrosAlunos');

            if (!selectAluno) return;

            // Clona as opções originais (exceto o "Selecione...")
            const originalOptions = Array.from(selectAluno.options)
                .filter(opt => opt.value !== '')
                .map(opt => opt.cloneNode(true));

            function atualizarBadge(temFiltro) {
                if (temFiltro) {
                    badgeStatus.classList.remove('d-none');
                } else {
                    badgeStatus.classList.add('d-none');
                }
            }

            function aplicarFiltros() {
                const termo = (inputBusca.value || '').trim().toLowerCase();
                const ordenar = selectOrdenar.value;
                const apenasSemTurma = checkSemTurma.checked;

                let filtrados = originalOptions.filter(opt => {
                    const nome = (opt.dataset.nome || '').toLowerCase();
                    const matricula = (opt.dataset.matricula || '').toLowerCase();
                    const turma = (opt.dataset.turma || '').toLowerCase();
                    const semTurma = opt.dataset.semTurma === '1';

                    if (apenasSemTurma && !semTurma) {
                        return false;
                    }

                    if (!termo) {
                        return true;
                    }

                    return (
                        nome.includes(termo) ||
                        matricula.includes(termo) ||
                        turma.includes(termo)
                    );
                });

                // Ordenação
                if (ordenar === 'nome') {
                    filtrados.sort((a, b) => {
                        return (a.dataset.nome || '').localeCompare(b.dataset.nome || '');
                    });
                } else if (ordenar === 'matricula') {
                    filtrados.sort((a, b) => {
                        return (a.dataset.matricula || '').localeCompare(b.dataset.matricula || '');
                    });
                } else if (ordenar === 'turma') {
                    filtrados.sort((a, b) => {
                        return (a.dataset.turma || '').localeCompare(b.dataset.turma || '');
                    });
                }

                // Reconstroi o select
                selectAluno.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Selecione...';
                selectAluno.appendChild(placeholder);

                filtrados.forEach(opt => {
                    selectAluno.appendChild(opt);
                });

                const temFiltroAtivo = !!termo || !!ordenar || apenasSemTurma;
                atualizarBadge(temFiltroAtivo);
            }

            // Eventos
            inputBusca.addEventListener('input', aplicarFiltros);
            selectOrdenar.addEventListener('change', aplicarFiltros);
            checkSemTurma.addEventListener('change', aplicarFiltros);
            btnLimpar.addEventListener('click', function () {
                inputBusca.value = '';
                selectOrdenar.value = '';
                checkSemTurma.checked = false;
                aplicarFiltros();
            });

            // Inicializa sem filtros
            aplicarFiltros();
        });
    </script>

@endsection
