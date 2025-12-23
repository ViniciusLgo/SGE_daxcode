@extends('layouts.app')

@section('content')

    <h4>Novo Atendimento da Secretaria</h4>

    <form action="{{ route('admin.secretaria.atendimentos.store') }}" method="POST">
        @csrf

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo de Atendimento</label>
            <input type="text" name="tipo" class="form-control" required>
        </div>

        {{-- Aluno --}}
        <div class="mb-3">
            <label class="form-label">Aluno</label>
            <select name="aluno_id" id="aluno_id" class="form-select">
                <option value="">—</option>
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id }}">
                        {{ $aluno->user->name ?? 'Aluno sem usuário' }}
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
                    <option value="{{ $r->id }}">
                        {{ $r->user->name ?? 'Responsável' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pendente">Pendente</option>
                <option value="concluido">Concluído</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        {{-- Data --}}
        <div class="mb-3">
            <label class="form-label">Data do Atendimento</label>
            <input type="date" name="data_atendimento" class="form-control" required>
        </div>

        <button class="btn btn-primary">Salvar</button>
        <a href="{{ route('admin.secretaria.atendimentos.index') }}" class="btn btn-secondary">Voltar</a>
    </form>

    {{-- ============================= --}}
    {{-- JS AUTO RESPONSÁVEL --}}
    {{-- ============================= --}}
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
