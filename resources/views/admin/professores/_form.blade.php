<div class="mb-3">
    <label class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $professor->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">E-mail</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $professor->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Telefone</label>
    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $professor->telefone ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Especialização</label>
    <input type="text" name="especializacao" class="form-control"
           value="{{ old('especializacao', $professor->especializacao ?? '') }}">
</div>

<div class="text-end">
    <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Salvar
    </button>
</div>
