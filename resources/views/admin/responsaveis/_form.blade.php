<div class="card shadow-sm border-0 p-4">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nome *</label>
            <input type="text" name="nome" class="form-control" required
                   value="{{ old('nome', $responsavel->nome ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">CPF</label>
            <input type="text" name="cpf" class="form-control"
                   value="{{ old('cpf', $responsavel->cpf ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">E-mail</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $responsavel->email ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Telefone</label>
            <input type="text" name="telefone" class="form-control"
                   value="{{ old('telefone', $responsavel->telefone ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Parentesco</label>
            <input type="text" name="grau_parentesco" class="form-control"
                   value="{{ old('grau_parentesco', $responsavel->grau_parentesco ?? '') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label fw-semibold">Alunos Vinculados</label>
            <select name="alunos[]" multiple class="form-select">
                @foreach($alunos as $a)
                    <option value="{{ $a->id }}"
                        @selected(isset($responsavel) && $responsavel->alunos->contains($a->id))>
                        {{ $a->nome }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-success px-4">Salvar</button>
        <a href="{{ route('admin.responsaveis.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>
