{{-- ===================================================== --}}
{{-- SEÇÃO: GERAIS – IDENTIDADE DA INSTITUIÇÃO            --}}
{{-- ===================================================== --}}

<div class="settings-card mb-4">

    {{-- HEADER --}}
    <div class="settings-card-header">
        <h5>
            🏫 Identidade da Instituição
        </h5>

        <p class="settings-card-subtitle">
            Informações básicas utilizadas em relatórios, cabeçalhos e comunicações oficiais.
        </p>
    </div>

    {{-- BODY --}}
    <div class="settings-card-body">

        {{-- ============================== --}}
        {{-- INFORMAÇÕES PRINCIPAIS         --}}
        {{-- ============================== --}}
        <div class="settings-group-label">
            Informações principais
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nome da Instituição</label>
                <input type="text"
                       class="form-control"
                       name="school_name"
                       value="{{ $settings->school_name ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">E-mail Institucional</label>
                <input type="email"
                       class="form-control"
                       name="email"
                       value="{{ $settings->email ?? '' }}">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text"
                       class="form-control"
                       name="phone"
                       value="{{ $settings->phone ?? '' }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Endereço</label>
                <input type="text"
                       class="form-control"
                       name="address"
                       value="{{ $settings->address ?? '' }}">
            </div>
        </div>

        <hr>

        {{-- ============================== --}}
        {{-- IDENTIDADE VISUAL              --}}
        {{-- ============================== --}}
        <div class="settings-group-label">
            Identidade visual
        </div>

        <div class="row align-items-end">
            <div class="col-md-6 mb-3">
                <label class="form-label">Logotipo (PNG ou JPG)</label>
                <input type="file"
                       class="form-control"
                       name="logo">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Versão do Sistema</label>
                <input type="text"
                       class="form-control"
                       name="version"
                       value="{{ $settings->version ?? '' }}">
            </div>
        </div>

    </div>
</div>
