@csrf

<!-- ========================================================= -->
<!-- FORMULÁRIO DE CADASTRO / EDIÇÃO DE TURMA                 -->
<!-- Este form é reaproveitado nas telas create e edit         -->
<!-- ========================================================= -->

<div class="row g-3">

    <!-- NOME DA TURMA -->
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome da Turma</label>
        <input
            type="text"
            name="nome"
            id="nome"
            value="{{ old('nome', $turma->nome ?? '') }}"
            class="form-control @error('nome') is-invalid @enderror"
            placeholder="Ex: T1 2025 Manhã"
            required
        >
        @error('nome')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- TURNO -->
    <div class="col-md-3">
        <label for="turno" class="form-label">Turno</label>
        <select name="turno" id="turno"
                class="form-select @error('turno') is-invalid @enderror">
            <option value="">Selecione...</option>
            <option value="Matutino" {{ old('turno', $turma->turno ?? '') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
            <option value="Vespertino" {{ old('turno', $turma->turno ?? '') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
            <option value="Noturno" {{ old('turno', $turma->turno ?? '') == 'Noturno' ? 'selected' : '' }}>Noturno</option>
        </select>

        @error('turno')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <!-- ANO LETIVO -->
    <div class="col-md-3">
        <label for="ano" class="form-label">Ano Letivo</label>
        <input
            type="number"
            name="ano"
            id="ano"
            value="{{ old('ano', $turma->ano ?? '') }}"
            min="2000"
            max="2100"
            class="form-control @error('ano') is-invalid @enderror"
            placeholder="2025"
        >
        @error('ano')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- DESCRIÇÃO -->
    <div class="col-12">
        <label for="descricao" class="form-label">Descrição da Turma</label>
        <textarea
            name="descricao"
            id="descricao"
            rows="3"
            class="form-control @error('descricao') is-invalid @enderror"
            placeholder="Informações adicionais sobre a turma (opcional)"
        >{{ old('descricao', $turma->descricao ?? '') }}</textarea>
        @error('descricao')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<!-- BOTÕES DE AÇÃO -->
<div class="d-flex justify-content-end gap-2 mt-4">

    <!-- Botão cancelar -->
    <a href="{{ route('admin.turmas.index') }}" class="btn btn-outline-secondary">
        Cancelar
    </a>

    <!-- Botão salvar -->
    <button type="submit" class="btn btn-primary">
        {{ isset($turma) ? 'Atualizar Turma' : 'Salvar Turma' }}
    </button>

</div>
