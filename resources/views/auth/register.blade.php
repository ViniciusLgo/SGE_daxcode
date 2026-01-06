<x-guest-layout>

    {{-- Cabecalho --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-dax-dark">
            Primeiro Acesso
        </h1>
        <p class="text-slate-500 mt-1">
            Cadastro inicial no sistema
        </p>
    </div>

    {{-- Form --}}
    <form method="POST"
          action="{{ route('register') }}"
          class="space-y-6">
        @csrf

        {{-- Nome --}}
        <div>
            <label for="name"
                   class="block text-sm font-semibold text-slate-700 mb-1">
                Nome completo
            </label>

            <input id="name"
                   name="name"
                   type="text"
                   required
                   autofocus
                   class="w-full rounded-md
                          border border-slate-300
                          px-4 py-2.5
                          text-slate-800
                          focus:border-dax-green
                          focus:ring-2 focus:ring-dax-green/20
                          transition">
        </div>

        {{-- E-mail --}}
        <div>
            <label for="email"
                   class="block text-sm font-semibold text-slate-700 mb-1">
                E-mail
            </label>

            <input id="email"
                   name="email"
                   type="email"
                   required
                   class="w-full rounded-md
                          border border-slate-300
                          px-4 py-2.5
                          text-slate-800
                          focus:border-dax-green
                          focus:ring-2 focus:ring-dax-green/20
                          transition">
        </div>

        {{-- Senhas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password"
                       class="block text-sm font-semibold text-slate-700 mb-1">
                    Senha
                </label>

                <input id="password"
                       name="password"
                       type="password"
                       required
                       class="w-full rounded-md
                              border border-slate-300
                              px-4 py-2.5
                              text-slate-800
                              focus:border-dax-green
                              focus:ring-2 focus:ring-dax-green/20
                              transition">
            </div>

            <div>
                <label for="password_confirmation"
                       class="block text-sm font-semibold text-slate-700 mb-1">
                    Confirmar senha
                </label>

                <input id="password_confirmation"
                       name="password_confirmation"
                       type="password"
                       required
                       class="w-full rounded-md
                              border border-slate-300
                              px-4 py-2.5
                              text-slate-800
                              focus:border-dax-green
                              focus:ring-2 focus:ring-dax-green/20
                              transition">
            </div>
        </div>

        {{-- Botao --}}
        <button type="submit"
                class="w-full py-3
                       bg-dax-green hover:bg-dax-greenSoft
                       text-white font-semibold
                       rounded-md
                       shadow-sm
                       transition">
            Criar conta
        </button>

        {{-- Login --}}
        <p class="text-center text-sm text-slate-600 mt-6">
            Ja possui conta?
            <a href="{{ route('login') }}"
               class="text-dax-green font-semibold hover:underline">
                Entrar no sistema
            </a>
        </p>
    </form>

</x-guest-layout>
