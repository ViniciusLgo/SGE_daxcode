<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricula_historicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matricula_id')
                ->constrained('matriculas')
                ->cascadeOnDelete();

            // O que aconteceu: mudança de status ou troca de turma
            $table->enum('tipo_evento', ['status', 'troca_turma']);

            // Para mudança de status
            $table->enum('status_anterior', ['ativo', 'desistente', 'transferido', 'trancado', 'concluido'])
                ->nullable();
            $table->enum('status_novo', ['ativo', 'desistente', 'transferido', 'trancado', 'concluido'])
                ->nullable();

            // Para troca de turma
            $table->foreignId('turma_anterior_id')
                ->nullable()
                ->constrained('turmas')
                ->nullOnDelete();

            $table->foreignId('turma_nova_id')
                ->nullable()
                ->constrained('turmas')
                ->nullOnDelete();

            // Motivo e observação do evento
            $table->string('motivo', 255)->nullable();
            $table->text('observacao')->nullable();

            // Quem realizou a ação
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['matricula_id', 'tipo_evento']);
            $table->index(['tipo_evento', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricula_historicos');
    }
};
