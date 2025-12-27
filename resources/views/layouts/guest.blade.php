<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SGE DaxCode — Acesso ao Sistema</title>

    {{-- Fonte --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Ícones --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center
             bg-gradient-to-br from-[#1F2933] via-[#2F3B2F] to-[#4B5D3B]
             font-inter text-slate-800">

<div class="w-full max-w-5xl
                bg-white/10 backdrop-blur-xl
                rounded-3xl shadow-2xl
                overflow-hidden
                flex flex-col md:flex-row
                border border-white/20">

    {{-- Lado institucional --}}
    <div class="md:w-1/2
                    bg-gradient-to-b from-[#4B5D3B] to-[#3A4A2E]
                    text-white
                    flex flex-col justify-center items-center
                    p-10 text-center">

        <i class="bi bi-mortarboard-fill text-7xl mb-5 text-[#F4C430] drop-shadow-lg"></i>

        <h1 class="text-4xl font-bold tracking-tight mb-3">
            SGE DaxCode
        </h1>

        <p class="text-[#F8F4E6] text-sm leading-relaxed max-w-xs">
            Sistema de Gestão Escolar,<br>
            desenvolvido com os valores da DAX OIL.
        </p>
    </div>

    {{-- Conteúdo dinâmico --}}
    <div class="md:w-1/2 w-full
                    p-8 md:p-12
                    bg-slate-50">

        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>
    </div>

</div>

{{-- Rodapé --}}
<footer class="absolute bottom-4 text-center text-white/70 text-xs">
    © {{ date('Y') }} DaxCode Tecnologia — Grupo DAX OIL
</footer>

</body>
</html>
