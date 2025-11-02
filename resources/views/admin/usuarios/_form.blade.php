@csrf
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nome</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">E-mail</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Tipo de Usuário</label>
        <select name="tipo" class="form-select" required>
            <option value="admin" {{ (old('tipo', $user->tipo ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
            <option value="professor" {{ (old('tipo', $user->tipo ?? '') == 'professor') ? 'selected' : '' }}>Professor</option>
            <option value="aluno" {{ (old('tipo', $user->tipo ?? '') == 'aluno') ? 'selected' : '' }}>Aluno</option>
            <option value="responsavel" {{ (old('tipo', $user->tipo ?? '') == 'responsavel') ? 'selected' : '' }}>Responsável</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Senha</label>
        <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>
</div>

@if(isset($user))
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Confirme a nova senha (opcional)</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
    </div>
@endif
