@extends('layouts.app')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
             Novo Registro
        </h1>
        <p class="text-sm text-slate-500">
            Adicione um documento ou ocorrencia para um aluno.
        </p>
    </div>

    @include('partials.alerts')

    {{-- ================= CARD FORM ================= --}}
    <div class="rounded-2xl bg-white dark:bg-dax-dark/60
                border border-slate-200 dark:border-slate-800 p-6">

        <form action="{{ route('admin.aluno_registros.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            {{-- ================= ALUNO ================= --}}
            <div>
                <label class="block font-semibold mb-1">Aluno *</label>
                <select name="aluno_id" id="aluno_id" required
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                               px-4 py-2.5 bg-white dark:bg-dax-dark/60
                               text-dax-dark dark:text-dax-light">
                    <option value="">Selecione...</option>
                    @foreach($alunos as $aluno)
                        <option value="{{ $aluno->id }}">
                            {{ $aluno->user->name ?? 'Sem nome' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ================= TURMA (AUTO) ================= --}}
            <div>
                <label class="block font-semibold mb-1">Turma</label>

                <input type="text" id="turma_nome" readonly
                       placeholder="Selecione um aluno"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              px-4 py-2.5 bg-slate-100 dark:bg-dax-dark
                              text-slate-600 dark:text-slate-400 cursor-not-allowed">

                <input type="hidden" name="turma_id" id="turma_id">
            </div>

            {{-- ================= TIPO ================= --}}
            <div>
                <label class="block font-semibold mb-1">Tipo *</label>
                <input type="text" name="tipo" required
                       placeholder="Ex: Atestado de falta"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              px-4 py-2.5 bg-white dark:bg-dax-dark/60
                              text-dax-dark dark:text-dax-light">
            </div>

            {{-- ================= CATEGORIA ================= --}}
            <div>
                <label class="block font-semibold mb-1">Categoria</label>
                <input type="text" name="categoria"
                       placeholder="Ex: Frequencia"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              px-4 py-2.5 bg-white dark:bg-dax-dark/60
                              text-dax-dark dark:text-dax-light">
            </div>

            {{-- ================= DATA EVENTO ================= --}}
            <div>
                <label class="block font-semibold mb-1">Data do Evento</label>
                <input type="date" name="data_evento"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              px-4 py-2.5 bg-white dark:bg-dax-dark/60
                              text-dax-dark dark:text-dax-light">
            </div>

            {{-- ================= DESCRICAO ================= --}}
            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Descricao / Observacoes</label>
                <textarea name="descricao" rows="4"
                          class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                 px-4 py-2.5 bg-white dark:bg-dax-dark/60
                                 text-dax-dark dark:text-dax-light"></textarea>
            </div>

            {{-- ================= ARQUIVO ================= --}}
            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Arquivo (opcional)</label>
                <input type="file" name="arquivo"
                       class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                              px-4 py-2 bg-white dark:bg-dax-dark/60
                              text-dax-dark dark:text-dax-light">
                <p class="mt-1 text-sm text-slate-500">
                    PDF, JPG, PNG  max. 5MB
                </p>
            </div>

            {{-- ================= BOTOES ================= --}}
            <div class="md:col-span-2 flex justify-between mt-4">

                <a href="{{ route('admin.aluno_registros.index') }}"
                   class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700
                          hover:bg-slate-100 dark:hover:bg-dax-dark/80">
                     Voltar
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-xl bg-dax-green text-white font-semibold
                               hover:bg-dax-greenSoft transition">
                     Salvar
                </button>

            </div>

        </form>
    </div>

    {{-- ================= JS: BUSCAR TURMA ================= --}}
    <script>
        document.getElementById('aluno_id').addEventListener('change', function () {

            const alunoId = this.value;
            if (!alunoId) return;

            fetch(`/admin/buscar-turma-aluno/${alunoId}`)
                .then(r => r.json())
                .then(data => {

                    if (data.sem_turma) {
                        alert(" Este aluno nao esta vinculado a nenhuma turma!");
                        document.getElementById('turma_nome').value = '';
                        document.getElementById('turma_id').value = '';
                        return;
                    }

                    document.getElementById('turma_nome').value = data.turma;
                    document.getElementById('turma_id').value = data.turma_id;
                })
                .catch(() => alert("Erro ao buscar turma do aluno."));
        });
    </script>

@endsection
