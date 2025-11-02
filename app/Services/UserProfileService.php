<?php

namespace App\Services;

use App\Models\User;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Responsavel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserProfileService
{
    /**
     * Cria um usuário e o perfil vinculado (Aluno, Professor ou Responsável)
     */
    public static function createUserWithProfile(array $data, string $tipo)
    {
        $user = User::create([
            'name' => $data['name'] ?? ucfirst($tipo).' '.$data['id'] ?? Str::random(5),
            'email' => $data['email'] ?? strtolower($tipo).Str::random(4).'@temp.local',
            'password' => Hash::make($data['password'] ?? '123456'),
            'tipo' => $tipo,
        ]);

        match ($tipo) {
            'aluno' => Aluno::create(array_merge($data, ['user_id' => $user->id])),
            'professor' => Professor::create(array_merge($data, ['user_id' => $user->id])),
            'responsavel' => Responsavel::create(array_merge($data, ['user_id' => $user->id])),
            default => null,
        };

        return $user;
    }

    /**
     * Cria um perfil e vincula a um usuário existente (ou cria um novo user)
     */
    public static function createProfileForUser(User $user, array $data)
    {
        match ($user->tipo) {
            'aluno' => Aluno::create(array_merge($data, ['user_id' => $user->id])),
            'professor' => Professor::create(array_merge($data, ['user_id' => $user->id])),
            'responsavel' => Responsavel::create(array_merge($data, ['user_id' => $user->id])),
            default => null,
        };
    }
}
