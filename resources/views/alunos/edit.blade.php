@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-pencil-square text-primary"></i> Editar Aluno</h4>
        <p class="text-muted mb-0">Atualize as informações e gerencie documentos do aluno.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Mensagens --}}
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Corrija os erros abaixo.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form principal --}}
            <form action="{{ route('admin.alunos.update', $aluno) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="mb-3">
                    <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                    <input type="file" name="foto_perfil" id="foto_perfil" class="form-control">

                    @if($aluno->foto_perfil)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$aluno->foto_perfil) }}"
                                 alt="Foto atual"
                                 class="rounded shadow-sm border"
                                 width="100" height="100"
                                 style="object-fit: cover;">
                        </div>
                    @endif
                </div>


                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" name="nome" class="form-control" value="{{ old('nome', $aluno->nome) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $aluno->email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Telefone</label>
                        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $aluno->telefone) }}">
                    </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-save me-1"></i> Atualizar
                    </button>
                    <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary px-3">Voltar</a>
                </div>
            </form>

            {{-- Documentos --}}
            <hr class="my-4">
            <h5 class="mb-3"><i class="bi bi-folder2 text-primary"></i> Documentos do Aluno</h5>

            <form action="{{ route('alunos.documentos.store', $aluno) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <input type="text" name="tipo" class="form-control" placeholder="Ex: RG, CPF..." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Arquivo</label>
                        <input type="file" name="arquivo" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Observações</label>
                        <input type="text" name="observacoes" class="form-control" placeholder="Comentário opcional">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button class="btn btn-success"><i class="bi bi-upload"></i></button>
                    </div>
                </div>
            </form>

            @if($aluno->documentos->count())
                <div class="table-responsive mt-4">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Arquivo</th>
                            <th>Data</th>
                            <th>Observações</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aluno->documentos as $doc)
                            @php
                                $ext = strtolower(pathinfo($doc->arquivo, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                                $isPdf = $ext === 'pdf';
                                $status = $loop->even ? 'Aprovado' : 'Pendente';
                                $color = $status === 'Aprovado' ? 'success' : 'warning';
                            @endphp
                            <tr>
                                <td><i class="bi bi-file-earmark-text text-primary"></i> {{ $doc->tipo }}</td>
                                <td>
                                    @if($isImage)
                                        <a href="{{ asset('storage/'.$doc->arquivo) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$doc->arquivo) }}" alt="Prévia" width="60" class="rounded shadow-sm border">
                                        </a>
                                    @elseif($isPdf)
                                        <a href="{{ asset('storage/'.$doc->arquivo) }}" target="_blank" class="text-decoration-none">📄 Ver PDF</a>
                                    @else
                                        <a href="{{ asset('storage/'.$doc->arquivo) }}" target="_blank">📎 Abrir arquivo</a>
                                    @endif
                                </td>
                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $doc->observacoes ?? '-' }}</td>
                                <td><span class="badge bg-{{ $color }}">{{ $status }}</span></td>
                                <td class="text-end">
                                    <form action="{{ route('documentos.destroy', $doc) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Excluir este documento?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mt-3">Nenhum documento enviado.</p>
            @endif
        </div>
    </div>
@endsection
