@php($isOpen = $open ?? false)
<details class="settings-card settings-collapse" {{ $isOpen ? 'open' : '' }}>
    <summary class="settings-card-header">
        <div>
            <h5>Carga horaria / curso</h5>
            <div class="settings-card-subtitle">
                Ajuste cargas horarias e parametros de cursos.
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
