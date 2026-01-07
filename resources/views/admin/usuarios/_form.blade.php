@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nome --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Nome
        </label>
        <input type="text"
               name="name"
               value="{{ old('name', $user->name ?? '') }}"
               required
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20
                      transition">
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            E-mail
        </label>
        <input type="email"
               name="email"
               value="{{ old('email', $user->email ?? '') }}"
               required
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20
                      transition">
    </div>

    {{-- Tipo --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Tipo de Usuario
        </label>
        <select name="tipo"
                required
                class="w-full rounded-xl
                       border border-slate-300 dark:border-slate-700
                       bg-white dark:bg-slate-900
                       px-4 py-2.5
                       text-slate-800 dark:text-slate-100
                       focus:border-dax-green
                       focus:ring-2 focus:ring-dax-green/20
                       transition">
            <option value="admin" {{ old('tipo', $user->tipo ?? '') == 'admin' ? 'selected' : '' }}>Administrador</option>
            <option value="professor" {{ old('tipo', $user->tipo ?? '') == 'professor' ? 'selected' : '' }}>Professor</option>
            <option value="aluno" {{ old('tipo', $user->tipo ?? '') == 'aluno' ? 'selected' : '' }}>Aluno</option>
            <option value="responsavel" {{ old('tipo', $user->tipo ?? '') == 'responsavel' ? 'selected' : '' }}>Responsavel</option>
        </select>
    </div>

    {{-- Senha --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Nova Senha
            <span class="text-xs text-slate-500">(opcional)</span>
        </label>
        <input type="password"
               name="password"
               placeholder="Deixe em branco para manter a atual"
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20
                      transition">
    </div>

    {{-- Confirmar senha --}}
    <div>
        <label class="block text-sm font-semibold mb-1">
            Confirmar Nova Senha
        </label>
        <input type="password"
               name="password_confirmation"
               placeholder="Confirme a nova senha"
               class="w-full rounded-xl
                      border border-slate-300 dark:border-slate-700
                      bg-white dark:bg-slate-900
                      px-4 py-2.5
                      text-slate-800 dark:text-slate-100
                      focus:border-dax-green
                      focus:ring-2 focus:ring-dax-green/20
                      transition">
    </div>

</div>
