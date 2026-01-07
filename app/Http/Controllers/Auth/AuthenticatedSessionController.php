<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe a tela de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Processa a autenticacao do usuario.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        //  Se for o primeiro login, redireciona para troca de senha
        if ($user->first_login) {
            return redirect()->route('auth.first_access');
        }

        //  Redireciona conforme o tipo de usuario
        return match ($user->tipo) {
            'admin' => redirect()->route('admin.dashboard'),
            'professor' => redirect()->route('dashboard.professor'),
            'aluno' => redirect()->route('dashboard.aluno'),
            'responsavel' => redirect()->route('dashboard.responsavel'),
            default => redirect()->route('login'),
        };
    }

    /**
     * Faz logout do usuario autenticado.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
