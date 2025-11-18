@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4>✏️ Editar Registro</h4>
        <p class="text-muted">Atualize as informações deste registro.</p>
    </div>

    @include('partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- FORMULÁRIO DE EDIÇÃO --}}
            <form action="{{ route('admin.aluno_registros.update', $aluno_registro->id) }}"
                  method="POST" enctype="multipart/form-data"
                  class="row g-3">

                @csrf
                @method('PUT')

                {{-- ===================================================== --}}
                {{-- ALUNO --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Aluno *</label>
                    <select name="aluno_id" id="aluno_id" class="form-select" required>
                        @foreach($alunos as $a)
                            <option value="{{ $a->id }}"
                                {{ $aluno_registro->aluno_id == $a->id ? 'selected' : '' }}>
                                {{ $a->user->name ?? 'Sem nome' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ===================================================== --}}
                {{-- TURMA (AUTO PREENCHIDA, NÃO EDITÁVEL) --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Turma</label>

                    <input type="text" id="turma_nome"
                           value="{{ $aluno_registro->turma->nome ?? 'Sem turma' }}"
                           class="form-control bg-light" readonly>

                    <input type="hidden" name="turma_id" id="turma_id"
                           value="{{ $aluno_registro->turma_id }}">
                </div>

                {{-- ===================================================== --}}
                {{-- TIPO --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo *</label>
                    <input type="text" name="tipo"
                           class="form-control"
                           value="{{ $aluno_registro->tipo }}" required>
                </div>

                {{-- ===================================================== --}}
                {{-- CATEGORIA --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Categoria</label>
                    <input type="text" name="categoria"
                           class="form-control"
                           value="{{ $aluno_registro->categoria }}">
                </div>

                {{-- ===================================================== --}}
                {{-- DATA DO EVENTO --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Data do evento</label>
                    <input type="date" name="data_evento"
                           class="form-control"
                           value="{{ $aluno_registro->data_evento }}">
                </div>

                {{-- ===================================================== --}}
                {{-- DESCRIÇÃO --}}
                {{-- ===================================================== --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Descrição</label>
                    <textarea name="descricao" rows="3"
                              class="form-control">{{ $aluno_registro->descricao }}</textarea>
                </div>

                {{-- ===================================================== --}}
                {{-- STATUS --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pendente','validado','arquivado','expirado'] as $s)
                            <option value="{{ $s }}"
                                {{ $aluno_registro->status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ===================================================== --}}
                {{-- NOVO ARQUIVO --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Novo arquivo (opcional)</label>
                    <input type="file" name="arquivo" class="form-control">

                    @if($aluno_registro->arquivo)
                        <small class="text-muted d-block mt-2">
                            📎 Arquivo atual:
                            <a href="{{ asset($aluno_registro->arquivo) }}"
                               target="_blank">
                                Ver documento
                            </a>
                        </small>
                    @endif
                </div>

                {{-- ===================================================== --}}
                {{-- BOTÕES --}}
                {{-- ===================================================== --}}
                <div class="col-12 d-flex justify-content-between mt-3">

                    <a href="{{ route('admin.aluno_registros.index') }}"
                       class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>

                    <button class="btn btn-success">
                        <i class="bi bi-save"></i> Atualizar
                    </button>

                </div>

            </form>
        </div>
    </div>

    {{-- =============================================== --}}
    {{-- JS PARA ATUALIZAR TURMA AO TROCAR DE ALUNO --}}
    {{-- =============================================== --}}
    <script>
        document.getElementById('aluno_id').addEventListener('change', function () {

            let alunoId = this.value;
            if (!alunoId) return;

            fetch(`/admin/buscar-turma-aluno/${alunoId}`)
                .then(response => response.json())
                .then(data => {

                    if (data.sem_turma) {
                        alert("⚠️ Este aluno não está vinculado a nenhuma turma!");
                        document.getElementById('turma_nome').value = "";
                        document.getElementById('turma_id').value = "";
                        return;
                    }

                    // Preenche turma nova
                    document.getElementById('turma_nome').value = data.turma;
                    document.getElementById('turma_id').value = data.turma_id;
                })
                .catch(() => {
                    alert("Erro ao buscar turma do aluno.");
                });

        });
    </script>

@endsection
