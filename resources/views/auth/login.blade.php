<x-guest-layout>

    {{-- Cabeçalho --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-dax-dark">
            Sistema de Gestão Educacional
        </h1>
        <p class="text-slate-500 mt-1">
            Acesso ao painel do sistema
        </p>
    </div>

    {{-- Status --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-dax-green text-center">
            {{ session('status') }}
        </div>
    @endif

    {{-- Form --}}
    <form method="POST"
          action="{{ route('login') }}"
          class="space-y-6">
        @csrf

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
                   autofocus
                   class="w-full rounded-md
                          border border-slate-300
                          px-4 py-2.5
                          text-slate-800
                          focus:border-dax-green
                          focus:ring-2 focus:ring-dax-green/20
                          transition">
        </div>

        {{-- Senha --}}
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

        {{-- Lembrar / Esqueci --}}
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center text-slate-600">
                <input type="checkbox"
                       name="remember"
                       class="rounded border-slate-300
                              text-dax-green
                              focus:ring-dax-green">
                <span class="ml-2">Manter sessão ativa</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-dax-green font-semibold hover:underline">
                    Esqueci a senha
                </a>
            @endif
        </div>

        {{-- Botão --}}
        <button type="submit"
                class="w-full py-3
                       bg-dax-green hover:bg-dax-greenSoft
                       text-white font-semibold
                       rounded-md
                       shadow-sm
                       transition">
            Entrar
        </button>

        {{-- Registro --}}
        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-600 mt-6">
                Primeiro acesso?
                <a href="{{ route('register') }}"
                   class="text-dax-green font-semibold hover:underline">
                    Criar conta
                </a>
            </p>
        @endif
    </form>

</x-guest-layout>
