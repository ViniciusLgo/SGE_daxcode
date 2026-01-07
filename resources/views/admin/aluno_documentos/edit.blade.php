@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Editar Documento
                </h1>
                <p class="text-slate-500">
                    {{ $documento->aluno->user->name }} ({{ $documento->aluno->user->email }})
                </p>
            </div>

            <a href="{{ route('admin.documentos.show', $documento) }}"
               class="px-4 py-2 rounded-xl border
                      border-slate-200 dark:border-slate-800">
                Voltar
            </a>
        </div>

        <div class="rounded-2xl border bg-white dark:bg-dax-dark/60
                border-slate-200 dark:border-slate-800 p-6">

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
                    <strong>Ops!</strong> Corrija os erros abaixo:
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.documentos.update', $documento) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php($isEdit = true)
                @include('admin.aluno_documentos._form')

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.documentos.show', $documento) }}"
                       class="px-4 py-2 rounded-xl border
                              border-slate-200 dark:border-slate-800">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-dax-green text-white">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
