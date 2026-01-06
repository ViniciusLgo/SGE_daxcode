{{-- Nome --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Nome da Disciplina</label>
    <input type="text" name="nome" required
           value="{{ old('nome', $disciplina->nome ?? '') }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Carga Horaria --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Carga Horaria (h)</label>
    <input type="number" name="carga_horaria" min="1" max="500" required
           value="{{ old('carga_horaria', $disciplina->carga_horaria ?? '') }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Descricao --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Descricao</label>
    <textarea name="descricao" rows="3"
              class="w-full rounded-xl border px-4 py-2.5
                     bg-white dark:bg-dax-dark/60
                     border-slate-200 dark:border-slate-800">{{ old('descricao', $disciplina->descricao ?? '') }}</textarea>
</div>

{{-- Professores --}}
<div class="mb-6">
    <label class="block text-sm font-medium mb-1">Professores</label>
    <select name="professores[]" multiple required
            class="w-full rounded-xl border px-4 py-2.5
                   bg-white dark:bg-dax-dark/60
                   border-slate-200 dark:border-slate-800">
        @foreach(($professores ?? []) as $id => $nome)
            <option value="{{ $id }}"
                @selected(in_array($id, old('professores', $professoresSelecionados ?? [])))>
                {{ $nome }}
            </option>
        @endforeach
    </select>
    <p class="text-xs text-slate-500 mt-1">
        Segure Ctrl (ou Cmd) para selecionar varios.
    </p>
</div>

{{-- Botoes --}}
<div class="flex justify-between items-center">
    <a href="{{ route('admin.disciplinas.index') }}"
       class="px-4 py-2 rounded-xl border
              border-slate-200 dark:border-slate-800">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

    <button type="submit"
            class="px-4 py-2 rounded-xl bg-dax-green text-white">
        <i class="bi bi-save"></i> Salvar
    </button>
</div>
