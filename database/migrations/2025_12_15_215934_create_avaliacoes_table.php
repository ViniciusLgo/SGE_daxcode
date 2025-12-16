<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('turma_id')
                ->constrained('turmas')
                ->cascadeOnDelete();

            $table->foreignId('disciplina_id')
                ->constrained('disciplinas')
                ->cascadeOnDelete();

            $table->foreignId('professor_id')
                ->constrained('professores')
                ->cascadeOnDelete();

            $table->string('titulo');

            $table->enum('tipo', [
                'Prova',
                'Trabalho',
                'Projeto',
                'Atividade',
                'Atividade Extra'
            ]);

            $table->decimal('peso', 5, 2)->default(1)->nullable();

            $table->date('data')->nullable();

            $table->enum('status', ['ativa', 'encerrada'])
                ->default('ativa');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
