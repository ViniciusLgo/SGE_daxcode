@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Bem-vindo, {{ Auth::user()->name }}!</h4>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">🎓 Alunos</h5>
                    <h2>128</h2>
                    <p class="text-muted mb-0">Cadastrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">👨‍🏫 Professores</h5>
                    <h2>12</h2>
                    <p class="text-muted mb-0">Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">📚 Disciplinas</h5>
                    <h2>24</h2>
                    <p class="text-muted mb-0">Registradas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">🏫 Turmas</h5>
                    <h2>8</h2>
                    <p class="text-muted mb-0">Em andamento</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
