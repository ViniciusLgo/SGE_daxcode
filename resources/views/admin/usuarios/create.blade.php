@extends('layouts.app')

@section('content')

    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
            <i class="bi bi-person-plus-fill text-dax-green"></i>
            Cadastrar Novo Usuário
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Preencha as informações abaixo para criar um novo acesso.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            rounded-2xl shadow-sm">

        <div class="p-6">

            {{-- Erros de validação --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20
                        border border-red-200 dark:border-red-800
                        px-4 py-3 text-red-700 dark:text-red-300">
                    <strong class="block mb-1">Ops! Corrija os erros abaixo:</strong>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nome --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Nome
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Nome completo"
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
                               value="{{ old('email') }}"
                               placeholder="exemplo@email.com"
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

                    {{-- Senha --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Senha
                        </label>
                        <input type="password"
                               name="password"
                               placeholder="Mínimo 6 caracteres"
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

                    {{-- Confirmar Senha --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Confirmar Senha
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               placeholder="Repita a senha"
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
                            Tipo de Usuário
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
                            <option value="" disabled selected>Selecione...</option>
                            <option value="admin">Administrador</option>
                            <option value="professor">Professor</option>
                            <option value="aluno">Aluno</option>
                            <option value="responsavel">Responsável</option>
                        </select>
                    </div>
                </div>

                {{-- Ações --}}
                <div class="mt-8 flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center gap-2
                               px-6 py-2.5 rounded-xl
                               bg-dax-green text-white font-bold
                               hover:bg-dax-greenSoft transition">
                        <i class="bi bi-save"></i>
                        Salvar
                    </button>

                    <a href="{{ route('admin.usuarios.index') }}"
                       class="inline-flex items-center gap-2
                          px-5 py-2.5 rounded-xl
                          border border-slate-300 dark:border-slate-700
                          text-slate-700 dark:text-slate-300
                          hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <i class="bi bi-arrow-left"></i>
                        Voltar
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection
