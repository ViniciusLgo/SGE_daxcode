@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                Detalhes do Atendimento
            </h1>
            <p class="text-sm text-slate-500">
                Visualizacao completa do atendimento registrado pela Secretaria.
            </p>
        </div>

        {{-- Acoes --}}
        <div class="flex gap-2">
            <a href="{{ route('admin.secretaria.atendimentos.index') }}"
               class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                      text-dax-dark dark:text-dax-light hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                 Voltar
            </a>

            <a href="{{ route('admin.secretaria.atendimentos.edit', $atendimento) }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
                 Editar
            </a>
        </div>
    </div>

    {{-- ================= CARD PRINCIPAL ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6 max-w-5xl">

        {{-- STATUS --}}
        <div class="mb-6">
            @if($atendimento->status === 'concluido')
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                    Concluido
                </span>
            @elseif($atendimento->status === 'pendente')
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Pendente
                </span>
            @else
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-slate-200 text-slate-700">
                    Cancelado
                </span>
            @endif
        </div>

        {{-- DADOS GERAIS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-slate-500">Tipo de Atendimento</p>
                <p class="font-semibold text-dax-dark dark:text-dax-light">
                    {{ $atendimento->tipo }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Data do Atendimento</p>
                <p class="font-semibold text-dax-dark dark:text-dax-light">
                    {{ $atendimento->data_atendimento->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Aluno</p>
                <p class="font-semibold text-dax-dark dark:text-dax-light">
                    {{ $atendimento->aluno->user->name ?? '' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-slate-500">Responsavel</p>
                <p class="font-semibold text-dax-dark dark:text-dax-light">
                    {{ $atendimento->responsavel->user->name ?? '' }}
                </p>
            </div>

        </div>

        {{-- DIVISOR --}}
        <div class="my-6 border-t border-slate-200 dark:border-slate-800"></div>

        {{-- ACOES FINAIS --}}
        <div class="flex flex-wrap justify-end gap-3">

            <a href="{{ route('admin.secretaria.atendimentos.edit', $atendimento) }}"
               class="px-4 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
                Editar Atendimento
            </a>

            <form action="{{ route('admin.secretaria.atendimentos.destroy', $atendimento) }}"
                  method="POST"
                  onsubmit="return confirm('Tem certeza que deseja excluir este atendimento?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-4 py-2 rounded-xl border border-red-300 text-red-600 font-semibold hover:bg-red-50 transition">
                    Excluir
                </button>
            </form>

        </div>

    </div>

@endsection
