<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->safeIndex('despesas', 'idx_despesas_data', 'data');
        $this->safeIndex('despesas', 'idx_despesas_categoria', 'categoria_id');
        $this->safeIndex('despesas', 'idx_despesas_centro', 'centro_custo_id');
        $this->safeIndex('despesas', 'idx_despesas_responsavel', 'responsavel_id');

        $this->safeIndex('avaliacao_resultados', 'idx_avaliacao_resultados_avaliacao', 'avaliacao_id');
        $this->safeIndex('avaliacao_resultados', 'idx_avaliacao_resultados_aluno', 'aluno_id');

        $this->safeIndex('presencas', 'idx_presencas_aula', 'aula_id');
        $this->safeIndex('presenca_alunos', 'idx_presenca_alunos_presenca', 'presenca_id');
        $this->safeIndex('presenca_alunos', 'idx_presenca_alunos_aluno', 'aluno_id');
    }

    public function down(): void
    {
        $this->safeDropIndex('despesas', 'idx_despesas_data');
        $this->safeDropIndex('despesas', 'idx_despesas_categoria');
        $this->safeDropIndex('despesas', 'idx_despesas_centro');
        $this->safeDropIndex('despesas', 'idx_despesas_responsavel');

        $this->safeDropIndex('avaliacao_resultados', 'idx_avaliacao_resultados_avaliacao');
        $this->safeDropIndex('avaliacao_resultados', 'idx_avaliacao_resultados_aluno');

        $this->safeDropIndex('presencas', 'idx_presencas_aula');
        $this->safeDropIndex('presenca_alunos', 'idx_presenca_alunos_presenca');
        $this->safeDropIndex('presenca_alunos', 'idx_presenca_alunos_aluno');
    }

    private function safeIndex(string $table, string $name, string $column): void
    {
        try {
            DB::statement("CREATE INDEX $name ON $table ($column)");
        } catch (\Throwable $e) {
            // ignore if already exists
        }
    }

    private function safeDropIndex(string $table, string $name): void
    {
        try {
            DB::statement("DROP INDEX $name ON $table");
        } catch (\Throwable $e) {
            // ignore if missing
        }
    }
};
