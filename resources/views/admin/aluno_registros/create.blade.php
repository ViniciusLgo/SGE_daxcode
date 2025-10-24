@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4>➕ Novo Registro</h4>
        <p class="text-muted">Adicione um documento ou ocorrência para um aluno.</p>
    </div>

    @include('partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.aluno_registros.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <select name="aluno_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                    @endforeach
                </select>

                <div class="col-md-6">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select">
                        <option value="">Selecione...</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <input type="text" name="tipo" class="form-control" placeholder="Ex: Atestado de falta" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Categoria</label>
                    <input type="text" name="categoria" class="form-control" placeholder="Ex: Frequência">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Data do evento</label>
                    <input type="date" name="data_evento" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Descrição / Observações</label>
                    <textarea name="descricao" rows="3" class="form-control"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Arquivo (opcional)</label>
                    <input type="file" name="arquivo" class="form-control">
                    <small class="text-muted">PDF, JPG, PNG — máx. 5MB</small>
                </div>

                <div class="col-12 d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.aluno_registros.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <button class="btn btn-success">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
