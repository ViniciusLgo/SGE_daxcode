@extends('layouts.app')

@section('content')
    <div class="dash-title mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Painel do Professor</h4>
            <p class="text-muted mb-0">Bem-vindo, {{ auth()->user()->name }} 👨‍🏫</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card card-kpi p-3 border-0 shadow-sm bg-light">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">📚</span></div>
                <div class="kpi-title mt-2 text-dark">DISCIPLINAS</div>
                <div class="kpi-number text-primary">
                    {{ \App\Models\Disciplina::whereHas('professores', fn($q) => $q->where('id', auth()->id()))->count() }}
                </div>
                <div class="kpi-foot text-muted">Disciplinas atribuídas</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-kpi p-3 border-0 shadow-sm bg-light">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🏫</span></div>
                <div class="kpi-title mt-2 text-dark">TURMAS</div>
                <div class="kpi-number text-primary">
                    {{ \App\Models\Turma::whereHas('professores', fn($q) => $q->where('id', auth()->id()))->count() }}
                </div>
                <div class="kpi-foot text-muted">Turmas vinculadas</div>
            </div>
        </div>
    </div>
@endsection
