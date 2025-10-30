<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstAccessController extends Controller
{
    /**
     * Mostra a tela de troca de senha no primeiro acesso.
     */
    public function index()
    {
        return view('auth.first_access');
    }

    /**
     * Atualiza a senha e libera o usuário para o sistema.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->first_login = false; // libera o acesso
        $user->save();

        return match ($user->tipo) {
            'admin' => redirect()->route('dashboard')->with('success', 'Senha atualizada!'),
            'professor' => redirect()->route('dashboard.professor')->with('success', 'Senha atualizada!'),
            'aluno' => redirect()->route('dashboard.aluno')->with('success', 'Senha atualizada!'),
            'responsavel' => redirect()->route('dashboard.responsavel')->with('success', 'Senha atualizada!'),
            default => redirect()->route('dashboard'),
        };
    }
}
