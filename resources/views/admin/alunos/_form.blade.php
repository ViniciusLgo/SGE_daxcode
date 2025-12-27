@csrf
@php($currentTurma = old('turma_id', $aluno->turma_id ?? ''))

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- ================== DADOS DO USUÁRIO ================== --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Nome do Aluno
        </label>
        <input type="text"
               name="user[name]"
               value="{{ old('user.name', $aluno->user->name ?? '') }}"
               required
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20 transition">
        @error('user.name')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">
            E-mail
        </label>
        <input type="email"
               name="user[email]"
               value="{{ old('user.email', $aluno->user->email ?? '') }}"
               required
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20 transition">
        @error('user.email')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ================== DADOS DO ALUNO ================== --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Matrícula
        </label>
        <input type="text"
               name="matricula"
               value="{{ old('matricula', $aluno->matricula ?? '') }}"
               required
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900 px-4 py-2.5">
        @error('matricula')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">
            Telefone
        </label>
        <input type="text"
               name="telefone"
               value="{{ old('telefone', $aluno->telefone ?? '') }}"
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900 px-4 py-2.5">
        @error('telefone')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">
            Data de Nascimento
        </label>
        <input type="date"
               name="data_nascimento"
               value="{{ old('data_nascimento', isset($aluno) && $aluno->data_nascimento ? $aluno->data_nascimento->format('Y-m-d') : '') }}"
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900 px-4 py-2.5">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">
            Turma
        </label>
        <select name="turma_id"
                required
                class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                       bg-white dark:bg-slate-900 px-4 py-2.5">
            <option value="">Selecione a turma</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}"
                    {{ (string)$turma->id === (string)$currentTurma ? 'selected' : '' }}>
                    {{ $turma->nome }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Foto --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Foto de Perfil
        </label>
        <input type="file"
               name="foto_perfil"
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900 px-3 py-2">

        @if(isset($aluno) && $aluno->foto_perfil)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $aluno->foto_perfil) }}"
                     class="rounded-xl w-24 border border-slate-300 dark:border-slate-700">
            </div>
        @endif
    </div>

</div>

{{-- Ações --}}
<div class="mt-8 flex justify-end gap-4">
    <a href="{{ route('admin.alunos.index') }}"
       class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700
              text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
        Cancelar
    </a>

    <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-dax-green text-white font-bold
                   hover:bg-dax-greenSoft transition">
        {{ isset($aluno) ? 'Atualizar Aluno' : 'Salvar Aluno' }}
    </button>
</div>
