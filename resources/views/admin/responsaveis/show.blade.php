@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4><i class="bi bi-person-vcard text-primary"></i> Detalhes do Responsável</h4>
    </div>

    <div class="card shadow-sm border-0 p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Nome:</strong> {{ $responsavel->nome }}
            </div>
            <div class="col-md-6">
                <strong>CPF:</strong> {{ $responsavel->cpf ?? '—' }}
            </div>
            <div class="col-md-6">
                <strong>E-mail:</strong> {{ $responsavel->email ?? '—' }}
            </div>
            <div class="col-md-6">
                <strong>Telefone:</strong> {{ $responsavel->telefone ?? '—' }}
            </div>
            <div class="col-md-6">
                <strong>Grau de Parentesco:</strong> {{ $responsavel->grau_parentesco ?? '—' }}
            </div>
        </div>

        <hr>

        <h5 class="mt-3 mb-2">👨‍🎓 Alunos Vinculados</h5>
        @if($responsavel->alunos->isEmpty())
            <p class="text-muted">Nenhum aluno vinculado.</p>
        @else
            <ul>
                @foreach($responsavel->alunos as $aluno)
                    <li>{{ $aluno->nome }}</li>
                @endforeach
            </ul>
        @endif

        <div class="mt-4 text-end">
            <a href="{{ route('admin.responsaveis.edit', $responsavel->id) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('admin.responsaveis.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@endsection
