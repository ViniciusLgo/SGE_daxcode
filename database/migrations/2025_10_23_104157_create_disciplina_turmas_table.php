<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplina_turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disciplina_id')->constrained()->onDelete('cascade');
            $table->foreignId('turma_id')->constrained()->onDelete('cascade');
            $table->foreignId('professor_id')->nullable()->constrained('professores')->onDelete('set null');
            $table->string('ano_letivo')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplina_turmas');
    }
};
