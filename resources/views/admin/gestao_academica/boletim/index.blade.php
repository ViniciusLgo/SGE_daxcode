@extends('layouts.app')

@section('content')

    <h4 class="mb-4">Boletins</h4>

    <div class="row g-3">

        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📘 Boletim por Turma</h5>
                    <p class="text-muted">
                        Visualize o boletim consolidado dos alunos por turma.
                    </p>
                    <a href="{{ route('admin.turmas.index') }}"
                       class="btn btn-outline-primary">
                        Acessar Turmas
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👤 Boletim por Aluno</h5>
                    <p class="text-muted">
                        Acesse o boletim individual a partir do cadastro do aluno.
                    </p>
                    <a href="{{ route('admin.alunos.index') }}"
                       class="btn btn-outline-secondary">
                        Ir para Alunos
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
