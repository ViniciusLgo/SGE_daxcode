@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
            Novo Atendimento
        </h1>
        <p class="text-sm text-slate-500">
            Registre um novo atendimento realizado pela Secretaria Escolar.
        </p>
    </div>

    {{-- ================= CARD FORM ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60 border border-slate-200 dark:border-slate-800 p-6 max-w-4xl">

        <form action="{{ route('admin.secretaria.atendimentos.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- ================= BLOCO PRINCIPAL ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold text-dax-dark dark:text-dax-light mb-1">
                        Tipo de Atendimento
                    </label>
                    <input
                        type="text"
                        name="tipo"
                        required
                        placeholder="Ex: Matricula, Declaracao, Atualizacao cadastral"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-dax-dark text-dax-dark dark:text-dax-light
                               px-4 py-2.5 focus:ring-2 focus:ring-dax-green focus:outline-none">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-dax-dark dark:text-dax-light mb-1">
                        Status do Atendimento
                    </label>
                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-dax-dark text-dax-dark dark:text-dax-light
                               px-4 py-2.5 focus:ring-2 focus:ring-dax-green focus:outline-none">
                        <option value="pendente">Pendente</option>
                        <option value="concluido">Concluido</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                {{-- Aluno --}}
                <div>
                    <label class="block text-sm font-semibold text-dax-dark dark:text-dax-light mb-1">
                        Aluno
                    </label>
                    <select
                        name="aluno_id"
                        id="aluno_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-dax-dark text-dax-dark dark:text-dax-light
                               px-4 py-2.5 focus:ring-2 focus:ring-dax-green focus:outline-none">
                        <option value=""> Selecione um aluno </option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}">
                                {{ $aluno->user->name ?? 'Aluno sem usuario' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Responsavel --}}
                <div>
                    <label class="block text-sm font-semibold text-dax-dark dark:text-dax-light mb-1">
                        Responsavel
                    </label>
                    <select
                        name="responsavel_id"
                        id="responsavel_id"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-dax-dark text-dax-dark dark:text-dax-light
                               px-4 py-2.5 focus:ring-2 focus:ring-dax-green focus:outline-none">
                        <option value=""> Selecionado automaticamente </option>
                        @foreach($responsaveis as $r)
                            <option value="{{ $r->id }}">
                                {{ $r->user->name ?? 'Responsavel' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-1">
                        Ao selecionar um aluno, o responsavel principal sera preenchido automaticamente.
                    </p>
                </div>

                {{-- Data --}}
                <div>
                    <label class="block text-sm font-semibold text-dax-dark dark:text-dax-light mb-1">
                        Data do Atendimento
                    </label>
                    <input
                        type="date"
                        name="data_atendimento"
                        required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               bg-white dark:bg-dax-dark text-dax-dark dark:text-dax-light
                               px-4 py-2.5 focus:ring-2 focus:ring-dax-green focus:outline-none">
                </div>

            </div>

            {{-- ================= ACOES ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.secretaria.atendimentos.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                          text-dax-dark dark:text-dax-light hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-xl bg-dax-green text-white font-semibold hover:bg-dax-greenSoft transition">
                    Salvar Atendimento
                </button>
            </div>

        </form>
    </div>

    {{-- ================= JS AUTO RESPONSAVEL ================= --}}
    <script>
        const alunos = @json($alunos);

        document.getElementById('aluno_id').addEventListener('change', function () {
            const alunoId = this.value;
            const responsavelSelect = document.getElementById('responsavel_id');

            responsavelSelect.value = '';

            if (!alunoId) return;

            const aluno = alunos.find(a => a.id == alunoId);

            if (aluno && aluno.responsaveis && aluno.responsaveis.length > 0) {
                responsavelSelect.value = aluno.responsaveis[0].id;
            }
        });
    </script>

@endsection
