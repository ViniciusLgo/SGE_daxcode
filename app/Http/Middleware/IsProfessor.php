<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsProfessor
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo === 'professor') {
            return $next($request);
        }

        abort(403, 'Acesso negado. Apenas professores podem acessar esta área.');
    }
}
