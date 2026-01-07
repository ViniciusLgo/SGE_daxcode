{{-- Tipo --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Tipo do Documento</label>
    <input type="text" name="tipo" list="tipos_documento" required
           value="{{ old('tipo', $documento->tipo ?? '') }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">

    <datalist id="tipos_documento">
        @foreach($tipos as $tipo)
            <option value="{{ $tipo }}"></option>
        @endforeach
    </datalist>
</div>

{{-- Data de envio --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Data de envio</label>
    <input type="date" name="data_envio"
           value="{{ old('data_envio', isset($documento->data_envio) ? \Carbon\Carbon::parse($documento->data_envio)->format('Y-m-d') : '') }}"
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
</div>

{{-- Arquivo --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Arquivo (PDF/JPG/PNG)</label>
    <input type="file" name="arquivo" {{ $isEdit ? '' : 'required' }}
           class="w-full rounded-xl border px-4 py-2.5
                  bg-white dark:bg-dax-dark/60
                  border-slate-200 dark:border-slate-800">
    @if($isEdit && !empty($documento->arquivo))
        <p class="text-xs text-slate-500 mt-1">
            Arquivo atual: {{ basename($documento->arquivo) }}
        </p>
    @endif
</div>

{{-- Observacoes --}}
<div class="mb-4">
    <label class="block text-sm font-medium mb-1">Observacoes</label>
    <textarea name="observacoes" rows="3"
              class="w-full rounded-xl border px-4 py-2.5
                     bg-white dark:bg-dax-dark/60
                     border-slate-200 dark:border-slate-800">{{ old('observacoes', $documento->observacoes ?? '') }}</textarea>
</div>

