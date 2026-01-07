{{-- ========================================================= --}}
{{-- CONTAINER PRINCIPAL  SIDEBAR + CONTEUDO                  --}}
{{-- ========================================================= --}}

@php
    $sec = request('sec', 'geral');
    $sub = request('sub');

    $secLabels = [
        'geral' => 'Gerais',
        'academico' => 'Academicas',
        'documentos' => 'Documentos & PDFs',
        'usuarios' => 'Usuarios & Acesso',
        'notificacoes' => 'Notificacoes',
        'financeiro' => 'Financeiro',
        'comunicacao' => 'Comunicacao',
        'logs' => 'Logs & Auditoria',
        'backup' => 'Backup',
        'avancado' => 'Avancado',
    ];

    $academicoSubs = [
        'ano-letivo'  => 'Ano letivo & Avaliacoes',
        'calendario'  => 'Calendario escolar',
        'feriados'    => 'Feriados & dias nao letivos',
        'turnos'      => 'Turnos da escola',
        'modulos'     => 'Estrutura modular',
        'carga-curso' => 'Carga horaria / curso',
        'fechamento'  => 'Fechamento de notas',
        'promocao'    => 'Promocao & globais',
    ];

    $secTitle = $secLabels[$sec] ?? 'Configuracoes';
    $subTitle = $sec === 'academico' && $sub ? ($academicoSubs[$sub] ?? null) : null;
@endphp

<div class="settings-container">

    {{-- SIDEBAR --}}
    <aside class="settings-sidebar">
        @include('admin.settings._sidebar')
    </aside>

    {{-- CONTEUDO CENTRAL --}}
    <main class="settings-content">

        <form action="{{ route('admin.settings.update') }}"
              method="POST"
              enctype="multipart/form-data"
              class="settings-form">

            @csrf
            @method('PUT')

            {{-- BARRA FIXA DE ACAO --}}
            <div class="settings-topbar">
                <div class="settings-topbar-info">
                    <strong>Edicao ativa:</strong>
                    <span>{{ $secTitle }}@if($subTitle) / {{ $subTitle }}@endif</span>
                </div>
                <button type="submit" class="btn btn-primary btn-save-settings">
                    Salvar alteracoes
                </button>
            </div>

            {{-- CABECALHO DA SECAO --}}
            <div class="settings-section-header">
                <div>
                    <div class="settings-section-title">
                        {{ $secTitle }}@if($subTitle) <span class="settings-section-sub">/ {{ $subTitle }}</span>@endif
                    </div>
                    <div class="settings-section-desc">
                        Ajuste as configuracoes desta area e salve ao final.
                    </div>
                </div>
                <div class="settings-section-badge">
                    {{ strtoupper($sec) }}
                </div>
            </div>

            {{-- ================================================= --}}
            {{-- SECAO: GERAL                                     --}}
            {{-- ================================================= --}}
            @if ($sec === 'geral')
                @include('admin.settings.tabs.geral')
            @endif

            {{-- ================================================= --}}
            {{-- SECAO: ACADEMICO                                 --}}
            {{-- ================================================= --}}
            @if ($sec === 'academico')
                @include('admin.settings.tabs.academico')

            @endif

            {{-- ================================================= --}}
            {{-- SECOES SIMPLES (PLACEHOLDERS)                    --}}
            {{-- ================================================= --}}
            @if ($sec === 'documentos')
                @include('admin.settings.tabs.documentos')
            @endif

            @if ($sec === 'usuarios')
                @include('admin.settings.tabs.usuarios')
            @endif

            @if ($sec === 'notificacoes')
                @include('admin.settings.tabs.notificacoes')
            @endif

            @if ($sec === 'financeiro')
                @include('admin.settings.tabs.financeiro')
            @endif

            @if ($sec === 'comunicacao')
                @include('admin.settings.tabs.comunicacao')
            @endif

            @if ($sec === 'logs')
                @include('admin.settings.tabs.logs')
            @endif

            @if ($sec === 'backup')
                @include('admin.settings.tabs.backup')
            @endif

            @if ($sec === 'avancado')
                @include('admin.settings.tabs.avancado')
            @endif

            {{-- ================================================= --}}
            {{-- FOOTER FIXO DO FORMULARIO                         --}}
            {{-- ================================================= --}}
            <div class="settings-footer">
                <button type="submit" class="btn btn-primary btn-save-settings">
                     <span>Salvar alteracoes</span>
                </button>
            </div>

        </form>

    </main>

</div>
