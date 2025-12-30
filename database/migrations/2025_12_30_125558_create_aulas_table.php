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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();

            /**
             * RELACIONAMENTOS PRINCIPAIS
             */
            $table->foreignId('turma_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('disciplina_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('professor_id')
                ->constrained('professores')
                ->cascadeOnDelete();


            /**
             * TIPO DA ATIVIDADE
             * Todos contam para cálculo de salário,
             * mas NÃO são todos aulas pedagógicas
             */
            $table->enum('tipo', [
                'aula',
                'reuniao',
                'evento',
                'conferencia',
                'outro'
            ])->default('aula');

            /**
             * DATA E HORÁRIO REAL DA ATIVIDADE
             */
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');

            /**
             * CONTROLE DE TEMPO
             * - Aula padrão = 50 minutos
             * - quantidade_blocos = quantas aulas seguidas
             */
            $table->unsignedTinyInteger('quantidade_blocos')->default(1);
            $table->unsignedSmallInteger('duracao_minutos');

            /**
             * CONTEÚDO E REGISTRO PEDAGÓGICO
             */
            $table->string('conteudo')->nullable();
            $table->text('observacoes')->nullable();

            /**
             * ATIVIDADE EXTRACLASSE
             */
            $table->boolean('atividade_casa')->default(false);

            /**
             * AUDITORIA
             * Quem registrou essa aula / atividade
             */
            $table->foreignId('user_id_registro')
                ->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
