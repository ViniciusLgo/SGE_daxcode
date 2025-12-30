@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="aulaFormEdit()">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-dax-dark dark:text-dax-light">
                    ✏️ Editar Aula
                </h1>
                <p class="text-sm text-slate-500">
                    Atualize as informações da aula registrada ou exclua o registro, se necessário.
                    <span class="block text-xs mt-1">
                    ⏱️ Cálculo baseado em <strong>hora-aula (50 minutos)</strong>
                </span>
                </p>
            </div>

            <a href="{{ route('admin.aulas.show', $aula) }}"
               class="px-4 py-2 rounded-xl border
                  border-slate-300 dark:border-slate-700
                  hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                Voltar
            </a>
        </div>

        {{-- ================= FORMULÁRIO ================= --}}
        <form method="POST"
              action="{{ route('admin.aulas.update', $aula) }}"
              class="rounded-2xl border
                 bg-white dark:bg-dax-dark/60
                 border-slate-200 dark:border-slate-800
                 p-6 space-y-6">

            @csrf
            @method('PUT')

            {{-- ================= DADOS DA AULA ================= --}}
            <div>
                <h2 class="font-semibold text-lg mb-4">
                    📌 Dados da Aula
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Data --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Data</label>
                        <input type="text"
                               id="data"
                               name="data"
                               value="{{ old('data', $aula->data->format('d/m/Y')) }}"
                               placeholder="DD/MM/AAAA"
                               required
                               class="w-full rounded-xl border
                              border-slate-300 dark:border-slate-700
                              px-4 py-2.5
                              bg-white dark:bg-dax-dark
                              text-dax-dark dark:text-dax-light">
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tipo</label>
                        <select name="tipo" required
                                class="w-full rounded-xl border
                               border-slate-300 dark:border-slate-700
                               px-4 py-2.5
                               bg-white dark:bg-dax-dark
                               text-dax-dark dark:text-dax-light">
                            @foreach(['aula','reuniao','evento','formacao'] as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ old('tipo', $aula->tipo) === $tipo ? 'selected' : '' }}>
                                    {{ ucfirst($tipo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hora início --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1">Hora inicial</label>
                        <input type="text"
                               id="hora_inicio"
                               name="hora_inicio"
                               x-model="horaInicio"
                               value="{{ old('hora_inicio', $aula->hora_inicio) }}"
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
                                <option value="{{ $i }}"
                                    {{ old('quantidade_blocos', $aula->quantidade_blocos) == $i ? 'selected' : '' }}>
                                    {{ $i }} h/a ({{ $i * 50 }} min)
                                </option>
                            @endfor
                        </select>
                    </div>

                </div>
            </div>

            {{-- ================= PREVIEW ================= --}}
            <div class="rounded-xl
                bg-slate-50 dark:bg-slate-900/40
                border border-slate-200 dark:border-slate-800
                p-4 text-sm">
                <strong>Resumo automático:</strong><br>
                ⏱️ Duração total: <span x-text="duracao"></span> minutos<br>
                🕒 Horário final previsto:
                <span class="font-bold" x-text="horaFim"></span>
            </div>

            {{-- ================= CONTEÚDO ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    📚 Conteúdo Trabalhado
                </label>
                <textarea name="conteudo"
                          rows="3"
                          class="w-full rounded-xl border
                         border-slate-300 dark:border-slate-700
                         px-4 py-2.5
                         bg-white dark:bg-dax-dark
                         text-dax-dark dark:text-dax-light">{{ old('conteudo', $aula->conteudo) }}</textarea>
            </div>

            {{-- ================= OBSERVAÇÕES ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">
                    📝 Atividades / Observações
                </label>
                <textarea name="observacoes"
                          rows="3"
                          class="w-full rounded-xl border
                         border-slate-300 dark:border-slate-700
                         px-4 py-2.5
                         bg-white dark:bg-dax-dark
                         text-dax-dark dark:text-dax-light">{{ old('observacoes', $aula->observacoes) }}</textarea>
            </div>

            {{-- ================= AÇÕES ================= --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl
                           bg-dax-green text-white font-semibold
                           hover:bg-dax-greenSoft transition">
                        💾 Salvar Alterações
                    </button>

                    <a href="{{ route('admin.aulas.show', $aula) }}"
                       class="px-4 py-2.5 rounded-xl border
                      border-slate-300 dark:border-slate-700
                      hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Cancelar
                    </a>
                </div>

                {{-- EXCLUSÃO --}}
                <form method="POST"
                      action="{{ route('admin.aulas.destroy', $aula) }}"
                      onsubmit="return confirm('Tem certeza que deseja excluir este registro de aula? Esta ação não poderá ser desfeita.')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl
                           border border-red-300
                           text-red-600 font-semibold
                           hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                        🗑 Excluir Aula
                    </button>
                </form>

            </div>
        </form>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    <script>
        function aulaFormEdit() {
            return {
                horaInicio: '{{ $aula->hora_inicio }}',
                blocos: {{ $aula->quantidade_blocos }},

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

    <script>
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
            allowInput: true
        });
    </script>
@endsection
