<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'              => 'Administrador',
                'password'          => Hash::make('admin@admin.com'),
                'tipo'              => 'admin',
                'first_login'       => 0, // já entra como configurado
                'email_verified_at' => now(),
            ]
        );
    }
}
