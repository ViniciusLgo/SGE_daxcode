<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    public static function next(string $tipo, ?int $ano = null): string
    {
        $ano = $ano ?? (int) date('Y');
        $prefix = match ($tipo) {
            'aluno' => 'ALU',
            'matricula' => 'MAT',
            default => strtoupper($tipo),
        };

        $row = DB::table('codigo_sequencias')
            ->where('tipo', $tipo)
            ->where('ano', $ano)
            ->lockForUpdate()
            ->first();

        if (!$row) {
            $seq = self::inferSequenciaInicial($tipo, $ano);
            DB::table('codigo_sequencias')->insert([
                'tipo' => $tipo,
                'ano' => $ano,
                'sequencia' => $seq,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $seq = (int) $row->sequencia + 1;
            DB::table('codigo_sequencias')
                ->where('tipo', $tipo)
                ->where('ano', $ano)
                ->update([
                    'sequencia' => $seq,
                    'updated_at' => now(),
                ]);
        }

        return sprintf('%s-%d-%05d', $prefix, $ano, $seq);
    }

    protected static function inferSequenciaInicial(string $tipo, int $ano): int
    {
        $prefix = match ($tipo) {
            'aluno' => 'ALU',
            'matricula' => 'MAT',
            default => strtoupper($tipo),
        };

        if ($tipo === 'aluno') {
            $max = DB::table('alunos')
                ->where('matricula', 'like', $prefix . '-' . $ano . '-%')
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(matricula, "-", -1) AS UNSIGNED)) as max_seq')
                ->value('max_seq');
            return ((int) $max) + 1;
        }

        if ($tipo === 'matricula') {
            $max = DB::table('matriculas')
                ->where('codigo', 'like', $prefix . '-' . $ano . '-%')
                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(codigo, "-", -1) AS UNSIGNED)) as max_seq')
                ->value('max_seq');
            return ((int) $max) + 1;
        }

        return 1;
    }
}
