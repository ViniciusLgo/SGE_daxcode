@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Documento do Aluno
                </h1>
                <p class="text-slate-500">
                    {{ $documento->aluno->user->name }} ({{ $documento->aluno->user->email }})
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.alunos.documentos.index', $documento->aluno_id) }}"
                   class="px-4 py-2 rounded-xl border
                          border-slate-200 dark:border-slate-800">
                    Voltar
                </a>
                <a href="{{ route('admin.documentos.edit', $documento) }}"
                   class="px-4 py-2 rounded-xl bg-dax-green text-white">
                    Editar
                </a>
            </div>
        </div>

        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><strong>Tipo:</strong> {{ $documento->tipo }}</div>
                <div>
                    <strong>Data de envio:</strong>
                    {{ optional($documento->data_envio)->format('d/m/Y') ?? $documento->created_at->format('d/m/Y') }}
                </div>
                <div class="md:col-span-2">
                    <strong>Observacoes:</strong> {{ $documento->observacoes ?? '' }}
                </div>
                <div class="md:col-span-2">
                    <strong>Arquivo:</strong>
                    <a href="{{ asset('storage/'.$documento->arquivo) }}"
                       target="_blank"
                       class="text-dax-green hover:underline">
                        <i class="bi bi-paperclip"></i> Abrir documento
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
