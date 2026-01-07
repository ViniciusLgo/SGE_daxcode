<div class="rounded-2xl border bg-white dark:bg-dax-dark/60
            border-slate-200 dark:border-slate-800 p-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Nome --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nome *</label>
            <input type="text" name="name" required
                   value="{{ old('name', $responsavel->user->name ?? '') }}"
                   class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
        </div>

        {{-- E-mail --}}
        <div>
            <label class="block text-sm font-medium mb-1">E-mail *</label>
            <input type="email" name="email" required
                   value="{{ old('email', $responsavel->user->email ?? '') }}"
                   class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
        </div>

        {{-- Senha (somente no create) --}}
        @if(!isset($responsavel))
            <div>
                <label class="block text-sm font-medium mb-1">Senha *</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border px-4 py-2.5
                              bg-white dark:bg-dax-dark/60
                              border-slate-200 dark:border-slate-800">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Confirmar Senha *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-xl border px-4 py-2.5
                              bg-white dark:bg-dax-dark/60
                              border-slate-200 dark:border-slate-800">
            </div>
        @endif

        {{-- Telefone --}}
        <div>
            <label class="block text-sm font-medium mb-1">Telefone</label>
            <input type="text" name="telefone"
                   value="{{ old('telefone', $responsavel->telefone ?? '') }}"
                   class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
        </div>

        {{-- CPF --}}
        <div>
            <label class="block text-sm font-medium mb-1">CPF</label>
            <input type="text" name="cpf"
                   value="{{ old('cpf', $responsavel->cpf ?? '') }}"
                   class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
        </div>

        {{-- Grau de Parentesco --}}
        <div>
            <label class="block text-sm font-medium mb-1">Grau de Parentesco</label>
            <input type="text" name="grau_parentesco"
                   value="{{ old('grau_parentesco', $responsavel->grau_parentesco ?? '') }}"
                   class="w-full rounded-xl border px-4 py-2.5
                          bg-white dark:bg-dax-dark/60
                          border-slate-200 dark:border-slate-800">
        </div>

        {{-- Alunos --}}
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Vincular Alunos</label>
            <select name="alunos[]" multiple
                    class="w-full rounded-xl border px-4 py-2.5
                           bg-white dark:bg-dax-dark/60
                           border-slate-200 dark:border-slate-800">
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}"
                        @selected(isset($responsavel) && $responsavel->alunos->contains($aluno->id))>
                        {{ $aluno->user->name }}  {{ $aluno->turma->nome ?? 'Sem turma' }}
                    </option>
                @endforeach
            </select>

            <p class="text-xs text-slate-500 mt-1">
                Segure CTRL para selecionar multiplos alunos.
            </p>
        </div>
    </div>

    {{-- Botoes --}}
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('admin.responsaveis.index') }}"
           class="px-4 py-2 rounded-xl border
                  border-slate-200 dark:border-slate-800">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>

        <button type="submit"
                class="px-4 py-2 rounded-xl bg-dax-green text-white">
            <i class="bi bi-save"></i> Salvar
        </button>
    </div>

</div>
