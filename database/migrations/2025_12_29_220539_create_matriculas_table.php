<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();

            // 1 matrícula por aluno (regra do seu domínio)
            $table->foreignId('aluno_id')
                ->constrained('alunos')
                ->cascadeOnDelete()
                ->unique();

            // Turma atual do aluno (pode mudar ao longo do tempo)
            $table->foreignId('turma_id')
                ->constrained('turmas')
                ->restrictOnDelete();

            // Código permanente do aluno (ex.: ALU-2025-00001)
            $table->string('codigo', 255)->unique();

            // Situação acadêmica atual
            $table->enum('status', ['ativo', 'desistente', 'transferido', 'trancado', 'concluido'])
                ->default('ativo');

            // Quando o status atual foi definido
            $table->dateTime('data_status')->nullable();

            // Motivo e observação para status/trocas relevantes
            $table->string('motivo', 255)->nullable();
            $table->text('observacao')->nullable();

            // Quem alterou por último (admin/secretaria)
            $table->foreignId('user_id_alteracao')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Índices úteis para filtros/relatórios
            $table->index(['turma_id', 'status']);
            $table->index(['status', 'data_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
