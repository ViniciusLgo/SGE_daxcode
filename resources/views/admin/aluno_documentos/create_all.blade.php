@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    Novo Documento
                </h1>
                <p class="text-slate-500">
                    Envio geral (sem entrar no perfil do aluno).
                </p>
            </div>

            <a href="{{ route('admin.documentos.index') }}"
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
                  action="{{ route('admin.documentos.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- Aluno --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Aluno</label>
                    <select name="aluno_id" required
                            class="w-full rounded-xl border px-4 py-2.5
                                   bg-white dark:bg-dax-dark/60
                                   border-slate-200 dark:border-slate-800">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}"
                                @selected(old('aluno_id') == $aluno->id)>
                                {{ $aluno->user->name ?? '-' }} ({{ $aluno->user->email ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @php($isEdit = false)
                @include('admin.aluno_documentos._form')

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.documentos.index') }}"
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
