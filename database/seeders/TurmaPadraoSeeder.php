<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turma;

class TurmaPadraoSeeder extends Seeder
{
    public function run(): void
    {
        Turma::firstOrCreate(
            ['nome' => 'Turma Padrão'],
            [
                'ano' => 2025,
                'turno' => 'Indefinido',
                'descricao' => 'Turma padrão do sistema',
            ]
        );
    }
}
