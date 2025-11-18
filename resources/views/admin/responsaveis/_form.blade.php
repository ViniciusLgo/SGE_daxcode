{{-- ================================================ --}}
{{-- FORMULÁRIO DOS RESPONSÁVEIS                     --}}
{{-- Estrutura correta:                              --}}
{{-- - Dados do usuário (name, email, senha)         --}}
{{-- - Dados do responsável (telefone, cpf, parentesco) --}}
{{-- - Vínculo com alunos                            --}}
{{-- ================================================ --}}

<div class="card shadow-sm border-0 p-4">

    <div class="row g-3">

        {{-- ========================== --}}
        {{-- NOME DO USUÁRIO --}}
        {{-- ========================== --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nome *</label>
            <input type="text" name="name" class="form-control" required
                   value="{{ old('name', $responsavel->user->name ?? '') }}">
        </div>

        {{-- ========================== --}}
        {{-- EMAIL DO USUÁRIO --}}
        {{-- ========================== --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">E-mail *</label>
            <input type="email" name="email" class="form-control" required
                   value="{{ old('email', $responsavel->user->email ?? '') }}">
        </div>

        {{-- ========================== --}}
        {{-- SENHA (somente no create) --}}
        {{-- ========================== --}}
        @if(!isset($responsavel))
            <div class="col-md-6">
                <label class="form-label fw-semibold">Senha *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Confirmar Senha *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        @endif

        {{-- ================================== --}}
        {{-- DADOS ESPECÍFICOS DO RESPONSÁVEL --}}
        {{-- ================================== --}}

        <div class="col-md-6">
            <label class="form-label fw-semibold">Telefone</label>
            <input type="text" name="telefone" class="form-control"
                   value="{{ old('telefone', $responsavel->telefone ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">CPF</label>
            <input type="text" name="cpf" class="form-control"
                   value="{{ old('cpf', $responsavel->cpf ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Grau de Parentesco</label>
            <input type="text" name="grau_parentesco" class="form-control"
                   value="{{ old('grau_parentesco', $responsavel->grau_parentesco ?? '') }}">
        </div>

        {{-- ========================== --}}
        {{-- ALUNOS VINCULADOS --}}
        {{-- ========================== --}}
        <div class="col-md-12">
            <label class="form-label fw-semibold">Vincular Alunos</label>
            <select name="alunos[]" multiple class="form-select">
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}"
                        @selected(isset($responsavel) && $responsavel->alunos->contains($aluno->id))>
                        {{ $aluno->user->name }} — {{ $aluno->turma->nome ?? 'Sem turma' }}
                    </option>
                @endforeach
            </select>

            <small class="text-muted d-block mt-1">
                Segure CTRL para selecionar múltiplos alunos.
            </small>
        </div>
    </div>

    {{-- BOTÕES --}}
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('admin.responsaveis.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>

        <button type="submit" class="btn btn-success px-4">
            <i class="bi bi-save"></i> Salvar
        </button>
    </div>

</div>
