@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1">🧩 Vincular Professores à Disciplina</h4>
        <p class="text-muted mb-0">Associe até 4 professores à disciplina desta turma.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.disciplina_turma.update', $vinculo->id) }}">
                @csrf
                @method('PUT')

                {{-- Turma e Disciplina fixas --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Turma</label>
                        <input type="text" class="form-control bg-light" value="{{ $vinculo->turma->nome }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Disciplina</label>
                        <input type="text" class="form-control bg-light" value="{{ $vinculo->disciplina->nome }}" readonly>
                    </div>
                </div>

                {{-- Seletor de Professores --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Professores Vinculados</label>
                    <div id="professores-container" class="d-flex flex-wrap gap-2 mb-2">
                        {{-- Professores ativos --}}
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            ➕ Adicionar Professor
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($professores as $prof)
                                <li>
                                    <a class="dropdown-item add-professor" href="#" data-id="{{ $prof->id }}" data-nome="{{ $prof->nome }}">
                                        {{ $prof->nome }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Campo oculto para armazenar IDs dos professores --}}
                    <input type="hidden" name="professores" id="professoresSelecionados" value="{{ $vinculo->professores->pluck('id')->join(',') }}">
                    <small class="text-muted d-block mt-2">Selecione até 4 professores.</small>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Ano Letivo</label>
                        <input type="text" name="ano_letivo" class="form-control" value="{{ old('ano_letivo', $vinculo->ano_letivo ?? '') }}" placeholder="Ex: 2025">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Observação</label>
                        <input type="text" name="observacao" class="form-control" value="{{ old('observacao', $vinculo->observacao ?? '') }}" placeholder="Ex: turma reforço, aula prática etc.">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.disciplina_turma.index') }}" class="btn btn-secondary">⬅️ Voltar</a>
                    <button class="btn btn-success">💾 Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('professores-container');
            const inputHidden = document.getElementById('professoresSelecionados');
            let selecionados = inputHidden.value ? inputHidden.value.split(',').map(Number) : [];

            // renderizar professores atuais
            selecionados.forEach(id => {
                const nome = document.querySelector(`[data-id="${id}"]`)?.dataset.nome;
                if (nome) adicionarChip(id, nome);
            });

            function adicionarChip(id, nome) {
                if (selecionados.includes(id)) return;
                if (selecionados.length >= 4) return alert('Máximo de 4 professores.');

                const chip = document.createElement('span');
                chip.className = 'badge bg-primary d-flex align-items-center gap-1 px-2 py-2';
                chip.innerHTML = `${nome} <button type="button" class="btn-close btn-close-white btn-sm ms-1"></button>`;
                chip.querySelector('button').addEventListener('click', () => removerChip(id, chip));
                container.appendChild(chip);

                selecionados.push(id);
                atualizarInput();
            }

            function removerChip(id, chip) {
                chip.remove();
                selecionados = selecionados.filter(i => i !== id);
                atualizarInput();
            }

            function atualizarInput() {
                inputHidden.value = selecionados.join(',');
            }

            document.querySelectorAll('.add-professor').forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    const id = parseInt(item.dataset.id);
                    const nome = item.dataset.nome;
                    adicionarChip(id, nome);
                });
            });
        });
    </script>
@endsection
