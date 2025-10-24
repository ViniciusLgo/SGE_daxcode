@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h4 class="mb-1"><i class="bi bi-person-vcard text-primary"></i> Detalhes do Aluno</h4>
        <p class="text-muted mb-0">Visualize as informações completas do estudante.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 text-center">
                    @if($aluno->foto_perfil)
                        <img src="{{ asset('storage/' . $aluno->foto_perfil) }}" class="img-fluid rounded-circle mb-2" alt="Foto do Aluno" width="120">
                    @else
                        <i class="bi bi-person-circle text-secondary" style="font-size: 4rem;"></i>
                    @endif
                    <h5 class="mt-2 fw-bold">{{ $aluno->nome }}</h5>
                    <span class="badge bg-primary">{{ $aluno->turma->nome ?? 'Sem turma' }}</span>
                </div>

                <div class="col-md-9">
                    <table class="table table-sm">
                        <tr>
                            <th scope="row" style="width: 180px;">Matrícula</th>
                            <td>{{ $aluno->matricula ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>E-mail</th>
                            <td>{{ $aluno->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefone</th>
                            <td>{{ $aluno->telefone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Data de Cadastro</th>
                            <td>{{ $aluno->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="text-end mt-4">
                <a href="{{ route('admin.alunos.edit', $aluno->id) }}" class="btn btn-warning px-3">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
                <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary px-3">
                    Voltar à Lista
                </a>
            </div>
        </div>
    </div>
@endsection
