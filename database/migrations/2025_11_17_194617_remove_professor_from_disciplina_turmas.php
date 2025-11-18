<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disciplina_turmas', function (Blueprint $table) {
            if (Schema::hasColumn('disciplina_turmas', 'professor_id')) {
                $table->dropForeign(['professor_id']);
                $table->dropColumn('professor_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disciplina_turmas', function (Blueprint $table) {
            if (!Schema::hasColumn('disciplina_turmas', 'professor_id')) {
                $table->foreignId('professor_id')
                    ->nullable()
                    ->constrained('professores')
                    ->nullOnDelete();
            }
        });
    }
};
