{{-- ========================================================= --}}
{{-- CONTAINER PRINCIPAL  SIDEBAR + CONTEUDO                  --}}
{{-- ========================================================= --}}

@php
    $sec = request('sec', 'geral');
    $sub = request('sub');
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

                @if ($sub)
                    @includeIf('admin.settings.academico.' . $sub)
                @else
                    {{-- fallback: visao geral academica --}}
                    @include('admin.settings.tabs.academico')
                @endif

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
