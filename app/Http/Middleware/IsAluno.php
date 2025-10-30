<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAluno
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo === 'aluno') {
            return $next($request);
        }

        abort(403, 'Acesso negado. Apenas alunos podem acessar esta área.');
    }
}
