@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6"
         x-data="aulaForm()"
         x-init="initPickers()">

        {{-- ================= HEADER ================= --}}
        <div>
            <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                 Registro de Aula / Atividade
            </h1>
            <p class="text-sm text-slate-500">
                Registre aulas, reunioes, eventos ou formacoes.
                <span class="block text-xs mt-1">
                 Calculo baseado em <strong>hora-aula (50 minutos)</strong>
            </span>
            </p>
        </div>

        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('admin.aulas.store') }}"
              class="rounded-2xl
             bg-white dark:bg-dax-dark/60
             border border-slate-200 dark:border-slate-800
             p-6 space-y-6">

            @csrf

            {{-- ================= INFORMACOES BASICAS ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                     Informacoes Basicas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Turma --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Turma</label>
                        <select name="turma_id" required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            <option value="">Selecione</option>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}">
                                    {{ $turma->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Disciplina --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Disciplina</label>
                        <select name="disciplina_id" required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            <option value="">Selecione</option>
                            @foreach($disciplinas as $disciplina)
                                <option value="{{ $disciplina->id }}">
                                    {{ $disciplina->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Professor --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Professor</label>
                        <select name="professor_id" required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            <option value="">Selecione</option>
                            @foreach($professores as $prof)
                                <option value="{{ $prof->id }}">
                                    {{ $prof->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            {{-- ================= DATA / HORARIO ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                     Data e Horario
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tipo</label>
                        <select name="tipo" required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            <option value="aula">Aula</option>
                            <option value="reuniao">Reuniao</option>
                            <option value="evento">Evento</option>
                            <option value="formacao">Formacao</option>
                        </select>
                    </div>

                    {{-- Data --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Data</label>
                        <input type="text"
                               id="data"
                               name="data"
                               placeholder="DD/MM/AAAA"
                               required
                               class="w-full rounded-xl border
                              border-slate-300 dark:border-slate-700
                              px-4 py-2.5
                              bg-white dark:bg-dax-dark
                              text-dax-dark dark:text-dax-light">
                    </div>

                    {{-- Hora inicio --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Hora inicial</label>
                        <input type="text"
                               id="hora_inicio"
                               name="hora_inicio"
                               placeholder="HH:MM"
                               required
                               class="w-full rounded-xl border
                              border-slate-300 dark:border-slate-700
                              px-4 py-2.5
                              bg-white dark:bg-dax-dark
                              text-dax-dark dark:text-dax-light">
                    </div>

                    {{-- Quantidade --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Quantidade de horas-aula
                        </label>
                        <select name="quantidade_blocos"
                                x-model="blocos"
                                required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">
                                    {{ $i }} h/a ({{ $i * 50 }} min)
                                </option>
                            @endfor
                        </select>
                    </div>

                </div>
            </div>

            {{-- ================= PREVIEW AUTOMATICO ================= --}}
            <div class="rounded-xl
                bg-slate-50 dark:bg-slate-900/40
                border border-slate-200 dark:border-slate-800
                p-4 text-sm">
                <strong>Resumo automatico:</strong><br>
                 Duracao total: <span x-text="duracao"></span> minutos<br>
                 Horario final previsto:
                <span class="font-bold" x-text="horaFim"></span>
            </div>

            {{-- ================= CONTEUDO ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                     Conteudo ministrado
                </label>
                <input type="text"
                       name="conteudo"
                       placeholder="Ex: Fracoes  operacoes basicas"
                       class="w-full rounded-xl border
                      border-slate-300 dark:border-slate-700
                      px-4 py-2.5
                      bg-white dark:bg-dax-dark
                      text-dax-dark dark:text-dax-light">
            </div>

            {{-- ================= OBSERVACOES ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                     Observacoes
                </label>
                <textarea name="observacoes"
                          rows="3"
                          placeholder="Atividades realizadas, dinamica da aula, observacoes importantes"
                          class="w-full rounded-xl border
                         border-slate-300 dark:border-slate-700
                         px-4 py-2.5
                         bg-white dark:bg-dax-dark
                         text-dax-dark dark:text-dax-light"></textarea>
            </div>

            {{-- ================= ATIVIDADE CASA ================= --}}
            <div class="flex items-center gap-2">
                <input type="checkbox"
                       name="atividade_casa"
                       value="1"
                       class="rounded border-slate-300 dark:border-slate-700">
                <label class="text-sm">
                    Foi deixada atividade para casa
                </label>
            </div>

            {{-- ================= ACOES ================= --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="{{ route('admin.aulas.index') }}"
                   class="px-4 py-2.5 rounded-xl border
                  border-slate-300 dark:border-slate-700
                  hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl
                       bg-dax-green text-white font-bold
                       hover:bg-dax-greenSoft transition">
                    Registrar Aula
                </button>
            </div>

        </form>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    <script>
        function aulaForm() {
            return {
                horaInicio: '',
                blocos: 1,

                initPickers() {
                    flatpickr("#data", {
                        dateFormat: "d/m/Y",
                        locale: "pt",
                        allowInput: true
                    });

                    flatpickr("#hora_inicio", {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        allowInput: true,
                        onChange: (selectedDates, dateStr) => {
                            this.horaInicio = dateStr;
                        }
                    });
                },

                get duracao() {
                    return this.blocos * 50;
                },

                get horaFim() {
                    if (!this.horaInicio) return '--:--';

                    let [h, m] = this.horaInicio.split(':').map(Number);
                    let total = h * 60 + m + (this.blocos * 50);

                    let fh = Math.floor(total / 60) % 24;
                    let fm = total % 60;

                    return String(fh).padStart(2, '0') + ':' + String(fm).padStart(2, '0');
                }
            }
        }
    </script>
@endsection
