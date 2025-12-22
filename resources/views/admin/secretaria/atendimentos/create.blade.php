@extends('layouts.app')

@section('content')

    <h4 class="mb-4">Novo Atendimento da Secretaria</h4>

    <form method="POST" action="{{ route('admin.secretaria.atendimentos.store') }}">
        @csrf

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Tipo de Atendimento</label>
                    <input type="text" name="tipo" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Aluno</label>
                        <select name="aluno_id" class="form-select">
                            <option value="">— Selecionar —</option>
                            @foreach($alunos as $aluno)
                                <option value="{{ $aluno->id }}">
                                    {{ $aluno->user->name ?? 'Aluno sem usuário' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Responsável</label>
                        <select name="responsavel_id" class="form-select">
                            <option value="">— Selecionar —</option>
                            @foreach($responsaveis as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->user->name ?? 'Responsável sem usuário' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pendente">Pendente</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data do Atendimento</label>
                    <input type="date" name="data_atendimento"
                           class="form-control"
                           value="{{ now()->toDateString() }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Observação</label>
                    <textarea name="observacao" class="form-control" rows="3"></textarea>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('admin.secretaria.atendimentos.index') }}"
                   class="btn btn-secondary">
                    Voltar
                </a>
                <button class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>

@endsection
