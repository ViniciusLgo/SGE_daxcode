@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Documentos do Aluno
                </h1>
                <p class="text-slate-500">
                    {{ $aluno->user->name }} ({{ $aluno->user->email }})
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.alunos.show', $aluno) }}"
                   class="px-4 py-2 rounded-xl border
                          border-slate-200 dark:border-slate-800">
                    Voltar
                </a>
                <a href="{{ route('admin.alunos.documentos.create', $aluno) }}"
                   class="px-4 py-2 rounded-xl bg-dax-green text-white">
                    Novo Documento
                </a>
            </div>
        </div>

        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">
            @if($documentos->isEmpty())
                <p class="text-slate-500">Nenhum documento cadastrado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-slate-500">
                        <tr>
                            <th class="py-2 text-left">Tipo</th>
                            <th class="py-2 text-left">Arquivo</th>
                            <th class="py-2 text-left">Data</th>
                            <th class="py-2 text-left">Observacoes</th>
                            <th class="py-2 text-right">Acoes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documentos as $doc)
                            <tr class="border-t border-slate-200 dark:border-slate-800">
                                <td class="py-2">{{ $doc->tipo }}</td>
                                <td class="py-2">
                                    <a href="{{ asset('storage/'.$doc->arquivo) }}"
                                       target="_blank"
                                       class="text-dax-green hover:underline">
                                        <i class="bi bi-paperclip"></i> Abrir
                                    </a>
                                </td>
                                <td class="py-2">
                                    {{ optional($doc->data_envio)->format('d/m/Y') ?? $doc->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-2">{{ $doc->observacoes ?? '' }}</td>
                                <td class="py-2 text-right space-x-2">
                                    <a href="{{ route('admin.documentos.show', $doc) }}"
                                       class="text-dax-green hover:underline">
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.documentos.edit', $doc) }}"
                                       class="text-sky-600 hover:underline">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.documentos.destroy', $doc) }}"
                                          method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:underline"
                                                onclick="return confirm('Deseja excluir este documento?')">
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
