@extends('layouts.app')

@section('content')
    @php
        $metrics = [
            ['label' => 'Alunos', 'value' => $totalAlunos, 'description' => 'Cadastrados'],
            ['label' => 'Professores', 'value' => $totalProfessores, 'description' => 'Ativos'],
            ['label' => 'Disciplinas', 'value' => $totalDisciplinas, 'description' => 'Registradas'],
            ['label' => 'Turmas', 'value' => $totalTurmas, 'description' => 'Em andamento'],
        ];
    @endphp

    <div class="d-flex flex-column gap-4">
        <div>
            <h4 class="mb-1">Bem-vindo, {{ auth()->user()->name }}!</h4>
            <p class="text-muted mb-0">Acompanhe os indicadores gerais do sistema.</p>
        </div>

        <div class="row g-3">
            @foreach($metrics as $metric)
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $metric['label'] }}</h5>
                            <h2 class="display-6">{{ number_format($metric['value'], 0, ',', '.') }}</h2>
                            <p class="text-muted mb-0">{{ $metric['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
