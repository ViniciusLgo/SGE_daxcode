@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                📋 Detalhes do Registro
            </h1>
            <p class="text-sm text-slate-500">
                Informações completas do documento e do aluno.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.aluno_registros.index') }}"
               class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                      text-dax-dark dark:text-dax-light hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                ← Voltar
            </a>

            <a href="{{ route('admin.aluno_registros.edit', $aluno_registro->id) }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
                ✏️ Editar
            </a>
        </div>
    </div>

    @include('partials.alerts')

    {{-- ================= CARD PRINCIPAL ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6">

        {{-- ================= CABEÇALHO DO REGISTRO ================= --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>
                <h2 class="text-xl font-black text-dax-dark dark:text-dax-light">
                    {{ $aluno_registro->tipo }}
                </h2>

                {{-- STATUS --}}
                @php
                    $statusClasses = match($aluno_registro->status) {
                        'pendente'  => 'bg-yellow-100 text-yellow-800',
                        'validado'  => 'bg-green-100 text-green-800',
                        'arquivado' => 'bg-slate-200 text-slate-700',
                        default     => 'bg-red-100 text-red-800',
                    };
                @endphp

                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                    {{ ucfirst($aluno_registro->status) }}
                </span>
            </div>
        </div>

        {{-- ================= DADOS DO ALUNO ================= --}}
        <div class="mb-8">
            <h3 class="font-semibold text-dax-dark dark:text-dax-light mb-4">
                👨‍🎓 Dados do Aluno
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <p class="text-sm text-slate-500">Nome</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->aluno->user->name ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Turma</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->turma->nome ?? 'Sem turma' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Responsável pelo Registro</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->responsavel->name ?? '—' }}
                    </p>
                </div>

            </div>
        </div>

        {{-- ================= DADOS DO REGISTRO ================= --}}
        <div class="mb-8">
            <h3 class="font-semibold text-dax-dark dark:text-dax-light mb-4">
                📝 Informações do Registro
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                <div>
                    <p class="text-sm text-slate-500">Categoria</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->categoria ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Data do Evento</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->data_evento
                            ? \Carbon\Carbon::parse($aluno_registro->data_evento)->format('d/m/Y')
                            : '—'
                        }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Criado em</p>
                    <p class="font-semibold">
                        {{ $aluno_registro->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            </div>

            <div>
                <p class="text-sm text-slate-500 mb-1">Descrição / Observações</p>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-dax-dark border border-slate-200 dark:border-slate-800">
                    {{ $aluno_registro->descricao ?? 'Nenhuma observação registrada.' }}
                </div>
            </div>
        </div>

        {{-- ================= DOCUMENTO ANEXADO ================= --}}
        <div class="mb-8">
            <h3 class="font-semibold text-dax-dark dark:text-dax-light mb-4">
                📎 Documento Anexado
            </h3>

            @if($aluno_registro->arquivo)
                <a href="{{ asset($aluno_registro->arquivo) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
                          border border-dax-green text-dax-green font-semibold
                          hover:bg-dax-green hover:text-white transition">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    Abrir / Baixar Documento
                </a>
            @else
                <p class="text-slate-500">
                    Nenhum arquivo enviado.
                </p>
            @endif
        </div>

        {{-- ================= AÇÕES FINAIS ================= --}}
        <div class="flex flex-col sm:flex-row sm:justify-between gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">

            <a href="{{ route('admin.aluno_registros.index') }}"
               class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                      text-dax-dark dark:text-dax-light hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                ← Voltar
            </a>

            <form action="{{ route('admin.aluno_registros.destroy', $aluno_registro->id) }}"
                  method="POST">
                @csrf
                @method('DELETE')

                <button type="submit"
                        onclick="return confirm('Tem certeza que deseja excluir este registro?')"
                        class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                    🗑️ Excluir
                </button>
            </form>

        </div>

    </div>

@endsection
