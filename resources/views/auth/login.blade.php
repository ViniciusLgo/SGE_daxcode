<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-8">Bem-vindo de volta 👋</h2>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="bi bi-envelope-fill me-1 text-indigo-600"></i> E-mail
            </label>
            <input id="email" name="email" type="email" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="bi bi-lock-fill me-1 text-indigo-600"></i> Senha
            </label>
            <input id="password" name="password" type="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <div class="flex items-center justify-between text-sm text-gray-600">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2">Lembrar-me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline font-semibold">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            Entrar no Sistema
        </button>

        <p class="text-center text-sm text-gray-600 mt-6">
            Ainda não tem conta?
            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">
                Cadastre-se
            </a>
        </p>
    </form>
</x-guest-layout>
