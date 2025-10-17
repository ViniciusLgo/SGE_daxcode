@csrf

@php($currentTurma = old('turma_id', $aluno->turma_id ?? ($selectedTurma ?? '')))

<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $aluno->nome ?? '') }}" class="form-control @error('nome') is-invalid @enderror" required>
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="matricula" class="form-label">Matrícula</label>
        <input type="text" name="matricula" id="matricula" value="{{ old('matricula', $aluno->matricula ?? '') }}" class="form-control @error('matricula') is-invalid @enderror" required>
        @error('matricula')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="email" id="email" value="{{ old('email', $aluno->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="turma_id" class="form-label">Turma</label>
        <select name="turma_id" id="turma_id" class="form-select @error('turma_id') is-invalid @enderror" required>
            <option value="" disabled {{ $currentTurma ? '' : 'selected' }}>Selecione</option>
            @foreach($turmas as $id => $nome)
                <option value="{{ $id }}" {{ (string) $id === (string) $currentTurma ? 'selected' : '' }}>
                    {{ $nome }}
                </option>
            @endforeach
        </select>
        @error('turma_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="data_nascimento" class="form-label">Data de nascimento</label>
        <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento', isset($aluno) && $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : '') }}" class="form-control @error('data_nascimento') is-invalid @enderror">
        @error('data_nascimento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $aluno->telefone ?? '') }}" class="form-control @error('telefone') is-invalid @enderror">
        @error('telefone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.alunos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        {{ isset($aluno) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
