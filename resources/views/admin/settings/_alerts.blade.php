{{-- ========================================================= --}}
{{-- ALERTAS DE ERRO / SUCESSO / TOAST                         --}}
{{-- ========================================================= --}}

@if ($errors->any())
    <div class="alert alert-danger rounded-3 shadow-sm p-3 mb-4"
         style="animation: fadeIn .3s;">
        <strong> Existem erros no formulario:</strong>
        <ul class="mt-2 mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3"
         style="z-index: 9999; animation: slideInRight .5s;">
        <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                     {{ session('success') }}
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="toast-container position-fixed top-0 end-0 p-3"
         style="z-index: 9999; animation: slideInRight .5s;">
        <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                     {{ session('error') }}
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif

{{-- Auto-fechar toast --}}
<script>
    setTimeout(() => {
        const toast = document.querySelector('.toast');
        if (toast && window.bootstrap) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toast);
            bsToast.hide();
        }
    }, 4000);
</script>

<style>
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to   { opacity: 1; transform: translateX(0); }
    }
</style>
