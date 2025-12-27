@extends('layouts.app')

@section('content')

    {{-- Cabeçalho --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light flex items-center gap-2">
            <i class="bi bi-people-fill text-dax-green"></i>
            Usuários do Sistema
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Gerencie os acessos e perfis dos usuários.
        </p>
    </div>

    {{-- Card principal --}}
    <div class="bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            rounded-2xl shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4
                border-b border-slate-200 dark:border-slate-800">

        <span class="font-bold text-dax-dark dark:text-dax-light">
            Lista de Usuários
        </span>

            <a href="{{ route('admin.usuarios.create') }}"
               class="inline-flex items-center gap-2
                  px-4 py-2 rounded-xl
                  bg-dax-green text-white font-bold
                  hover:bg-dax-greenSoft transition">
                <i class="bi bi-plus-circle"></i>
                Novo Usuário
            </a>
        </div>

        {{-- Corpo --}}
        <div class="p-6">

            {{-- Mensagem de sucesso --}}
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700
                        border border-emerald-200 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabela --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="pb-3">Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th class="text-right">Ações</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($usuarios as $u)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="py-3 font-semibold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>

                            <td>
                            <span class="inline-flex px-3 py-1 rounded-full
                                         text-xs font-bold
                                         bg-slate-100 dark:bg-slate-800
                                         text-slate-700 dark:text-slate-300">
                                {{ $u->tipo_label }}
                            </span>
                            </td>

                            <td class="text-right space-x-3">
                                <a href="{{ route('admin.usuarios.edit', $u->id) }}"
                                   class="font-semibold text-dax-green hover:underline">
                                    Editar
                                </a>

                                <form action="{{ route('admin.usuarios.destroy', $u->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-red-600 hover:underline">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
