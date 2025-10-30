@extends('layouts.app')

@section('content')
    <div class="dash-title mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">Painel do Aluno</h4>
            <p class="text-muted mb-0">Olá, {{ auth()->user()->name }} 👩‍🎓</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-kpi p-3 bg-light border-0 shadow-sm">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">📝</span></div>
                <div class="kpi-title mt-2">NOTAS</div>
                <div class="kpi-number text-success">
                    {{ rand(7,10) }} <!-- Exemplo até integrar notas -->
                </div>
                <div class="kpi-foot">Média geral</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-kpi p-3 bg-light border-0 shadow-sm">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">📅</span></div>
                <div class="kpi-title mt-2">FREQUÊNCIA</div>
                <div class="kpi-number text-success">
                    {{ rand(85,100) }}%
                </div>
                <div class="kpi-foot">Presença média</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-kpi p-3 bg-light border-0 shadow-sm">
                <div class="d-flex justify-content-between"><span class="kpi-emoji">🏫</span></div>
                <div class="kpi-title mt-2">TURMA</div>
                <div class="kpi-number text-success">
                    {{ optional(auth()->user()->turma)->nome ?? '—' }}
                </div>
                <div class="kpi-foot">Turma atual</div>
            </div>
        </div>
    </div>
@endsection
