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

    .settings-hero {
        background: linear-gradient(135deg, rgba(255, 213, 79, 0.35), rgba(255, 255, 255, 0.9));
        border: 1px solid var(--dax-border);
        border-radius: 18px;
        padding: 20px 24px;
    }

    .settings-hero-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--dax-dark);
    }

    .settings-hero-subtitle {
        font-size: 13px;
        color: #4B5563;
        max-width: 520px;
        margin-top: 6px;
    }

    .settings-hero-icon {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid var(--dax-border);
        font-size: 18px;
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

    .settings-menu-title {
        margin: 16px 0 6px;
        font-size: 11px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
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

    .settings-topbar {
        position: sticky;
        top: 0;
        z-index: 5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 16px;
        margin-bottom: 16px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(6px);
        border: 1px solid var(--dax-border);
        border-radius: 12px;
    }

    .settings-topbar-info {
        font-size: 12px;
        color: #6B7280;
    }

    .settings-topbar-info strong {
        color: var(--dax-dark);
    }

    .settings-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
        padding: 16px 18px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--dax-border);
    }

    .settings-section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dax-dark);
    }

    .settings-section-sub {
        color: #6B7280;
        font-weight: 600;
    }

    .settings-section-desc {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .settings-section-badge {
        background: rgba(255, 213, 79, 0.2);
        color: var(--dax-dark);
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
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

    .settings-card {
        border: 1px solid var(--dax-border);
        border-radius: 14px;
        background: #fff;
    }

    .settings-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid var(--dax-border);
        background: #FAFBFC;
    }

    .settings-card-body {
        padding: 16px;
    }

    .settings-card h5 {
        font-size: 15px;
        font-weight: 700;
        color: var(--dax-dark);
        margin: 0 0 4px 0;
    }

    .settings-card-subtitle {
        font-size: 12px;
        color: #6B7280;
    }

    .settings-group-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6B7280;
        margin-bottom: 10px;
    }

    .settings-stack {
        display: grid;
        gap: 14px;
    }

    .settings-collapse {
        overflow: hidden;
    }

    .settings-collapse > summary {
        list-style: none;
        cursor: pointer;
    }

    .settings-collapse > summary::-webkit-details-marker {
        display: none;
    }

    .settings-collapse-icon {
        width: 22px;
        height: 22px;
        border-radius: 999px;
        border: 1px solid var(--dax-border);
        position: relative;
        flex-shrink: 0;
    }

    .settings-collapse-icon::before,
    .settings-collapse-icon::after {
        content: '';
        position: absolute;
        background: var(--dax-dark);
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .settings-collapse-icon::before {
        width: 10px;
        height: 2px;
    }

    .settings-collapse-icon::after {
        width: 2px;
        height: 10px;
    }

    .settings-collapse[open] .settings-collapse-icon::after {
        opacity: 0;
    }

    .settings-content .form-control {
        border-radius: 10px;
        border: 1px solid var(--dax-border);
        padding: 10px 12px;
        background: #fff;
    }

    .settings-content .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .settings-content hr {
        border-color: var(--dax-border);
        margin: 20px 0;
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

    @media (max-width: 1024px) {
        .settings-container {
            flex-direction: column;
            height: auto;
        }

        .settings-sidebar {
            width: 100%;
        }

        .settings-content {
            padding: 20px;
        }
    }
</style>
