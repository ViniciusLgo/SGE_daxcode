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
        Schema::create('presenca_alunos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('presenca_id')
                ->constrained('presencas')
                ->cascadeOnDelete();

            $table->foreignId('aluno_id')
                ->constrained('alunos')
                ->cascadeOnDelete();

            // Blocos de presença (até 6 h/a)
            $table->boolean('bloco_1')->default(false);
            $table->boolean('bloco_2')->default(false);
            $table->boolean('bloco_3')->default(false);
            $table->boolean('bloco_4')->default(false);
            $table->boolean('bloco_5')->default(false);
            $table->boolean('bloco_6')->default(false);

            $table->foreignId('justificativa_falta_id')
                ->nullable()
                ->constrained('justificativa_faltas');

            $table->text('observacao')->nullable();

            $table->timestamps();

            // Um aluno só pode ter um registro por presença
            $table->unique(['presenca_id', 'aluno_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presenca_alunos');
    }
};
