<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->text('descricao')->nullable();

            $table->enum('status', ['aberta', 'encerrada'])->default('aberta');

            $table->foreignId('turma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained()->cascadeOnDelete();

            $table->date('data_avaliacao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
