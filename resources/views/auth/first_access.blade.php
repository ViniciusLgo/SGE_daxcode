<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Trocar Senha</h2>

    <p class="text-center text-gray-600 mb-4">
        Olá <strong>{{ auth()->user()->name }}</strong>, por segurança, defina uma nova senha antes de continuar.
    </p>

    <form method="POST" action="{{ route('auth.first_access.update') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">Nova Senha</label>
            <input id="password" name="password" type="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirmar Senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
        </div>

        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            Salvar e Continuar
        </button>
    </form>
</x-guest-layout>
