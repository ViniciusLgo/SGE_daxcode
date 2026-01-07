{{-- ===================================================== --}}
{{-- ACADEMICO: ANO LETIVO                               --}}
{{-- ===================================================== --}}

@php($isOpen = $open ?? false)
<details class="settings-card settings-collapse" {{ $isOpen ? 'open' : '' }}>

    <summary class="settings-card-header">
        <div>
            <h5>Ano Letivo & Avaliacoes</h5>
            <p class="settings-card-subtitle">
                Configuracao do ano letivo, periodos avaliativos e regras academicas globais.
            </p>
        </div>
        <span class="settings-collapse-icon"></span>
    </summary>

    <div class="settings-card-body">
        <p class="text-muted mb-0">
            Nesta secao sera possivel cadastrar e gerenciar os anos letivos,
            definir o ano ativo, configurar periodos avaliativos, criterios
            de fechamento e regras academicas que impactam todo o sistema.
        </p>
    </div>

</details>
