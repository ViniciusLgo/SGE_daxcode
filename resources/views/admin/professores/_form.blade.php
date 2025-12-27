{{-- Nome --}}
<div>
    <label class="block text-sm font-medium mb-1">Nome</label>
    <input type="text" name="nome" required
           value="{{ old('nome', optional($professor->user ?? null)->name) }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- E-mail --}}
<div>
    <label class="block text-sm font-medium mb-1">E-mail</label>
    <input type="email" name="email" required
           value="{{ old('email', optional($professor->user ?? null)->email) }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Telefone --}}
<div>
    <label class="block text-sm font-medium mb-1">Telefone</label>
    <input type="text" name="telefone"
           value="{{ old('telefone', $professor->telefone ?? '') }}"
           placeholder="(00) 00000-0000"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Especialização --}}
<div>
    <label class="block text-sm font-medium mb-1">Especialização</label>
    <input type="text" name="especializacao"
           value="{{ old('especializacao', $professor->especializacao ?? '') }}"
           placeholder="Ex: Matemática, História..."
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Foto --}}
<div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Foto de Perfil</label>
    <input type="file" name="foto_perfil"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">

    @if(!empty($professor->foto_perfil))
        <div class="mt-3">
            <img src="{{ asset('storage/' . $professor->foto_perfil) }}"
                 alt="Foto do Professor"
                 class="w-20 h-20 rounded-xl border
                        border-slate-200 dark:border-slate-800">
        </div>
    @endif
</div>

{{-- Botões --}}
<div class="md:col-span-2 flex justify-end gap-2 pt-4">
    <a href="{{ route('admin.professores.index') }}"
       class="px-4 py-2 rounded-xl border
              border-slate-200 dark:border-slate-800">
        <i class="bi bi-x-circle"></i> Cancelar
    </a>

    <button type="submit"
            class="px-4 py-2 rounded-xl bg-dax-green text-white">
        <i class="bi bi-save"></i> Salvar
    </button>
</div>
