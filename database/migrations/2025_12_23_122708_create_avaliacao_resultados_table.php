<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('avaliacao_resultados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('avaliacao_id')
                ->constrained('avaliacoes')
                ->cascadeOnDelete();

            $table->foreignId('aluno_id')
                ->constrained('alunos')
                ->cascadeOnDelete();

            $table->decimal('nota', 4, 2)->nullable();

            $table->string('arquivo')->nullable();

            $table->text('observacao')->nullable();

            $table->boolean('entregue')->default(false);

            $table->timestamps();

            // Garante 1 resultado por aluno por avaliação
            $table->unique(['avaliacao_id', 'aluno_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacao_resultados');
    }
};
