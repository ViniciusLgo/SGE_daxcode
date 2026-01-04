<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aula_id')
                ->constrained('aulas')
                ->cascadeOnDelete();

            $table->foreignId('turma_id')
                ->constrained('turmas');

            $table->foreignId('disciplina_id')
                ->constrained('disciplinas');

            $table->foreignId('professor_id')
                ->constrained('professores');

            $table->date('data');

            $table->unsignedTinyInteger('quantidade_blocos');

            $table->enum('status', ['aberta', 'finalizada'])
                ->default('aberta');

            $table->timestamps();

            // Evita duplicar presença para a mesma aula
            $table->unique('aula_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};
