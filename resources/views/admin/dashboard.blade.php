@extends('layouts.app')

@section('content')
    <style>
        /* ==================== DAXCODE 2.0+ ==================== */
        :root {
            --dax-yellow: #FFD54F;
            --dax-dark: #0f1115;
            --dax-gray: #1c1f26;
            --dax-accent: #00BFA6;
            --dax-blue: #0ea5e9;
            --dax-purple: #8b5cf6;
            --dax-pink: #ec4899;
            --dax-green: #22c55e;
            --glass-light: rgba(255, 255, 255, .8);
            --glass-dark: rgba(24, 26, 31, .75);
        }

        body {
            background: linear-gradient(180deg, #f7f7f7, #efefef);
        }

        body.dark-mode {
            background: linear-gradient(180deg, #0c0e12, #171a20);
        }

        /* Header */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-light);
            backdrop-filter: blur(10px);
            padding: 1rem 1.25rem;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .06);
            margin-bottom: 1.25rem;
            border: 1px solid rgba(0, 0, 0, .06);
        }

        body.dark-mode .dash-header {
            background: var(--glass-dark);
            border-color: #222;
            color: #eee;
        }

        .dash-header h4 {
            font-weight: 900;
            margin: 0
        }

        .dash-header p {
            margin: 0;
            color: #7a7a7a
        }

        .badge-soft {
            background: #fff;
            border: 1px solid #eee
        }

        body.dark-mode .badge-soft {
            background: #111;
            color: #ddd;
            border-color: #2a2a2a
        }

        /* KPI cards */
        .kpi {
            border: 0;
            border-radius: 18px;
            color: #fff;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: .25s;
            cursor: pointer;
            min-height: 140px;
        }

        .kpi:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .2)
        }

        .kpi:after {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
        }

        .kpi .kpi-title {
            letter-spacing: .4px;
            text-transform: uppercase;
            font-weight: 800;
            opacity: .9;
            font-size: .78rem
        }

        .kpi .kpi-value {
            font-size: 2.4rem;
            font-weight: 900;
            margin: .25rem 0 0
        }

        .kpi .kpi-icon {
            position: absolute;
            bottom: 14px;
            right: 14px;
            opacity: .85;
            font-size: 2.2rem
        }

        .bg-grad-yellow {
            background: linear-gradient(135deg, #F5C518, #f59e0b);
        }

        .bg-grad-green {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .bg-grad-blue {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .bg-grad-purple {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        /* Blocos seção (glass) */
        .section {
            border-radius: 16px;
            background: var(--glass-light);
            backdrop-filter: blur(10px);
            padding: 1.25rem;
            box-shadow: 0 6px 24px rgba(0, 0, 0, .06);
            border: 1px solid rgba(0, 0, 0, .06);
            margin-bottom: 1.25rem;
        }

        body.dark-mode .section {
            background: var(--glass-dark);
            border-color: #222;
            color: #eee
        }

        /* Quick links */
        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem
        }

        .quick-link {
            text-decoration: none;
            font-weight: 700;
            border-radius: 12px;
            padding: .7rem 1rem;
            background: #fff;
            border: 1px solid #eee;
            color: #111;
            transition: .2s
        }

        .quick-link:hover {
            transform: translateY(-2px);
            background: var(--dax-yellow);
            color: #000
        }

        body.dark-mode .quick-link {
            background: #1f232b;
            border-color: #2c2f36;
            color: #eaeaea
        }

        /* Card Dinâmico */
        .dynamic-card {
            border-radius: 14px;
            padding: 1rem;
            background: #fff;
            border: 1px solid #eee
        }

        body.dark-mode .dynamic-card {
            background: #14181f;
            border-color: #2a2a2a
        }

        .progress {
            height: 10px;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(0, 0, 0, .06)
        }

        body.dark-mode .progress {
            background: #1f232b
        }

        /* Lists / tables */
        .list-unstyled li {
            margin-bottom: .4rem
        }

        .table-sm td, .table-sm th {
            padding: .45rem .5rem
        }

        /* Avatares */
        .mini-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd
        }

        body.dark-mode .mini-avatar {
            border-color: #2c2c2c
        }

        /* Botões principais */
        .btn-manage {
            border: 0;
            border-radius: 12px;
            padding: .85rem 1rem;
            font-weight: 800;
            letter-spacing: .3px;
            background: linear-gradient(90deg, #FFD54F, #ffb300);
            color: #111;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .15)
        }

        .btn-manage:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .25)
        }

        .btn-ghost {
            border: 1px dashed #e5e7eb;
            background: transparent;
            color: #6b7280;
            border-radius: 10px;
            padding: .6rem .9rem;
            font-weight: 700
        }

        body.dark-mode .btn-ghost {
            border-color: #333;
            color: #9aa3af
        }

        /* Charts */
        .chart-box {
            height: 280px
        }

        .mini-chart {
            height: 220px
        }

        /* Módulos */
        .module-tile {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: 1rem;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #eee;
            text-decoration: none;
            color: #111;
            font-weight: 800;
            transition: .2s;
        }

        .module-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .08)
        }

        body.dark-mode .module-tile {
            background: #161a22;
            border-color: #2a2a2a;
            color: #eaeaea
        }

        .module-ico {
            font-size: 1.3rem
        }

        /* Badges soft */
        .badge-tag {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            color: #111
        }

        body.dark-mode .badge-tag {
            background: #1f232b;
            border-color: #2a2a2a;
            color: #ddd
        }
    </style>

    {{-- HEADER --}}
    <div class="dash-header">
        <div>
            <h4>Painel Administrativo</h4>
            <p>Bem-vindo, {{ auth()->user()->name ?? 'Administrador' }}</p>
        </div>
        <span class="badge badge-soft">
        <i class="bi bi-hdd-network me-1"></i> v{{ $settings->versao_sistema ?? '1.0.0' }}
      </span>
    </div>

    @php
        $totalAlunos       = $alunosCount ?? 0;
        $totalProfessores  = $professoresCount ?? 0;
        $totalDisciplinas  = $disciplinasCount ?? 0;
        $totalTurmas       = $turmasCount ?? 0;

        $recentAlunos = $recentAlunos ?? collect();
        $atividadesRecentes = $atividadesRecentes ?? collect();
        $turmasTop = $turmasTop ?? collect();
    @endphp


    {{-- KPIs --}}
    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-yellow">
                <div class="kpi-title">Alunos</div>
                <div class="kpi-value">{{ $totalAlunos }}</div>
                <div class="kpi-icon"><i class="bi bi-person-fill"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-green">
                <div class="kpi-title">Professores</div>
                <div class="kpi-value">{{ $totalProfessores }}</div>
                <div class="kpi-icon"><i class="bi bi-person-video3"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-blue">
                <div class="kpi-title">Disciplinas</div>
                <div class="kpi-value">{{ $totalDisciplinas }}</div>
                <div class="kpi-icon"><i class="bi bi-book"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="kpi bg-grad-purple">
                <div class="kpi-title">Turmas</div>
                <div class="kpi-value">{{ $totalTurmas }}</div>
                <div class="kpi-icon"><i class="bi bi-building"></i></div>
            </div>
        </div>
    </div>

    {{-- Atalhos + Botões Principais --}}
    <div class="section">
        <h6 class="fw-bold text-uppercase mb-3">Atalhos Administrativos</h6>

        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('admin.usuarios.index') }}" class="module-tile">
                    <i class="bi bi-people-fill module-ico text-warning"></i>
                    Gerenciar Usuários
                </a>
            </div>

            <div class="col-md-6">
                <a href="{{ route('admin.settings.edit') }}" class="module-tile">
                    <i class="bi bi-gear-fill module-ico text-secondary"></i>
                    Configurações do Sistema
                </a>
            </div>
        </div>
    </div>
@endsection
