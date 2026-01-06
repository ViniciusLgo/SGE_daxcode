@extends('layouts.app')

@section('content')

  {{-- ================= HEADER ================= --}}
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
        Boletim do Aluno
      </h1>
      <p class="text-sm text-slate-500">
        {{ $aluno->user->name }} | Turma {{ $aluno->turma->nome '' }}
      </p>
    </div>

    <a href="{{ route('admin.alunos.show', $aluno) }}"
      class="px-4 py-2 rounded-xl border
       border-slate-300 dark:border-slate-700
       hover:bg-slate-100 dark:hover:bg-slate-800">
      <- Voltar
    </a>
  </div>

  {{-- ================= CONTEÚDO ================= --}}
  @forelse($boletim as $item)

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
      bg-white dark:bg-dax-dark/60 p-6 mb-6">

      {{-- DISCIPLINA + SITUAÇÃO --}}
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-dax-dark dark:text-dax-light">
          {{ $item['disciplina']->nome }}
        </h2>

        @php
          $situacao = $item['situacao'];
          $situacaoClasses = match($situacao) {
            'Aprovado' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
            'Recuperacao' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
            default => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
          };
        @endphp

        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $situacaoClasses }}">
      {{ $situacao }}
    </span>
      </div>

      {{-- TABELA DE AVALIAÇÕES --}}
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm border
           border-slate-200 dark:border-slate-800">
          <thead class="bg-slate-50 dark:bg-slate-900/40">
          <tr class="text-slate-600 dark:text-slate-300">
            <th class="px-4 py-2 text-left">Avaliação</th>
            <th class="px-4 py-2 text-center">Tipo</th>
            <th class="px-4 py-2 text-center">Nota</th>
            <th class="px-4 py-2 text-center">Entrega</th>
          </tr>
          </thead>

          <tbody>
          @foreach($item['avaliacoes'] as $resultado)
            <tr class="border-t border-slate-200 dark:border-slate-800">

              <td class="px-4 py-2">
                <div class="font-semibold text-dax-dark dark:text-dax-light">
                  {{ $resultado->avaliacao->titulo }}
                </div>
                <div class="text-xs text-slate-500">
                  {{ $resultado->avaliacao->data_avaliacao->format('d/m/Y') }}
                </div>
              </td>

              <td class="px-4 py-2 text-center">
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                   bg-slate-200 text-slate-700
                   dark:bg-slate-700 dark:text-slate-200">
              {{ ucfirst($resultado->avaliacao->tipo) }}
            </span>
              </td>

              <td class="px-4 py-2 text-center font-semibold
            {{ (!$resultado->entregue || $resultado->nota === null) ? 'text-red-500' : '' }}">
                {{ (!$resultado->entregue || $resultado->nota === null)
                  ? '0,00'
                  : number_format($resultado->nota, 2, ',', '.') }}
              </td>

              <td class="px-4 py-2 text-center">
                @if($resultado->entregue)
                  <span class="text-emerald-600 font-semibold">Entregue</span>
                @else
                  <span class="text-red-500 font-semibold">Não entregue</span>
                @endif
              </td>

            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      {{-- MÉDIA --}}
      <div class="flex justify-end mt-4">
        <div class="text-right">
          <div class="text-xs text-slate-500">Média da disciplina</div>
          <div class="text-lg font-black
        {{ $item['media'] < 6 ? 'text-red-500' : 'text-emerald-600' }}">
            {{ number_format($item['media'], 2, ',', '.') }}
          </div>
        </div>
      </div>

    </div>

  @empty
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800
      bg-white dark:bg-dax-dark/60 p-6 text-center text-slate-500">
      Nenhuma avaliação registrada para este aluno.
    </div>
  @endforelse

@endsection
