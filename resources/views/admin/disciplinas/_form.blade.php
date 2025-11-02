@csrf
<div class="mb-3">
    <label for="nome" class="form-label">Nome da Disciplina</label>
    <input type="text" name="nome" id="nome" class="form-control"
           value="{{ old('nome', $disciplina->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="carga_horaria" class="form-label">Carga Horária (h)</label>
    <input type="number" name="carga_horaria" id="carga_horaria" class="form-control"
           value="{{ old('carga_horaria', $disciplina->carga_horaria ?? '') }}" min="1" max="500" required>
</div>

<div class="mb-3">
    <label for="descricao" class="form-label">Descrição</label>
    <textarea name="descricao" id="descricao" class="form-control" rows="3">{{ old('descricao', $disciplina->descricao ?? '') }}</textarea>
</div>

{{-- Seleção múltipla de professores --}}
{{-- Professores --}}
<div class="mb-3">
    <label for="professores" class="form-label fw-semibold">Professores</label>
    <select name="professores[]" id="professores" class="form-select" multiple required>
        @foreach(($professores ?? []) as $id => $nome)
            <option value="{{ $id }}"
                {{ in_array($id, old('professores', $professoresSelecionados ?? [])) ? 'selected' : '' }}>
                {{ $nome }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Segure Ctrl (ou Cmd) para selecionar vários.</small>
</div>



<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Salvar
    </button>
</div>
