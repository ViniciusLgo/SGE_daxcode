<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JustificativaFalta;

class JustificativaFaltaSeeder extends Seeder
{
    public function run(): void
    {
        $dados = [
            ['nome' => 'Doença', 'exige_observacao' => false],
            ['nome' => 'Atestado médico', 'exige_observacao' => true],
            ['nome' => 'Problema familiar', 'exige_observacao' => false],
            ['nome' => 'Transporte', 'exige_observacao' => false],
            ['nome' => 'Atraso', 'exige_observacao' => false],
            ['nome' => 'Falta sem justificativa', 'exige_observacao' => false],
            ['nome' => 'Atividade externa', 'exige_observacao' => false],
            ['nome' => 'Compromisso escolar', 'exige_observacao' => false],
            ['nome' => 'Outro', 'exige_observacao' => true],
        ];

        foreach ($dados as $item) {
            JustificativaFalta::firstOrCreate(
                ['nome' => $item['nome']],
                $item
            );
        }
    }
}
