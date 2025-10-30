@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-badge text-info me-2"></i> {{ $professor->nome }}
            </h4>
            <p class="text-muted mb-0">Informações detalhadas do professor e suas disciplinas associadas.</p>
        </div>
        <a href="{{ route('admin.professores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row g-3">
        {{-- Coluna: dados pessoais --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-person-lines-fill me-1"></i> Dados do Professor</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nome</dt>
                        <dd class="col-sm-7">{{ $professor->nome }}</dd>

                        <dt class="col-sm-5">E-mail</dt>
                        <dd class="col-sm-7">{{ $professor->email }}</dd>

                        <dt class="col-sm-5">Telefone</dt>
                        <dd class="col-sm-7">{{ $professor->telefone ?? '—' }}</dd>

                        <dt class="col-sm-5">Especialização</dt>
                        <dd class="col-sm-7">{{ $professor->especializacao ?? '—' }}</dd>

                        <dt class="col-sm-5">Criado em</dt>
                        <dd class="col-sm-7">
                            {{ optional($professor->created_at)->format('d/m/Y H:i') ?? '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Coluna: disciplinas associadas --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-book-half me-1"></i> Disciplinas ({{ $disciplinas->total() ?? $disciplinas->count() }})
                        </h5>
                        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Nova Disciplina
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped mb-0 align-middle">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Carga Horária</th>
                                <th class="text-end">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($disciplinas as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->nome }}</td>
                                    <td>{{ $disciplina->carga_horaria ? $disciplina->carga_horaria . 'h' : '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.disciplinas.show', $disciplina->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-exclamation-circle"></i> Nenhuma disciplina associada a este professor.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($disciplinas instanceof \Illuminate\Pagination\LengthAwarePaginator && $disciplinas->hasPages())
                    <div class="card-footer bg-white">
                        {{ $disciplinas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
