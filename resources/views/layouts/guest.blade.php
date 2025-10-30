<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SGE DaxCode — Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-[#004AAD] via-[#0076D4] to-[#00BFA6] flex items-center justify-center min-h-screen font-inter text-gray-900">
<div class="w-full max-w-5xl bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/20">

    <!-- Lado esquerdo -->
    <div class="md:w-1/2 bg-gradient-to-b from-[#004AAD] to-[#00BFA6] text-white flex flex-col justify-center items-center p-10">
        <i class="bi bi-mortarboard-fill text-7xl mb-4 text-white drop-shadow-md"></i>
        <h1 class="text-4xl font-bold tracking-tight mb-2">SGE DaxCode</h1>
        <p class="text-[#D7FDF3] text-center text-sm leading-relaxed max-w-xs">
            Sistema de Gestão Escolar Inteligente<br>
            conectando escola, professores e famílias.
        </p>
    </div>

    <!-- Lado direito (formulário) -->
    <div class="md:w-1/2 w-full p-8 md:p-12 bg-[#F8FAFC]">
        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>
    </div>
</div>

<footer class="absolute bottom-4 text-center text-white/70 text-xs">
    © {{ date('Y') }} DaxCode Tecnologia — Todos os direitos reservados.
</footer>
</body>
</html>
