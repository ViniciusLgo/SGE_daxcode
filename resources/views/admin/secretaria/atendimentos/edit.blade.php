@extends('layouts.app')

@section('content')

    <h4>Editar Atendimento</h4>

    <form action="{{ route('admin.secretaria.atendimentos.update', $atendimento) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <input type="text" name="tipo"
                   value="{{ $atendimento->tipo }}"
                   class="form-control" required>
        </div>

        {{-- Aluno --}}
        <div class="mb-3">
            <label class="form-label">Aluno</label>
            <select name="aluno_id" id="aluno_id" class="form-select">
                <option value="">—</option>
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}"
                        @selected($atendimento->aluno_id == $aluno->id)>
                        {{ $aluno->user->name ?? 'Aluno' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Responsável --}}
        <div class="mb-3">
            <label class="form-label">Responsável</label>
            <select name="responsavel_id" id="responsavel_id" class="form-select">
                <option value="">—</option>
                @foreach($responsaveis as $r)
                    <option value="{{ $r->id }}"
                        @selected($atendimento->responsavel_id == $r->id)>
                        {{ $r->user->name ?? 'Responsável' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                @foreach(['pendente','concluido','cancelado'] as $s)
                    <option value="{{ $s }}" @selected($atendimento->status == $s)>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Data --}}
        <div class="mb-3">
            <label>Data</label>
            <input type="date"
                   name="data_atendimento"
                   value="{{ $atendimento->data_atendimento->format('Y-m-d') }}"
                   class="form-control">
        </div>

        <button class="btn btn-primary">Atualizar</button>
        <a href="{{ route('admin.secretaria.atendimentos.index') }}" class="btn btn-secondary">Voltar</a>
    </form>

    {{-- JS AUTO --}}
    <script>
        const alunos = @json($alunos);

        document.getElementById('aluno_id').addEventListener('change', function () {
            const alunoId = this.value;
            const responsavelSelect = document.getElementById('responsavel_id');

            responsavelSelect.value = '';

            if (!alunoId) return;

            const aluno = alunos.find(a => a.id == alunoId);

            if (aluno && aluno.responsaveis.length > 0) {
                responsavelSelect.value = aluno.responsaveis[0].id;
            }
        });
    </script>

@endsection
