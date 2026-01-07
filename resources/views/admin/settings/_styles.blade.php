<style>
    /* ==========================================================
       PALETA DAX
    ========================================================== */
    :root {
        --dax-yellow: #FFD54F;
        --dax-dark: #1E1E1E;
        --dax-gray: #2B2B2B;
        --dax-light: #F8F9FA;
        --dax-border: #E5E7EB;
        --dax-text: #1F2937;
    }

    /* ==========================================================
       WRAPPER GERAL
    ========================================================== */
    .settings-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* ==========================================================
       CONTAINER PRINCIPAL
       (SIDEBAR FIXA + CONTEUDO COM SCROLL)
    ========================================================== */
    .settings-container {
        display: flex;
        gap: 24px;
        height: calc(100vh - 180px); /* header + hero */
    }

    /* ==========================================================
       SIDEBAR
    ========================================================== */
    .settings-sidebar {
        width: 260px;
        background: var(--dax-dark);
        border-radius: 16px;
        padding: 16px;
        color: #fff;
        overflow-y: auto;
    }

    /* Titulo */
    .settings-sidebar-title {
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        opacity: 0.8;
        margin-bottom: 12px;
    }

    /* ==========================================================
       MENU
    ========================================================== */
    .settings-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .settings-item {
        margin-bottom: 6px;
    }

    .settings-link,
    .settings-sublink {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        color: #E5E7EB;
        background: transparent;
        border: none;
        cursor: pointer;
    }

    .settings-link:hover,
    .settings-sublink:hover {
        background: rgba(255, 213, 79, 0.12);
        color: var(--dax-yellow);
    }

    /* ACTIVE */
    .settings-link.active,
    .settings-sublink.active {
        background: var(--dax-yellow);
        color: var(--dax-dark);
        font-weight: 600;
    }

    /* ==========================================================
       SUBMENU
    ========================================================== */
    .settings-submenu {
        display: none;
        margin-top: 4px;
        padding-left: 12px;
    }

    .settings-group.open .settings-submenu {
        display: block;
    }

    .settings-group .chevron {
        transition: transform 0.2s ease;
    }

    .settings-group.open .chevron {
        transform: rotate(180deg);
    }

    /* ==========================================================
       CONTEUDO CENTRAL
    ========================================================== */
    .settings-content {
        flex: 1;
        background: var(--dax-light);
        border-radius: 16px;
        padding: 24px;
        overflow-y: auto;
    }

    /* ==========================================================
       FORMULARIO
    ========================================================== */
    .settings-form {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* ==========================================================
       FOOTER FIXO DO FORM
    ========================================================== */
    .settings-footer {
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid var(--dax-border);
        display: flex;
        justify-content: flex-end;
        background: var(--dax-light);
    }

    .btn-save-settings {
        background: var(--dax-yellow);
        border: none;
        color: var(--dax-dark);
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
    }

    .btn-save-settings:hover {
        opacity: 0.9;
    }
</style>
