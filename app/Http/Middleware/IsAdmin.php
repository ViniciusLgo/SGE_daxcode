<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo === 'admin') {
            return $next($request);
        }

        abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
    }
}
