<div class="mb-3">
    <label class="form-label">Turma</label>
    <select name="turma_id" class="form-select" required>
        <option value="">Selecione...</option>
        @foreach($turmas as $turma)
            <option value="{{ $turma->id }}" {{ old('turma_id', $vinculo->turma_id ?? '') == $turma->id ? 'selected' : '' }}>
                {{ $turma->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Disciplina</label>
    <select name="disciplina_id" class="form-select" required>
        <option value="">Selecione...</option>
        @foreach($disciplinas as $disciplina)
            <option value="{{ $disciplina->id }}" {{ old('disciplina_id', $vinculo->disciplina_id ?? '') == $disciplina->id ? 'selected' : '' }}>
                {{ $disciplina->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Professor</label>
    <select name="professor_id" class="form-select">
        <option value="">Selecione...</option>
        @foreach($professores as $professor)
            <option value="{{ $professor->id }}" {{ old('professor_id', $vinculo->professor_id ?? '') == $professor->id ? 'selected' : '' }}>
                {{ $professor->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Ano Letivo</label>
    <input type="text" name="ano_letivo" class="form-control" value="{{ old('ano_letivo', $vinculo->ano_letivo ?? '') }}" placeholder="Ex: 2025">
</div>

<div class="mb-3">
    <label class="form-label">Observação</label>
    <textarea name="observacao" class="form-control" rows="3">{{ old('observacao', $vinculo->observacao ?? '') }}</textarea>
</div>

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.disciplina_turma.index') }}" class="btn btn-secondary">⬅️ Voltar</a>
    <button class="btn btn-success">{{ $buttonText }}</button>
</div>
