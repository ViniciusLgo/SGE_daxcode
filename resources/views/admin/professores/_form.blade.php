@csrf

{{-- Nome --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Nome</label>
    <input type="text" name="nome" class="form-control shadow-sm"
           value="{{ old('nome', optional($professor->user ?? null)->name) }}" required>
</div>

{{-- E-mail --}}
<div class="mb-3">
    <label class="form-label fw-semibold">E-mail</label>
    <input type="email" name="email" class="form-control shadow-sm"
           value="{{ old('email', optional($professor->user ?? null)->email) }}" required>
</div>

{{-- Telefone --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Telefone</label>
    <input type="text" name="telefone" class="form-control shadow-sm"
           value="{{ old('telefone', $professor->telefone ?? '') }}">
</div>

{{-- Especialização --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Especialização</label>
    <input type="text" name="especializacao" class="form-control shadow-sm"
           value="{{ old('especializacao', $professor->especializacao ?? '') }}">
</div>

{{-- Foto de perfil --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Foto de Perfil</label>
    <input type="file" name="foto_perfil" class="form-control">
    @if(!empty($professor->foto_perfil))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $professor->foto_perfil) }}" alt="Foto"
                 width="80" height="80" class="rounded border">
        </div>
    @endif
</div>

{{-- Botões --}}
<div class="text-end mt-3">
    <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Cancelar
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Salvar
    </button>
</div>
