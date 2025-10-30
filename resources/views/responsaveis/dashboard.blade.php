@extends('layouts.app')

@section('content')
    <div class="dash-title mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Painel do Responsável</h4>
            <p class="text-muted mb-0">Bem-vindo, {{ auth()->user()->name }} 👨‍👩‍👧</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card card-kpi p-3 border-0 shadow-sm bg-light">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🎓</span></div>
                <div class="kpi-title mt-2 text-dark">FILHOS VINCULADOS</div>
                <div class="kpi-number text-info">
                    {{ \App\Models\Aluno::where('responsavel_id', auth()->id())->count() }}
                </div>
                <div class="kpi-foot text-muted">Alunos sob sua responsabilidade</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-kpi p-3 border-0 shadow-sm bg-light">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">📈</span></div>
                <div class="kpi-title mt-2 text-dark">DESEMPENHO GERAL</div>
                <div class="kpi-number text-info">
                    {{ rand(7,10) }}
                </div>
                <div class="kpi-foot text-muted">Média dos filhos</div>
            </div>
        </div>
    </div>
@endsection
