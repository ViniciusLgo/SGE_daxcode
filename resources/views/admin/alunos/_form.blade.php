@csrf

{{-- ============================================================
| CONTEXTO
| - $user  -> quando o aluno vem de um usuário já criado
| - $aluno -> quando estamos editando um aluno existente
============================================================ --}}

@php
    $currentTurma = old('turma_id', $aluno->turma_id ?? '');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- ============================================================
    | DADOS DO USUÁRIO (IDENTIDADE)
    | Esses dados vêm do User e NÃO devem ser editados aqui
    | Servem apenas para visualização e vínculo
    ============================================================ --}}
    @if(isset($user))
        <div>
            <label class="block text-sm font-semibold mb-1">
                Nome do Aluno
            </label>
            <input type="text"
                   value="{{ $user->name }}"
                   disabled
                   class="w-full rounded-xl
                          border border-slate-300 dark:border-slate-700
                          bg-slate-100 dark:bg-slate-800
                          px-4 py-2.5
                          text-slate-700 dark:text-slate-300">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">
                E-mail
            </label>
            <input type="email"
                   value="{{ $user->email }}"
                   disabled
                   class="w-full rounded-xl
                          border border-slate-300 dark:border-slate-700
                          bg-slate-100 dark:bg-slate-800
                          px-4 py-2.5
                          text-slate-700 dark:text-slate-300">
        </div>

        {{-- User ID real que será usado no store --}}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

    @elseif(isset($aluno))
        {{-- ============================================================
        | CENÁRIO DE EDIÇÃO
        | Nome e e-mail vêm do relacionamento aluno -> user
        ============================================================ --}}
        <div>
            <label class="block text-sm font-semibold mb-1">
                Nome do Aluno
            </label>
            <input type="text"
                   value="{{ $aluno->user->name }}"
                   disabled
                   class="w-full rounded-xl
                          border border-slate-300 dark:border-slate-700
                          bg-slate-100 dark:bg-slate-800
                          px-4 py-2.5">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">
                E-mail
            </label>
            <input type="email"
                   value="{{ $aluno->user->email }}"
                   disabled
                   class="w-full rounded-xl
                          border border-slate-300 dark:border-slate-700
                          bg-slate-100 dark:bg-slate-800
                          px-4 py-2.5">
        </div>
    @endif

    {{-- ============================================================
    | DADOS ACADÊMICOS DO ALUNO
    | Esses campos pertencem exclusivamente à entidade Aluno
    ============================================================ --}}
    @if(isset($aluno))
        <div>
            <label class="block text-sm font-semibold mb-1">
                Matrícula
            </label>
            <input type="text"
                   value="{{ $aluno->matricula }}"
                   disabled
                   class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-slate-100 dark:bg-slate-800
                      px-4 py-2.5
                      text-slate-700 dark:text-slate-300">
        </div>
    @endif

    {{-- Telefone --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Telefone
        </label>
        <input type="text"
               name="telefone"
               value="{{ old('telefone', $aluno->telefone ?? '') }}"
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5">
        @error('telefone')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Data de Nascimento --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Data de Nascimento
        </label>
        <input type="date"
               name="data_nascimento"
               value="{{ old(
                    'data_nascimento',
                    isset($aluno) && $aluno->data_nascimento
                        ? $aluno->data_nascimento->format('Y-m-d')
                        : ''
               ) }}"
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5">
    </div>

    {{-- Turma --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Turma
        </label>
        <select name="turma_id"
                required
                class="w-full rounded-xl
                       border border-slate-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       px-4 py-2.5">
            <option value="">Selecione a turma</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}"
                    {{ (string)$turma->id === (string)$currentTurma ? 'selected' : '' }}>
                    {{ $turma->nome }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Foto de Perfil --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Foto de Perfil
        </label>
        <input type="file"
               name="foto_perfil"
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-3 py-2">

        @if(isset($aluno) && $aluno->foto_perfil)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $aluno->foto_perfil) }}"
                     class="rounded-xl w-24 border border-slate-300 dark:border-slate-700">
            </div>
        @endif
    </div>

</div>

{{-- ============================================================
| AÇÕES DO FORMULÁRIO
============================================================ --}}
<div class="mt-8 flex justify-end gap-4">
    <a href="{{ route('admin.alunos.index') }}"
       class="px-5 py-2.5 rounded-xl
              border border-slate-300 dark:border-slate-700
              text-slate-700 dark:text-slate-300
              hover:bg-slate-100 dark:hover:bg-slate-800">
        Cancelar
    </a>

    <button type="submit"
            class="px-6 py-2.5 rounded-xl
                   bg-dax-green text-white font-bold
                   hover:bg-dax-greenSoft transition">
        {{ isset($aluno) ? 'Atualizar Aluno' : 'Salvar Aluno' }}
    </button>
</div>
