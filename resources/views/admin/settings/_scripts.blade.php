{{-- ========================================================= --}}
{{-- SCRIPTS  CONFIGURACOES DO SISTEMA (SGE DaxCode)          --}}
{{-- ========================================================= --}}

<script>
    document.addEventListener('DOMContentLoaded', () => {

        /* =====================================================
           TOGGLE DO SUBMENU ACADEMICO (SIDEBAR)
        ===================================================== */
        document.querySelectorAll('[data-toggle="submenu"]').forEach(button => {

            const group = button.closest('.settings-group');
            if (!group) return;

            // Estado inicial salvo
            const savedState = localStorage.getItem('settings-academico-open');
            if (savedState === 'true') {
                group.classList.add('open');
                button.setAttribute('aria-expanded', 'true');
            }

            button.addEventListener('click', () => {
                const isOpen = group.classList.toggle('open');
                button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                // Persistencia
                localStorage.setItem('settings-academico-open', isOpen);
            });
        });

        /* =====================================================
           TOAST (SUCESSO / ERRO)
        ===================================================== */
        setTimeout(() => {
            const toast = document.querySelector('.toast');
            if (toast && window.bootstrap) {
                bootstrap.Toast.getOrCreateInstance(toast).hide();
            }
        }, 4000);

    });
</script>
