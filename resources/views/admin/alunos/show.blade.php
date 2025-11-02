@extends('layouts.app')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1"><i class="bi bi-person-vcard text-primary"></i> Ficha Completa do Aluno</h4>
            <p class="text-muted mb-0">Informações gerais, responsáveis e documentos vinculados.</p>
        </div>
        <div>
            <a href="{{ route('admin.alunos.edit', $aluno->id) }}" class="btn btn-warning btn-sm me-2">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
            <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    {{-- DADOS GERAIS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    @if($aluno->foto_perfil)
                        <img src="{{ asset('storage/' . $aluno->foto_perfil) }}"
                             class="rounded-circle shadow-sm border mb-2" width="110" height="110" style="object-fit: cover;">
                    @else
                        <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                    @endif
                        <h5 class="mt-2 fw-bold">{{ $aluno->user->name }}</h5>
                        <span class="badge bg-primary">{{ $aluno->turma->nome ?? 'Sem turma atribuída' }}</span>
                </div>

                <div class="col-md-9">
                    <table class="table table-sm mb-0">
                        <tr>
                            <th style="width: 180px;">Matrícula</th>
                            <td>{{ $aluno->matricula ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>E-mail</th>
                            <td>{{ $aluno->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefone</th>
                            <td>{{ $aluno->telefone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Data de Nascimento</th>
                            <td>{{ $aluno->data_nascimento ? \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') : '—' }}</td>
                        </tr>
                        <tr>
                            <th>Data de Cadastro</th>
                            <td>{{ $aluno->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RESPONSÁVEIS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people-fill text-primary"></i> Responsáveis do Aluno</span>
            <a href="{{ route('admin.alunos.edit', $aluno->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Gerenciar
            </a>
        </div>
        <div class="card-body">
            @if($aluno->responsaveis->isEmpty())
                <p class="text-muted mb-0">Nenhum responsável vinculado a este aluno.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Parentesco</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th class="text-end">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->responsaveis as $resp)
                            <tr>
                                <td>{{ $resp->nome }}</td>
                                <td>{{ $resp->grau_parentesco ?? '—' }}</td>
                                <td>{{ $resp->telefone ?? '—' }}</td>
                                <td>{{ $resp->email ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.responsaveis.show', $resp->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- DOCUMENTOS --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom fw-bold">
            <i class="bi bi-folder2 text-primary"></i> Documentos do Aluno
        </div>
        <div class="card-body">
            @if($aluno->documentos->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Arquivo</th>
                            <th>Data</th>
                            <th>Observações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->documentos as $doc)
                            <tr>
                                <td>{{ $doc->tipo }}</td>
                                <td>
                                    <a href="{{ asset('storage/'.$doc->arquivo) }}" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-paperclip"></i> Abrir
                                    </a>
                                </td>
                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $doc->observacoes ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Nenhum documento enviado.</p>
            @endif
        </div>
    </div>

    {{-- Registros do Aluno --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-journal-text text-primary"></i> Registros do Aluno</span>
            <a href="{{ route('admin.aluno_registros.create', ['aluno_id' => $aluno->id]) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus"></i> Novo Registro
            </a>
        </div>
        <div class="card-body">
            @if($aluno->registros->isEmpty())
                <p class="text-muted mb-0">Nenhum registro encontrado.</p>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th class="text-end">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($aluno->registros as $registro)
                        <tr>
                            <td>{{ $registro->tipo }}</td>
                            <td>{{ $registro->categoria ?? '-' }}</td>
                            <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.aluno_registros.show', $registro->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                <form action="{{ route('admin.aluno_registros.destroy', $registro->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Deseja excluir este registro?')">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection
