<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-8">Crie sua conta ✨</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nome completo</label>
            <input id="name" name="name" type="text" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
            <input id="email" name="email" type="email" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Senha</label>
                <input id="password" name="password" type="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar senha</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
            </div>
        </div>

        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            Criar Conta
        </button>

        <p class="text-center text-sm text-gray-600 mt-6">
            Já possui conta?
            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">
                Entrar
            </a>
        </p>
    </form>
</x-guest-layout>
