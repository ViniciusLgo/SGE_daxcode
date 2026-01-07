@extends('layouts.app')

@section('content')

    {{-- CABECALHO --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black flex items-center gap-2 text-dax-dark dark:text-dax-light">
                <i class="bi bi-person-vcard"></i> Ficha Completa do Aluno
            </h1>
            <p class="text-slate-500 dark:text-slate-400">
                Informacoes gerais, responsaveis, documentos e registros vinculados.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.boletim.aluno', $aluno) }}"
               class="px-4 py-2 rounded-xl border border-dax-green
                  text-dax-green font-semibold hover:bg-dax-green hover:text-white transition">
                 Ver Boletim
            </a>

            <a href="{{ route('admin.alunos.edit', $aluno->id) }}"
               class="px-4 py-2 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 transition">
                <i class="bi bi-pencil-square"></i> Editar
            </a>

            <a href="{{ route('admin.alunos.index') }}"
               class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    {{-- DADOS GERAIS --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm p-6 mb-6">

        <div class="flex flex-col md:flex-row gap-6 items-center">

            {{-- FOTO / IDENTIDADE --}}
            <div class="text-center w-full md:w-1/4">
                @if($aluno->foto_perfil)
                    <img src="{{ asset('storage/' . $aluno->foto_perfil) }}"
                         class="w-28 h-28 rounded-full object-cover border mx-auto shadow">
                @else
                    <i class="bi bi-person-circle text-7xl text-slate-400"></i>
                @endif

                <h2 class="mt-3 font-black text-lg text-dax-dark dark:text-dax-light">
                    {{ $aluno->user->name }}
                </h2>

                <span class="inline-block mt-1 px-3 py-1 text-xs rounded-full
                         bg-dax-green/10 text-dax-green font-bold">
                {{ $aluno->turma->nome ?? 'Sem turma atribuida' }}
            </span>
            </div>

            {{-- INFORMACOES --}}
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><strong>Matricula:</strong> {{ $aluno->matricula ?? '' }}</div>
                    <div><strong>E-mail:</strong> {{ $aluno->user->email }}</div>
                    <div><strong>Telefone:</strong> {{ $aluno->telefone ?? '' }}</div>
                    <div>
                        <strong>Data de Nascimento:</strong>
                        {{ $aluno->data_nascimento ? \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') : '' }}
                    </div>
                    <div>
                        <strong>Data de Cadastro:</strong>
                        {{ $aluno->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- RESPONSAVEIS --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm mb-6">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800
                flex justify-between items-center">
            <h3 class="font-black flex items-center gap-2">
                <i class="bi bi-people-fill"></i> Responsaveis
            </h3>

            <a href="{{ route('admin.alunos.edit', $aluno->id) }}"
               class="text-sm font-semibold text-dax-green hover:underline">
                Gerenciar
            </a>
        </div>

        <div class="p-6">
            @if($aluno->responsaveis->isEmpty())
                <p class="text-slate-500">Nenhum responsavel vinculado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-500">
                        <tr>
                            <th class="py-2 text-left">Nome</th>
                            <th class="py-2 text-left">Parentesco</th>
                            <th class="py-2 text-left">Telefone</th>
                            <th class="py-2 text-left">Email</th>
                            <th class="py-2 text-right">Acoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->responsaveis as $resp)
                            <tr class="border-t border-slate-200 dark:border-slate-800">
                                <td class="py-2">{{ $resp->nome }}</td>
                                <td class="py-2">{{ $resp->grau_parentesco ?? '' }}</td>
                                <td class="py-2">{{ $resp->telefone ?? '' }}</td>
                                <td class="py-2">{{ $resp->email ?? '' }}</td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.responsaveis.show', $resp->id) }}"
                                       class="text-dax-green hover:underline">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- DOCUMENTOS --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm mb-6">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="font-black flex items-center gap-2">
                <i class="bi bi-folder2"></i> Documentos
            </h3>
        </div>

        <div class="p-6">
            @if($aluno->documentos->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-500">
                        <tr>
                            <th class="py-2 text-left">Tipo</th>
                            <th class="py-2 text-left">Arquivo</th>
                            <th class="py-2 text-left">Data</th>
                            <th class="py-2 text-left">Observacoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->documentos as $doc)
                            <tr class="border-t border-slate-200 dark:border-slate-800">
                                <td class="py-2">{{ $doc->tipo }}</td>
                                <td class="py-2">
                                    <a href="{{ asset('storage/'.$doc->arquivo) }}"
                                       target="_blank"
                                       class="text-dax-green hover:underline">
                                        <i class="bi bi-paperclip"></i> Abrir
                                    </a>
                                </td>
                                <td class="py-2">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2">{{ $doc->observacoes ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-slate-500">Nenhum documento enviado.</p>
            @endif
        </div>
    </div>

    {{-- REGISTROS --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
            border border-slate-200 dark:border-slate-800
            shadow-sm">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800
                flex justify-between items-center">
            <h3 class="font-black flex items-center gap-2">
                <i class="bi bi-journal-text"></i> Registros do Aluno
            </h3>

            <a href="{{ route('admin.aluno_registros.create', ['aluno_id' => $aluno->id]) }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white font-bold hover:bg-dax-greenSoft transition">
                <i class="bi bi-plus"></i> Novo Registro
            </a>
        </div>

        <div class="p-6">
            @if($aluno->registros->isEmpty())
                <p class="text-slate-500">Nenhum registro encontrado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-500">
                        <tr>
                            <th class="py-2 text-left">Tipo</th>
                            <th class="py-2 text-left">Categoria</th>
                            <th class="py-2 text-left">Data</th>
                            <th class="py-2 text-right">Acoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->registros as $registro)
                            <tr class="border-t border-slate-200 dark:border-slate-800">
                                <td class="py-2">{{ $registro->tipo }}</td>
                                <td class="py-2">{{ $registro->categoria ?? '' }}</td>
                                <td class="py-2">{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2 text-right space-x-2">
                                    <a href="{{ route('admin.aluno_registros.show', $registro->id) }}"
                                       class="text-dax-green hover:underline">
                                        Ver
                                    </a>

                                    <form action="{{ route('admin.aluno_registros.destroy', $registro->id) }}"
                                          method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:underline"
                                                onclick="return confirm('Deseja excluir este registro?')">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
