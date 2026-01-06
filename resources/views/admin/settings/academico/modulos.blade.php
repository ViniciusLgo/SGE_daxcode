@php($isOpen = $open ?? false)
<details class="settings-card settings-collapse" {{ $isOpen ? 'open' : '' }}>
    <summary class="settings-card-header">
        <div>
            <h5>Estrutura modular</h5>
            <div class="settings-card-subtitle">
                Defina modulos, duracoes e distribuicao.
            </div>
        </div>
        <span class="settings-collapse-icon"></span>
    </summary>

    <div class="settings-card-body">
        <p class="text-muted mb-0">
            Conteudo desta area academica sera implementado na proxima etapa.
        </p>
    </div>
</details>
