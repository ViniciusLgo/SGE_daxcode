@csrf

{{-- ========================================================= --}}
{{-- FORMULARIO DE CADASTRO / EDICAO DE TURMA                  --}}
{{-- Reaproveitado em create e edit                            --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 md:grid-cols-6 gap-4">

    {{-- NOME DA TURMA --}}
    <div class="md:col-span-3">
        <label class="block text-sm font-medium mb-1">Nome da Turma *</label>
        <input
            type="text"
            name="nome"
            value="{{ old('nome', $turma->nome ?? '') }}"
            placeholder="Ex: T1 2025 Manha"
            required
            class="w-full rounded-xl border px-4 py-2.5
                   bg-white dark:bg-dax-dark/60
                   border-slate-200 dark:border-slate-800
                   @error('nome') border-red-500 @enderror"
        >
        @error('nome')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- TURNO --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Turno</label>
        <select
            name="turno"
            class="w-full rounded-xl border px-4 py-2.5
                   bg-white dark:bg-dax-dark/60
                   border-slate-200 dark:border-slate-800
                   @error('turno') border-red-500 @enderror"
        >
            <option value="">Selecione...</option>
            <option value="Matutino" {{ old('turno', $turma->turno ?? '') === 'Matutino' ? 'selected' : '' }}>Matutino</option>
            <option value="Vespertino" {{ old('turno', $turma->turno ?? '') === 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
            <option value="Noturno" {{ old('turno', $turma->turno ?? '') === 'Noturno' ? 'selected' : '' }}>Noturno</option>
        </select>
        @error('turno')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ANO LETIVO --}}
    <div class="md:col-span-1">
        <label class="block text-sm font-medium mb-1">Ano Letivo</label>
        <input
            type="number"
            name="ano"
            min="2000"
            max="2100"
            placeholder="2025"
            value="{{ old('ano', $turma->ano ?? '') }}"
            class="w-full rounded-xl border px-4 py-2.5
                   bg-white dark:bg-dax-dark/60
                   border-slate-200 dark:border-slate-800
                   @error('ano') border-red-500 @enderror"
        >
        @error('ano')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- DESCRICAO --}}
    <div class="md:col-span-6">
        <label class="block text-sm font-medium mb-1">Descricao da Turma</label>
        <textarea
            name="descricao"
            rows="3"
            placeholder="Informacoes adicionais sobre a turma (opcional)"
            class="w-full rounded-xl border px-4 py-2.5
                   bg-white dark:bg-dax-dark/60
                   border-slate-200 dark:border-slate-800
                   @error('descricao') border-red-500 @enderror"
        >{{ old('descricao', $turma->descricao ?? '') }}</textarea>
        @error('descricao')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>

{{-- BOTOES --}}
<div class="flex justify-end gap-2 mt-6">
    <a href="{{ route('admin.turmas.index') }}"
       class="px-4 py-2 rounded-xl border
              border-slate-200 dark:border-slate-800">
        Cancelar
    </a>

    <button type="submit"
            class="px-4 py-2 rounded-xl bg-dax-green text-white">
        {{ isset($turma) ? 'Atualizar Turma' : 'Salvar Turma' }}
    </button>
</div>
