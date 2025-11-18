@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h4 class="mb-0">📋 Detalhes do Registro</h4>
        <small class="text-muted">Informações completas do documento e do aluno.</small>
    </div>

    @include('partials.alerts')

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            {{-- ========================================= --}}
            {{-- CABEÇALHO DO REGISTRO --}}
            {{-- ========================================= --}}
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold mb-1">{{ $aluno_registro->tipo }}</h4>

                    <span class="badge
                    @if($aluno_registro->status == 'pendente') bg-warning text-dark
                    @elseif($aluno_registro->status == 'validado') bg-success
                    @elseif($aluno_registro->status == 'arquivado') bg-secondary
                    @else bg-danger
                    @endif
                ">
                    {{ ucfirst($aluno_registro->status) }}
                </span>
                </div>

                <a href="{{ route('admin.aluno_registros.edit', $aluno_registro->id) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            </div>

            <hr>

            {{-- ========================================= --}}
            {{-- DADOS DO ALUNO --}}
            {{-- ========================================= --}}
            <h5 class="fw-bold text-primary mb-3">👨‍🎓 Dados do Aluno</h5>

            <div class="row mb-3">
                <div class="col-md-4">
                    <p class="mb-1 text-muted">Nome</p>
                    <p class="fw-semibold">{{ $aluno_registro->aluno->user->name ?? '—' }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Turma</p>
                    <p class="fw-semibold">{{ $aluno_registro->turma->nome ?? 'Sem turma' }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Responsável pelo Registro</p>
                    <p class="fw-semibold">
                        {{ $aluno_registro->responsavel->name ?? '—' }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- ========================================= --}}
            {{-- DADOS DO REGISTRO --}}
            {{-- ========================================= --}}
            <h5 class="fw-bold text-primary mb-3">📝 Informações do Registro</h5>

            <div class="row mb-3">

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Categoria</p>
                    <p class="fw-semibold">{{ $aluno_registro->categoria ?? '—' }}</p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Data do Evento</p>
                    <p class="fw-semibold">
                        {{ $aluno_registro->data_evento
                            ? \Carbon\Carbon::parse($aluno_registro->data_evento)->format('d/m/Y')
                            : '—'
                        }}
                    </p>
                </div>

                <div class="col-md-4">
                    <p class="mb-1 text-muted">Criado em</p>
                    <p class="fw-semibold">
                        {{ $aluno_registro->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            </div>

            <div class="mb-3">
                <p class="mb-1 text-muted">Descrição / Observações</p>
                <div class="p-3 bg-light rounded border">
                    {{ $aluno_registro->descricao ?? 'Nenhuma observação registrada.' }}
                </div>
            </div>

            <hr>

            {{-- ========================================= --}}
            {{-- ARQUIVO ANEXADO --}}
            {{-- ========================================= --}}
            <h5 class="fw-bold text-primary mb-3">📎 Documento Anexado</h5>

            @if($aluno_registro->arquivo)
                <a href="{{ asset($aluno_registro->arquivo) }}"
                   target="_blank"
                   class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-file-earmark-arrow-down"></i> Abrir / Baixar Documento
                </a>
            @else
                <p class="text-muted">Nenhum arquivo enviado.</p>
            @endif

            <hr>

            {{-- ========================================= --}}
            {{-- BOTÕES FINAIS --}}
            {{-- ========================================= --}}
            <div class="d-flex justify-content-between mt-4">

                <a href="{{ route('admin.aluno_registros.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>

                <form action="{{ route('admin.aluno_registros.destroy', $aluno_registro->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"
                            onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </form>

            </div>

        </div>
    </div>

@endsection
