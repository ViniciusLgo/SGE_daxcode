@csrf
@php($currentTurma = old('turma_id', $aluno->turma_id ?? ''))

<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome do Aluno</label>
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
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $aluno->telefone ?? '') }}" class="form-control @error('telefone') is-invalid @enderror">
        @error('telefone')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento', isset($aluno) && $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : '') }}" class="form-control @error('data_nascimento') is-invalid @enderror">
        @error('data_nascimento')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="turma_id" class="form-label">Turma</label>
        <select name="turma_id" id="turma_id" class="form-select @error('turma_id') is-invalid @enderror" required>
            <option value="" disabled {{ !$currentTurma ? 'selected' : '' }}>Selecione a turma</option>
            @foreach ($turmas as $turma)
                <option value="{{ $turma->id }}" {{ (string) $turma->id === (string) $currentTurma ? 'selected' : '' }}>
                    {{ $turma->nome }}
                </option>
            @endforeach
        </select>
        @error('turma_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="foto_perfil" class="form-label">Foto de Perfil</label>
        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control @error('foto_perfil') is-invalid @enderror">
        @error('foto_perfil')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($aluno) && $aluno->foto_perfil)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $aluno->foto_perfil) }}" alt="Foto atual" class="rounded" width="90">
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('admin.alunos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" class="btn btn-success">{{ isset($aluno) ? 'Atualizar Aluno' : 'Salvar Aluno' }}</button>
</div>
