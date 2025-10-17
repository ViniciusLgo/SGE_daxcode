@csrf

@php($currentProfessor = old('professor_id', $disciplina->professor_id ?? ($selectedProfessor ?? '')))

<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $disciplina->nome ?? '') }}" class="form-control @error('nome') is-invalid @enderror" required>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="professor_id" class="form-label">Professor responsável</label>
        <select name="professor_id" id="professor_id" class="form-select @error('professor_id') is-invalid @enderror" required>
            <option value="" disabled {{ $currentProfessor ? '' : 'selected' }}>Selecione</option>
            @foreach($professores as $id => $nome)
                <option value="{{ $id }}" {{ (string) $id === (string) $currentProfessor ? 'selected' : '' }}>
                    {{ $nome }}
                </option>
            @endforeach
        </select>
        @error('professor_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="carga_horaria" class="form-label">Carga horária (h)</label>
        <input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria', $disciplina->carga_horaria ?? '') }}" class="form-control @error('carga_horaria') is-invalid @enderror" min="1" max="500">
        @error('carga_horaria')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" id="descricao" rows="3" class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $disciplina->descricao ?? '') }}</textarea>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($disciplina) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
