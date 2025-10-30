@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $turma->nome ?? '') }}" class="form-control @error('nome') is-invalid @enderror" required>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="turno" class="form-label">Turno</label>
        <input type="text" name="turno" id="turno" value="{{ old('turno', $turma->turno ?? '') }}" class="form-control @error('turno') is-invalid @enderror" placeholder="Matutino, vespertino...">
        @error('turno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="ano" class="form-label">Ano</label>
        <input type="number" name="ano" id="ano" value="{{ old('ano', $turma->ano ?? '') }}" class="form-control @error('ano') is-invalid @enderror" min="2000" max="2100">
        @error('ano')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" id="descricao" rows="3" class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $turma->descricao ?? '') }}</textarea>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.turmas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($turma) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
