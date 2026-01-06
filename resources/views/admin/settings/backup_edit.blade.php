@extends('layouts.app')

@section('content')

    {{-- ========================================================= --}}
    {{-- ALERTAS DE ERRO / SUCESSO                               --}}
    {{-- ========================================================= --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm p-3 mb-4" style="animation: fadeIn .3s;">
            <strong> Existem erros no formulario:</strong>
            <ul class="mt-2 mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div id="toastSuccess"
             class="toast-container position-fixed top-0 end-0 p-3"
             style="z-index: 9999; animation: slideInRight .5s;">

            <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                         {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="toastError"
             class="toast-container position-fixed top-0 end-0 p-3"
             style="z-index: 9999; animation: slideInRight .5s;">

            <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                         {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- ESTILOS GERAIS DO PAINEL DE CONFIGURACOES                --}}
    {{-- ========================================================= --}}
    <style>
        .settings-wrapper {
            animation: fadeInUp .4s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .settings-hero {
            background: linear-gradient(135deg, #ffcc33, #ffdd66);
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin-bottom: 22px;
        }

        .settings-hero-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .settings-hero-subtitle {
            font-size: .9rem;
            opacity: .85;
        }

        .settings-hero-icon {
            font-size: 2.4rem;
            opacity: .9;
            transform: translateY(2px);
        }

        /* ================================ */
        /* SIDEBAR (Gerais, Academicas, ...) */
        /* ================================ */
        .settings-sidebar {
            border-right: 1px solid rgba(0,0,0,0.05);
            padding-right: 16px;
        }

        .settings-sidebar-title {
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #999;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .settings-nav {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .settings-nav li { margin-bottom: 4px; }

        .settings-nav a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: #444;
            font-size: .9rem;
            font-weight: 500;
            transition: background .18s ease, transform .12s ease, box-shadow .18s ease;
        }

        .settings-nav a span.icon {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .settings-nav a.active {
            background: #111827;
            color: #fff;
            box-shadow: 0 6px 14px rgba(0,0,0,0.25);
            transform: translateY(-1px);
        }

        .settings-nav a:hover:not(.active) {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        /* ================================ */
        /* CARDS / FORM GERAL               */
        /* ================================ */
        .settings-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 22px rgba(15,23,42,0.07);
            overflow: hidden;
        }

        .settings-card-header {
            padding: 14px 18px 8px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .settings-card-header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-card-header h5 .emoji {
            font-size: 1.2rem;
        }

        .settings-card-subtitle {
            font-size: .78rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .settings-card-body {
            padding: 18px;
            background: #f9fafb;
        }

        .settings-group-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #9ca3af;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-label {
            font-size: .85rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control, .form-select {
            border-radius: 10px;
            font-size: .9rem;
        }

        .settings-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn-save-settings {
            border-radius: 999px;
            padding: 8px 18px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(37,99,235,0.25);
            transform: translateY(0);
            transition: transform .12s ease, box-shadow .18s ease;
        }

        .btn-save-settings:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(37,99,235,0.32);
        }

        .tab-content-settings {
            animation: tabFade .25s ease-out;
        }

        @keyframes tabFade {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .d-none-important { display: none !important; }

        /* ====================================================== */
        /* SUB-ABAS ACADEMICAS (Estilo 1, grande horizontal)      */
        /* ====================================================== */
        .academic-subnav-wrapper {
            position: sticky;
            top: 70px; /* ajusta se seu header for maior/menor */
            z-index: 20;
            background: #f9fafb;
            padding: 10px 0 6px;
        }

        .academic-subnav {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .academic-subnav-btn {
            flex: 0 0 auto;
            min-width: 170px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            padding: 8px 14px;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            font-weight: 500;
            color: #4b5563;
            cursor: pointer;
            transition: background .18s, box-shadow .18s, transform .1s, border-color .18s;
            white-space: nowrap;
        }

        .academic-subnav-btn span.icon {
            font-size: 1.1rem;
        }

        .academic-subnav-btn.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
            box-shadow: 0 8px 18px rgba(15,23,42,0.35);
            transform: translateY(-1px);
        }

        .academic-subnav-btn:hover:not(.active) {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        .academic-section {
            margin-top: 16px;
        }
    </style>

    <div class="settings-wrapper">

        {{-- HEADER / HERO --}}
        <div class="settings-hero">
            <div>
                <div class="settings-hero-title">Configuracoes do Sistema</div>
                <div class="settings-hero-subtitle">
                    Personalize a identidade, regras academicas e comportamento geral do SGE DaxCode.
                </div>
            </div>
            <div class="settings-hero-icon"></div>
        </div>

        <div class="row">
            {{-- ================================== --}}
            {{-- SIDEBAR LATERAL                    --}}
            {{-- ================================== --}}
            <div class="col-md-3 settings-sidebar">
                <div class="settings-sidebar-title">Secoes</div>
                <ul class="settings-nav">
                    <li><a href="#tab-geral" class="tab-link active"><span class="icon"></span> <span>Gerais</span></a></li>
                    <li><a href="#tab-academico" class="tab-link"><span class="icon"></span> <span>Academicas</span></a></li>
                    <li><a href="#tab-documentos" class="tab-link"><span class="icon"></span> <span>Documentos & PDFs</span></a></li>
                    <li><a href="#tab-usuarios" class="tab-link"><span class="icon"></span> <span>Usuarios & Acesso</span></a></li>
                    <li><a href="#tab-notificacoes" class="tab-link"><span class="icon"></span> <span>Notificacoes</span></a></li>
                    <li><a href="#tab-financeiro" class="tab-link"><span class="icon"></span> <span>Financeiro</span></a></li>
                    <li><a href="#tab-comunicacao" class="tab-link"><span class="icon"></span> <span>Comunicacao</span></a></li>
                    <li><a href="#tab-logs" class="tab-link"><span class="icon"></span> <span>Logs & Auditoria</span></a></li>
                    <li><a href="#tab-backup" class="tab-link"><span class="icon"></span> <span>Backup</span></a></li>
                    <li><a href="#tab-avancado" class="tab-link"><span class="icon"></span> <span>Avancado</span></a></li>
                </ul>
            </div>

            {{-- ================================== --}}
            {{-- CONTEUDO PRINCIPAL                 --}}
            {{-- ================================== --}}
            <div class="col-md-9">

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ===================================================== --}}
                    {{-- TAB: GERAIS                                           --}}
                    {{-- ===================================================== --}}
                    <div id="tab-geral" class="tab-content-settings">
                        <div class="card settings-card mb-3">
                            <div class="settings-card-header">
                                <h5><span class="emoji"></span>Identidade da Instituicao</h5>
                                <div class="settings-card-subtitle">
                                    Informacoes basicas usadas em relatorios, cabecalhos e comunicacao oficial.
                                </div>
                            </div>

                            <div class="settings-card-body">
                                <div class="settings-group-label">Informacoes principais</div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nome da Instituicao</label>
                                        <input type="text" class="form-control" name="school_name"
                                               value="{{ $settings->school_name ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">E-mail Institucional</label>
                                        <input type="email" class="form-control" name="email"
                                               value="{{ $settings->email ?? '' }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Telefone</label>
                                        <input type="text" class="form-control" name="phone"
                                               value="{{ $settings->phone ?? '' }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Endereco</label>
                                        <input type="text" class="form-control" name="address"
                                               value="{{ $settings->address ?? '' }}">
                                    </div>
                                </div>

                                <hr>

                                <div class="settings-group-label">Identidade visual</div>

                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Logotipo (PNG/JPG)</label>
                                        <input type="file" class="form-control" name="logo">
                                        {{-- Se quiser exibir preview do logo atual, voce pode tratar aqui futuramente --}}
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Versao do Sistema</label>
                                        <input type="text" class="form-control" name="version"
                                               value="{{ $settings->version ?? '' }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ===================================================== --}}
                    {{-- TAB: ACADEMICAS  COM SUB-ABAS GRANDES (ESTILO 1)    --}}
                    {{-- ===================================================== --}}
                    <div id="tab-academico" class="tab-content-settings d-none-important">

                        {{-- ====== SUBNAV HORIZONTAL (botoes grandes) ====== --}}
                        <div class="academic-subnav-wrapper">
                            <div class="academic-subnav">

                                {{-- Cada botao ativa uma secao abaixo via data-section --}}
                                <button type="button"
                                        class="academic-subnav-btn active"
                                        data-section="ano-letivo">
                                    <span class="icon"></span>
                                    <span>Ano letivo & Avaliacao</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="calendario">
                                    <span class="icon"></span>
                                    <span>Calendario escolar</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="feriados">
                                    <span class="icon"></span>
                                    <span>Feriados & dias nao letivos</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="turnos">
                                    <span class="icon"></span>
                                    <span>Turnos da escola</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="modulos">
                                    <span class="icon"></span>
                                    <span>Estrutura modular</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="carga-curso">
                                    <span class="icon"></span>
                                    <span>Carga horaria / curso</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="fechamento">
                                    <span class="icon"></span>
                                    <span>Fechamento de notas</span>
                                </button>

                                <button type="button"
                                        class="academic-subnav-btn"
                                        data-section="promocao">
                                    <span class="icon"></span>
                                    <span>Promocao & Globais</span>
                                </button>
                            </div>
                        </div>

                        {{-- ============= SECAO 1  ANO LETIVO & AVALIACAO ============= --}}
                        <div class="academic-section" data-section="ano-letivo">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Configuracoes Academicas  Ano Letivo & Avaliacoes
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Parametros gerais do ano letivo, regras de avaliacao e frequencia.
                                    </div>
                                </div>

                                <div class="settings-card-body">

                                    {{-- GRUPO: Ano Letivo --}}
                                    <div class="settings-group-label">Ano Letivo</div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Ano Letivo Atual</label>
                                            <input type="text" class="form-control"
                                                   name="academic_settings[ano_letivo]"
                                                   value="{{ $settings->academic_settings['ano_letivo'] ?? '' }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Modelo do Ano</label>
                                            @php $modelo = $settings->academic_settings['modelo_ano'] ?? 'bimestre'; @endphp
                                            <select class="form-select" name="academic_settings[modelo_ano]">
                                                <option value="bimestre"  {{ $modelo=='bimestre' ? 'selected':'' }}>Bimestral</option>
                                                <option value="trimestre" {{ $modelo=='trimestre' ? 'selected':'' }}>Trimestral</option>
                                                <option value="anual"     {{ $modelo=='anual' ? 'selected':'' }}>Anual</option>
                                                <option value="modulos"   {{ $modelo=='modulos' ? 'selected':'' }}>Por modulos</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Dias Letivos Minimos</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[dias_letivos]"
                                                   value="{{ $settings->academic_settings['dias_letivos'] ?? '' }}">
                                        </div>
                                    </div>

                                    {{-- GRUPO: Avaliacoes --}}
                                    <hr>
                                    <div class="settings-group-label">Avaliacoes</div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Media Minima para Aprovacao</label>
                                            <input type="number" step="0.1" class="form-control"
                                                   name="academic_settings[media_minima]"
                                                   value="{{ $settings->academic_settings['media_minima'] ?? '' }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Escala de Notas</label>
                                            @php $escala = $settings->academic_settings['escala_notas'] ?? '0-10'; @endphp
                                            <select class="form-select" name="academic_settings[escala_notas]">
                                                <option value="0-10" {{ $escala=='0-10' ? 'selected':'' }}>0 a 10</option>
                                                <option value="0-100" {{ $escala=='0-100' ? 'selected':'' }}>0 a 100</option>
                                                <option value="conceitos" {{ $escala=='conceitos' ? 'selected':'' }}>Conceitos (A, B, C)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Politica de Aprovacao</label>
                                            @php $pol = $settings->academic_settings['politica_aprovacao'] ?? 'media_final'; @endphp
                                            <select class="form-select" name="academic_settings[politica_aprovacao]">
                                                <option value="media_final" {{ $pol=='media_final' ? 'selected':'' }}>Media Final</option>
                                                <option value="media_e_frequencia" {{ $pol=='media_e_frequencia' ? 'selected':'' }}>Media + Frequencia</option>
                                                <option value="conceitual" {{ $pol=='conceitual' ? 'selected':'' }}>Conceitual</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- GRUPO: Frequencia --}}
                                    <hr>
                                    <div class="settings-group-label">Frequencia</div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Limite de Faltas (por ano)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[limite_faltas]"
                                                   value="{{ $settings->academic_settings['limite_faltas'] ?? '' }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 2  CALENDARIO ESCOLAR ============= --}}
                        <div class="academic-section d-none-important" data-section="calendario">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Calendario Escolar
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Datas oficiais de inicio e fim do ano letivo e observacoes gerais.
                                    </div>
                                </div>

                                <div class="settings-card-body">
                                    <div class="settings-group-label">Periodo Letivo</div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Inicio do Ano Letivo</label>
                                            <input type="date" class="form-control"
                                                   name="academic_settings[inicio_ano_letivo]"
                                                   value="{{ $settings->academic_settings['inicio_ano_letivo'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Fim do Ano Letivo</label>
                                            <input type="date" class="form-control"
                                                   name="academic_settings[fim_ano_letivo]"
                                                   value="{{ $settings->academic_settings['fim_ano_letivo'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Observacoes gerais</label>
                                            <input type="text" class="form-control"
                                                   name="academic_settings[observacoes_ano_letivo]"
                                                   value="{{ $settings->academic_settings['observacoes_ano_letivo'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 3  FERIADOS & DIAS NAO LETIVOS ============= --}}
                        <div class="academic-section d-none-important" data-section="feriados">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Feriados e Dias Nao Letivos
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Cadastre feriados nacionais, municipais e institucionais importantes.
                                    </div>
                                </div>

                                @php
                                    $feriados = $settings->academic_settings['feriados'] ?? [];
                                    // Garante pelo menos 5 linhas
                                    $maxFeriados = max(5, count($feriados));
                                @endphp

                                <div class="settings-card-body">
                                    <div class="settings-group-label">Lista de feriados</div>

                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th style="width: 18%">Data</th>
                                                <th style="width: 32%">Nome</th>
                                                <th style="width: 20%">Tipo</th>
                                                <th style="width: 15%">Recorrente?</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i = 0; $i < $maxFeriados; $i++)
                                                @php
                                                    $row = $feriados[$i] ?? ['data' => null, 'nome' => null, 'tipo' => null, 'repetir' => true];
                                                    $tipo = $row['tipo'] ?? '';
                                                    $repetir = $row['repetir'] ?? false;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="date" class="form-control"
                                                               name="academic_settings[feriados][{{ $i }}][data]"
                                                               value="{{ $row['data'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               name="academic_settings[feriados][{{ $i }}][nome]"
                                                               value="{{ $row['nome'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select"
                                                                name="academic_settings[feriados][{{ $i }}][tipo]">
                                                            <option value="" {{ $tipo=='' ? 'selected':'' }}>--</option>
                                                            <option value="nacional" {{ $tipo=='nacional' ? 'selected':'' }}>Nacional</option>
                                                            <option value="estadual" {{ $tipo=='estadual' ? 'selected':'' }}>Estadual</option>
                                                            <option value="municipal" {{ $tipo=='municipal' ? 'selected':'' }}>Municipal</option>
                                                            <option value="institucional" {{ $tipo=='institucional' ? 'selected':'' }}>Institucional</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input"
                                                                   type="checkbox"
                                                                   name="academic_settings[feriados][{{ $i }}][repetir]"
                                                                {{ $repetir ? 'checked' : '' }}>
                                                            <label class="form-check-label">Repete todo ano</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 4  TURNOS DA ESCOLA ============= --}}
                        <div class="academic-section d-none-important" data-section="turnos">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Turnos da Escola
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Configure horarios e estrutura de aulas para cada turno.
                                    </div>
                                </div>

                                <div class="settings-card-body">

                                    @php
                                        $turnos = [
                                            'matutino' => ' Matutino',
                                            'vespertino' => ' Vespertino',
                                            'noturno' => ' Noturno',
                                        ];
                                    @endphp

                                    @foreach($turnos as $key => $titulo)
                                        @php
                                            $t = $settings->academic_settings['turnos'][$key] ?? [
                                                'ativo' => false,
                                                'inicio' => '',
                                                'fim' => '',
                                                'duracao_aula' => '',
                                                'intervalo' => '',
                                                'tempo_entre_aulas' => '',
                                                'quantidade_aulas' => '',
                                            ];
                                        @endphp

                                        <div class="card p-3 mb-3 shadow-sm">

                                            {{-- Switch para ativar/desativar o turno --}}
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input turno-toggle"
                                                       type="checkbox"
                                                       data-target="#turno-{{ $key }}"
                                                       name="academic_settings[turnos][{{ $key }}][ativo]"
                                                    {{ ($t['ativo'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">{{ $titulo }}</label>
                                            </div>

                                            <div id="turno-{{ $key }}" class="{{ ($t['ativo'] ?? false) ? '' : 'd-none' }}">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Inicio (hh:mm)</label>
                                                        <input type="time" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][inicio]"
                                                               value="{{ $t['inicio'] }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fim (hh:mm)</label>
                                                        <input type="time" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][fim]"
                                                               value="{{ $t['fim'] }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Duracao da Aula (min)</label>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][duracao_aula]"
                                                               value="{{ $t['duracao_aula'] }}">
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Intervalo/Recreio (min)</label>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][intervalo]"
                                                               value="{{ $t['intervalo'] }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tempo entre Aulas (min)</label>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][tempo_entre_aulas]"
                                                               value="{{ $t['tempo_entre_aulas'] }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Quantidade de Aulas no Turno</label>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[turnos][{{ $key }}][quantidade_aulas]"
                                                               value="{{ $t['quantidade_aulas'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 5  ESTRUTURA MODULAR (2a EDICAO) ============= --}}
                        <div class="academic-section d-none-important" data-section="modulos">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Estrutura Modular (2a Edicao)
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Use para configurar modulos de projetos como a 2a edicao do DaxCode.
                                    </div>
                                </div>

                                @php
                                    $modulos = $settings->academic_settings['modulos'] ?? [];
                                    $maxModulos = max(4, count($modulos));
                                @endphp

                                <div class="settings-card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Quantidade de Modulos</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[quantidade_modulos]"
                                                   value="{{ $settings->academic_settings['quantidade_modulos'] ?? '' }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Observacoes da Estrutura Modular</label>
                                            <input type="text" class="form-control"
                                                   name="academic_settings[obs_modulos]"
                                                   value="{{ $settings->academic_settings['obs_modulos'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="settings-group-label">Modulos</div>

                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>Modulo</th>
                                                <th>Carga Horaria (h)</th>
                                                <th>Inicio</th>
                                                <th>Fim</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i = 0; $i < $maxModulos; $i++)
                                                @php
                                                    $m = $modulos[$i] ?? ['nome' => null, 'carga_horaria' => null, 'inicio' => null, 'fim' => null];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               name="academic_settings[modulos][{{ $i }}][nome]"
                                                               value="{{ $m['nome'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[modulos][{{ $i }}][carga_horaria]"
                                                               value="{{ $m['carga_horaria'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control"
                                                               name="academic_settings[modulos][{{ $i }}][inicio]"
                                                               value="{{ $m['inicio'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="date" class="form-control"
                                                               name="academic_settings[modulos][{{ $i }}][fim]"
                                                               value="{{ $m['fim'] ?? '' }}">
                                                    </td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 6  CARGA HORARIA POR CURSO ============= --}}
                        <div class="academic-section d-none-important" data-section="carga-curso">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Carga Horaria por Curso
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Cursos principais (Creche, 1o ano, 2o ano, Projeto DaxCode, etc.).
                                    </div>
                                </div>

                                @php
                                    $cursos = $settings->academic_settings['cursos'] ?? [];
                                    $maxCursos = max(5, count($cursos));
                                @endphp

                                <div class="settings-card-body">
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>Curso</th>
                                                <th>Carga Horaria Total (h)</th>
                                                <th>Aulas Semanais</th>
                                                <th>Turno Padrao</th>
                                                <th>Modelo</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i = 0; $i < $maxCursos; $i++)
                                                @php
                                                    $c = $cursos[$i] ?? [
                                                        'nome' => null,
                                                        'carga_horaria_total' => null,
                                                        'aulas_semanais' => null,
                                                        'turno_padrao' => null,
                                                        'modelo' => null,
                                                    ];
                                                    $turnoPadrao = $c['turno_padrao'] ?? '';
                                                    $modeloCurso = $c['modelo'] ?? '';
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               name="academic_settings[cursos][{{ $i }}][nome]"
                                                               value="{{ $c['nome'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[cursos][{{ $i }}][carga_horaria_total]"
                                                               value="{{ $c['carga_horaria_total'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control"
                                                               name="academic_settings[cursos][{{ $i }}][aulas_semanais]"
                                                               value="{{ $c['aulas_semanais'] ?? '' }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-select"
                                                                name="academic_settings[cursos][{{ $i }}][turno_padrao]">
                                                            <option value="" {{ $turnoPadrao=='' ? 'selected':'' }}>--</option>
                                                            <option value="matutino" {{ $turnoPadrao=='matutino' ? 'selected':'' }}>Matutino</option>
                                                            <option value="vespertino" {{ $turnoPadrao=='vespertino' ? 'selected':'' }}>Vespertino</option>
                                                            <option value="noturno" {{ $turnoPadrao=='noturno' ? 'selected':'' }}>Noturno</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-select"
                                                                name="academic_settings[cursos][{{ $i }}][modelo]">
                                                            <option value="" {{ $modeloCurso=='' ? 'selected':'' }}>--</option>
                                                            <option value="bimestre"  {{ $modeloCurso=='bimestre' ? 'selected':'' }}>Bimestral</option>
                                                            <option value="trimestre" {{ $modeloCurso=='trimestre' ? 'selected':'' }}>Trimestral</option>
                                                            <option value="anual"     {{ $modeloCurso=='anual' ? 'selected':'' }}>Anual</option>
                                                            <option value="modulos"   {{ $modeloCurso=='modulos' ? 'selected':'' }}>Por modulos</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 7  FECHAMENTO DE NOTAS ============= --}}
                        <div class="academic-section d-none-important" data-section="fechamento">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Parametros de Fechamento de Notas
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Regras gerais para encerramento de lancamentos no boletim.
                                    </div>
                                </div>

                                @php
                                    $fech = $settings->academic_settings['fechamento'] ?? [];
                                @endphp

                                <div class="settings-card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Prazo para edicao (dias)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[fechamento][prazo_edicao]"
                                                   value="{{ $fech['prazo_edicao'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Data limite geral de fechamento</label>
                                            <input type="date" class="form-control"
                                                   name="academic_settings[fechamento][data_limite]"
                                                   value="{{ $fech['data_limite'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Peso Provas (%)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[fechamento][peso_provas]"
                                                   value="{{ $fech['peso_provas'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Peso Atividades (%)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[fechamento][peso_atividades]"
                                                   value="{{ $fech['peso_atividades'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                       name="academic_settings[fechamento][bloquear_lancamento]"
                                                    {{ ($fech['bloquear_lancamento'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Bloquear lancamento apos prazo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                       name="academic_settings[fechamento][arredondar_media]"
                                                    {{ ($fech['arredondar_media'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Arredondar media final</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Casas decimais (arredondamento)</label>
                                            @php $cd = $fech['casas_decimais'] ?? '1'; @endphp
                                            <select class="form-select"
                                                    name="academic_settings[fechamento][casas_decimais]">
                                                <option value="0" {{ $cd=='0' ? 'selected':'' }}>Inteiro</option>
                                                <option value="1" {{ $cd=='1' ? 'selected':'' }}>1 casa</option>
                                                <option value="2" {{ $cd=='2' ? 'selected':'' }}>2 casas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============= SECAO 8  PROMOCAO & REGRAS GLOBAIS ============= --}}
                        <div class="academic-section d-none-important" data-section="promocao">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji"></span>
                                        Regras de Promocao Automatica & Configuracoes Globais
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Criterios de promocao, recuperacao e parametros globais de horarios.
                                    </div>
                                </div>

                                @php
                                    $prom = $settings->academic_settings['promocao'] ?? [];
                                    $globais = $settings->academic_settings['globais'] ?? [];
                                @endphp

                                <div class="settings-card-body">
                                    {{-- REGRAS DE PROMOCAO --}}
                                    <div class="settings-group-label">Regras de Promocao Automatica</div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Criterio principal</label>
                                            @php $crit = $prom['criterio_principal'] ?? 'media_frequencia'; @endphp
                                            <select class="form-select"
                                                    name="academic_settings[promocao][criterio_principal]">
                                                <option value="media" {{ $crit=='media' ? 'selected':'' }}>Somente media</option>
                                                <option value="frequencia" {{ $crit=='frequencia' ? 'selected':'' }}>Somente frequencia</option>
                                                <option value="media_frequencia" {{ $crit=='media_frequencia' ? 'selected':'' }}>Media + Frequencia</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nota minima para aprovacao</label>
                                            <input type="number" step="0.1" class="form-control"
                                                   name="academic_settings[promocao][nota_minima_aprovacao]"
                                                   value="{{ $prom['nota_minima_aprovacao'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Frequencia minima (%)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[promocao][frequencia_minima]"
                                                   value="{{ $prom['frequencia_minima'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Nota minima na recuperacao</label>
                                            <input type="number" step="0.1" class="form-control"
                                                   name="academic_settings[promocao][nota_minima_recuperacao]"
                                                   value="{{ $prom['nota_minima_recuperacao'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                       name="academic_settings[promocao][reprovar_por_nota]"
                                                    {{ ($prom['reprovar_por_nota'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Reprovar automaticamente por nota</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                       name="academic_settings[promocao][reprovar_por_frequencia]"
                                                    {{ ($prom['reprovar_por_frequencia'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Reprovar por frequencia</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    {{-- CONFIGURACOES GLOBAIS DE HORARIOS / MODULOS --}}
                                    <div class="settings-group-label">Configuracoes Globais de Horarios e Modulos</div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Duracao padrao da aula (min)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[globais][duracao_padrao]"
                                                   value="{{ $globais['duracao_padrao'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Intervalo padrao (min)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[globais][intervalo_padrao]"
                                                   value="{{ $globais['intervalo_padrao'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Tempo entre aulas (min)</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[globais][tempo_entre_aulas]"
                                                   value="{{ $globais['tempo_entre_aulas'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Aulas em dia normal</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[globais][aulas_dia_normal]"
                                                   value="{{ $globais['aulas_dia_normal'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Aulas em horario reduzido</label>
                                            <input type="number" class="form-control"
                                                   name="academic_settings[globais][aulas_horario_reduzido]"
                                                   value="{{ $globais['aulas_horario_reduzido'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox"
                                                       name="academic_settings[globais][ativar_sabados_letivos]"
                                                    {{ ($globais['ativar_sabados_letivos'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label">Ativar sabados letivos</label>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Descricao / Regras para sabados letivos</label>
                                            <input type="text" class="form-control"
                                                   name="academic_settings[globais][descricao_sabados_letivos]"
                                                   value="{{ $globais['descricao_sabados_letivos'] ?? '' }}">
                                        </div>
                                    </div>

                                    <hr>

                                    {{-- REMATRICULA --}}
                                    <div class="settings-group-label">Rematricula</div>
                                    @php $rem = $settings->academic_settings['rematricula_tipo'] ?? 'manual'; @endphp

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo de Rematricula</label>
                                            <select class="form-select" name="academic_settings[rematricula_tipo]">
                                                <option value="manual" {{ $rem=='manual' ? 'selected':'' }}>Manual</option>
                                                <option value="automatica" {{ $rem=='automatica' ? 'selected':'' }}>Automatica</option>
                                                <option value="por_aprovacao" {{ $rem=='por_aprovacao' ? 'selected':'' }}>Somente quando aprovado</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div> {{-- FIM TAB ACADEMICO --}}

                    {{-- ===================================================== --}}
                    {{-- OUTRAS ABAS (placeholders por enquanto)              --}}
                    {{-- ===================================================== --}}
                    @foreach([
                        'documentos'   => ' Documentos & PDFs',
                        'usuarios'     => ' Usuarios & Acesso',
                        'notificacoes' => ' Notificacoes',
                        'financeiro'   => ' Financeiro',
                        'comunicacao'  => ' Comunicacao',
                        'logs'         => ' Logs & Auditoria',
                        'backup'       => ' Backup',
                        'avancado'     => ' Avancado',
                    ] as $id => $titulo)
                        <div id="tab-{{ $id }}" class="tab-content-settings d-none-important">
                            <div class="card settings-card mb-3">
                                <div class="settings-card-header">
                                    <h5>
                                        <span class="emoji">{{ explode(' ', $titulo)[0] }}</span>
                                        <span>{{ implode(' ', array_slice(explode(' ', $titulo), 1)) }}</span>
                                    </h5>
                                    <div class="settings-card-subtitle">
                                        Estas configuracoes serao detalhadas e preenchidas com campos reais numa proxima etapa.
                                    </div>
                                </div>
                                <div class="settings-card-body">
                                    <p class="text-muted mb-0">
                                        Em breve voce podera definir regras especificas para
                                        <strong>{{ $titulo }}</strong> diretamente por aqui.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- BOTAO SALVAR GERAL --}}
                    <div class="settings-footer">
                        <button class="btn btn-primary btn-save-settings" type="submit">
                             <span>Salvar alteracoes</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- SCRIPTS                                                   --}}
    {{-- ========================================================= --}}
    <script>
        // Tabs principais (Gerais, Academicas, etc.)
        document.querySelectorAll('.tab-link').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelectorAll('.tab-link').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                document.querySelectorAll('.tab-content-settings').forEach(tab => {
                    tab.classList.add('d-none-important');
                });

                const target = this.getAttribute('href');
                const el = document.querySelector(target);
                if (el) {
                    el.classList.remove('d-none-important');
                }
            });
        });

        // Sub-abas da aba "Academicas"
        const subnavButtons = document.querySelectorAll('.academic-subnav-btn');
        const sections = document.querySelectorAll('.academic-section');

        subnavButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const section = btn.dataset.section;

                // ativa botao
                subnavButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // mostra somente a secao correspondente
                sections.forEach(sec => {
                    if (sec.dataset.section === section) {
                        sec.classList.remove('d-none-important');
                    } else {
                        sec.classList.add('d-none-important');
                    }
                });
            });
        });

        // Switch dos turnos (mostra / esconde bloco do turno)
        document.querySelectorAll('.turno-toggle').forEach(chk => {
            chk.addEventListener('change', () => {
                const target = document.querySelector(chk.dataset.target);
                if (!target) return;
                if (chk.checked) target.classList.remove('d-none');
                else target.classList.add('d-none');
            });
        });
    </script>

    <style>
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
    </style>

    <script>
        // Fecha toast automaticamente em 4 segundos
        setTimeout(() => {
            let toast = document.querySelector('.toast');
            if (toast) {
                let bsToast = bootstrap.Toast.getOrCreateInstance(toast);
                bsToast.hide();
            }
        }, 4000);
    </script>

@endsection
