@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $professor->nome ?? '') }}" class="form-control @error('nome') is-invalid @enderror" required>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="email" id="email" value="{{ old('email', $professor->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $professor->telefone ?? '') }}" class="form-control @error('telefone') is-invalid @enderror">
        @error('telefone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="especializacao" class="form-label">Especialização</label>
        <input type="text" name="especializacao" id="especializacao" value="{{ old('especializacao', $professor->especializacao ?? '') }}" class="form-control @error('especializacao') is-invalid @enderror">
        @error('especializacao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.professores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($professor) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
