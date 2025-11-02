<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Professores — cria FK se não existir
        if (!$this->foreignKeyExists('professores', 'professores_user_id_foreign')) {
            Schema::table('professores', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
        }

        // 2️⃣ Responsáveis — remove FK duplicada e mantém apenas uma (CASCADE)
        try {
            DB::statement('ALTER TABLE responsaveis DROP FOREIGN KEY responsaveis_usuario_id_foreign');
        } catch (\Throwable $e) {
            // ignora se já não existir
        }

        if (!$this->foreignKeyExists('responsaveis', 'responsaveis_user_id_foreign')) {
            Schema::table('responsaveis', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('professores', fn(Blueprint $t) => $t->dropForeign(['user_id']));
        Schema::table('responsaveis', fn(Blueprint $t) => $t->dropForeign(['user_id']));
    }

    private function foreignKeyExists(string $table, string $key): bool
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
        ", [$table, $key]);

        return !empty($result);
    }
};
