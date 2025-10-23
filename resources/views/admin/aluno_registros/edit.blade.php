@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4>✏️ Editar Registro</h4>
        <p class="text-muted">Atualize informações e status do registro.</p>
    </div>

    @include('partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('aluno_registros.update', $aluno_registro->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Aluno *</label>
                    <select name="aluno_id" class="form-select" required>
                        @foreach($alunos as $a)
                            <option value="{{ $a->id }}" {{ $aluno_registro->aluno_id == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Turma</label>
                    <select name="turma_id" class="form-select">
                        <option value="">Selecione...</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}" {{ $aluno_registro->turma_id == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <input type="text" name="tipo" class="form-control" value="{{ $aluno_registro->tipo }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoria</label>
                    <input type="text" name="categoria" class="form-control" value="{{ $aluno_registro->categoria }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data do evento</label>
                    <input type="date" name="data_evento" class="form-control" value="{{ $aluno_registro->data_evento }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" rows="3" class="form-control">{{ $aluno_registro->descricao }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pendente','validado','arquivado','expirado'] as $s)
                            <option value="{{ $s }}" {{ $aluno_registro->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Novo arquivo (opcional)</label>
                    <input type="file" name="arquivo" class="form-control">
                    @if($aluno_registro->arquivo)
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-paperclip"></i> Arquivo atual:
                            <a href="{{ asset($aluno_registro->arquivo) }}" target="_blank">Ver documento</a>
                        </small>
                    @endif
                </div>
                <div class="col-12 d-flex justify-content-between mt-3">
                    <a href="{{ route('aluno_registros.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Voltar</a>
                    <button class="btn btn-success"><i class="bi bi-save"></i> Atualizar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
