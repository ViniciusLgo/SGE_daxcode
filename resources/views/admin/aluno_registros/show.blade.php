@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4>📋 Detalhes do Registro</h4>
        <p class="text-muted">Visualize as informações e o documento anexado.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">{{ $aluno_registro->tipo }}</h5>

            <p><strong>Aluno:</strong> {{ $aluno_registro->aluno->name ?? '-' }}</p>
            <p><strong>Turma:</strong> {{ $aluno_registro->turma->nome ?? '-' }}</p>
            <p><strong>Categoria:</strong> {{ $aluno_registro->categoria ?? '-' }}</p>
            <p><strong>Data do evento:</strong> {{ $aluno_registro->data_evento ? \Carbon\Carbon::parse($aluno_registro->data_evento)->format('d/m/Y') : '-' }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $aluno_registro->status == 'validado' ? 'success' : ($aluno_registro->status == 'pendente' ? 'warning' : 'secondary') }}">
                {{ ucfirst($aluno_registro->status) }}
            </span>
            </p>
            <p><strong>Descrição:</strong><br>{{ $aluno_registro->descricao ?? '—' }}</p>

            @if($aluno_registro->arquivo)
                <div class="mt-3">
                    <a href="{{ asset($aluno_registro->arquivo) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-arrow-down"></i> Ver / Baixar Documento
                    </a>
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('aluno_registros.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Voltar</a>
                <a href="{{ route('aluno_registros.edit', $aluno_registro->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
            </div>
        </div>
    </div>
@endsection
