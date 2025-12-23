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
        Schema::create('secretaria_atendimentos', function (Blueprint $table) {
            $table->id();

            // Tipo do atendimento
            $table->string('tipo');

            // Relacionamentos (opcionais no início)
            $table->foreignId('aluno_id')->nullable()
                ->constrained('alunos')
                ->nullOnDelete();

            $table->foreignId('responsavel_id')->nullable()
                ->constrained('responsaveis')
                ->nullOnDelete();

            // Status do atendimento
            $table->enum('status', ['pendente', 'concluido', 'cancelado'])
                ->default('pendente');

            // Observações
            $table->text('observacao')->nullable();

            // Data do atendimento
            $table->date('data_atendimento')->default(now());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretaria_atendimentos');
    }

};
