<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsaveis', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique()->nullable();
            $table->string('telefone', 50)->nullable();
            $table->string('cpf', 14)->unique()->nullable();
            $table->string('grau_parentesco', 50)->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responsaveis');
    }
};
