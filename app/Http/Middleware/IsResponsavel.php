<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsResponsavel
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo === 'responsavel') {
            return $next($request);
        }

        abort(403, 'Acesso negado. Apenas responsáveis podem acessar esta área.');
    }
}
