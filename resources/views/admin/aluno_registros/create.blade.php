@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4>➕ Novo Registro</h4>
        <p class="text-muted">Adicione um documento ou ocorrência para um aluno.</p>
    </div>

    @include('partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- FORMULÁRIO --}}
            <form action="{{ route('admin.aluno_registros.store') }}"
                  method="POST" enctype="multipart/form-data"
                  class="row g-3">
                @csrf

                {{-- ===================================================== --}}
                {{-- SELECT DE ALUNOS - Corrigido para exibir nome correto --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Aluno *</label>
                    <select name="aluno_id" id="aluno_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        @foreach($alunos as $aluno)
                            {{-- Exibe nome do usuário vinculado ao aluno --}}
                            <option value="{{ $aluno->id }}">
                                {{ $aluno->user->name ?? 'Sem nome' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ===================================================== --}}
                {{-- TURMA (AUTO-PREENCHIDA via AJAX futuramente) --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Turma</label>

                    {{-- Campo exibido (somente leitura) --}}
                    <input type="text" id="turma_nome"
                           class="form-control bg-light"
                           placeholder="Selecione um aluno"
                           readonly>

                    {{-- Campo oculto para enviar o ID da turma --}}
                    <input type="hidden" name="turma_id" id="turma_id">
                </div>

                {{-- ===================================================== --}}
                {{-- TIPO DO REGISTRO --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo *</label>
                    <input type="text" name="tipo" class="form-control"
                           placeholder="Ex: Atestado de falta" required>
                </div>

                {{-- ===================================================== --}}
                {{-- CATEGORIA (ainda simples; depois vira CRUD próprio) --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Categoria</label>
                    <input type="text" name="categoria" class="form-control"
                           placeholder="Ex: Frequência">
                </div>

                {{-- ===================================================== --}}
                {{-- DATA DO EVENTO --}}
                {{-- ===================================================== --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Data do evento</label>
                    <input type="date" name="data_evento" class="form-control">
                </div>

                {{-- ===================================================== --}}
                {{-- DESCRIÇÃO / OBSERVAÇÕES --}}
                {{-- ===================================================== --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Descrição / Observações</label>
                    <textarea name="descricao" rows="3" class="form-control"></textarea>
                </div>

                {{-- ===================================================== --}}
                {{-- UPLOAD DE ARQUIVO --}}
                {{-- ===================================================== --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Arquivo (opcional)</label>
                    <input type="file" name="arquivo" class="form-control">
                    <small class="text-muted">PDF, JPG, PNG — máx. 5MB</small>
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
                        <i class="bi bi-save"></i> Salvar
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- =============================================== --}}
    {{-- JS PARA PREENCHER AUTOMATICAMENTE A TURMA --}}
    {{-- =============================================== --}}
    <script>
        document.getElementById('aluno_id').addEventListener('change', function () {

            let alunoId = this.value;
            if (!alunoId) return;

            fetch(`/admin/buscar-turma-aluno/${alunoId}`)
                .then(response => response.json())
                .then(data => {

                    // Se aluno não tiver turma
                    if (data.sem_turma) {
                        alert("⚠️ Este aluno não está vinculado a nenhuma turma!");
                        document.getElementById('turma_nome').value = "";
                        document.getElementById('turma_id').value = "";
                        return;
                    }

                    // Preenche automaticamente a turma
                    document.getElementById('turma_nome').value = data.turma;
                    document.getElementById('turma_id').value = data.turma_id;
                })
                .catch(() => {
                    alert("Erro ao buscar turma do aluno. Tente novamente.");
                });

        });
    </script>
@endsection
