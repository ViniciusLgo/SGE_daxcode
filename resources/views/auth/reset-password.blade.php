<x-guest-layout>

    {{-- Cabecalho --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-dax-dark">
            Redefinir senha
        </h1>
        <p class="text-slate-500 mt-1">
            Crie uma nova senha para acessar o sistema
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="E-mail" class="text-slate-700 font-semibold" />

            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email', $request->email)"
                required
                autofocus
                autocomplete="username"
                class="mt-1 block w-full
                       border border-slate-300
                       text-slate-800
                       focus:border-dax-green
                       focus:ring-2 focus:ring-dax-green/20
                       transition" />

            <x-input-error :messages="$errors->get('email')" class="mt-2 text-dax-green" />
        </div>

        {{-- Nova senha --}}
        <div>
            <x-input-label for="password" value="Nova senha" class="text-slate-700 font-semibold" />

            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="mt-1 block w-full
                       border border-slate-300
                       text-slate-800
                       focus:border-dax-green
                       focus:ring-2 focus:ring-dax-green/20
                       transition" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-dax-green" />
        </div>

        {{-- Confirmar senha --}}
        <div>
            <x-input-label for="password_confirmation" value="Confirmar nova senha" class="text-slate-700 font-semibold" />

            <x-text-input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="mt-1 block w-full
                       border border-slate-300
                       text-slate-800
                       focus:border-dax-green
                       focus:ring-2 focus:ring-dax-green/20
                       transition" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-dax-green" />
        </div>

        {{-- Botao --}}
        <button type="submit"
                class="w-full py-3
                       bg-dax-green hover:bg-dax-greenSoft
                       text-white font-semibold
                       rounded-md
                       shadow-sm
                       transition">
            Redefinir senha
        </button>
    </form>

</x-guest-layout>
