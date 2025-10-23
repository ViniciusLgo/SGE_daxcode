<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('aluno_registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('turma_id')->nullable()->constrained('turmas')->nullOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete(); // quem cadastrou (admin/professor)

            $table->string('tipo')->comment('Tipo do registro ex: Atestado, Declaração, Advertência');
            $table->string('categoria')->nullable()->comment('Frequência, Acadêmico, Financeiro...');
            $table->text('descricao')->nullable();
            $table->string('arquivo')->nullable(); // caminho do arquivo (storage/app/public/...)
            $table->date('data_evento')->nullable();
            $table->enum('status', ['pendente', 'validado', 'arquivado', 'expirado'])->default('pendente');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aluno_registros');
    }
};
